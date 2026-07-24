<?php

namespace App\Http\Controllers\Teacher\auth;

use App\Http\Controllers\Controller;
use App\Models\Teacher\Lbteacher;
use App\Models\general\conversation\Lbconversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Mail\TeacherResetPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TeacherAuthController extends Controller
{
    public function teacherLogin(Request $request)
    {
        try {

            $credentials = $request->only('email', 'password');

             if (!$token = auth('Lbteacher')->claims(['guard' => 'teacher'])->attempt($credentials)) {

             return response()->json([
             "success" => false,
             "message" => "Invalid credentials"
            ]);
             }
             $teacher = auth('Lbteacher')->user();

            $conversation = Lbconversation::where('lbteacher_id',$teacher->id)->where('type', 'ai')
            ->first();

            return response()->json([
                "success" => true,
                "token"   => $token,
                "teacher" => $teacher,
                "lbconversation" =>$conversation
            ]);

        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }

    // ── AUTO-LOGIN ENDPOINT ──
    // Frontend refresh hone par localStorage se authToken uthayega, token decode
    // karke guard='teacher' nikalega, aur is endpoint ko call karega. Yeh token
    // verify karke logged-in teacher ka data wapas bhej deta hai.
    public function me(Request $request)
{
    try {
        $teacher = auth('Lbteacher')->user();

        if (!$teacher) {

            return response()->json([
                "success" => false,
                "message" => "Unauthenticated"
            ], 401);

        }

        $conversation = Lbconversation::where('lbteacher_id', $teacher->id)
            ->where('type', 'ai')
            ->first();

        return response()->json([
            "success" => true,
            "teacher" => $teacher,
            "lbconversation" => $conversation
        ]);

    } catch (\Exception $e) {

        return response()->json([
            "success" => false,
            "error" => $e->getMessage()
        ]);

    }
   }

    public function updateProfile(Request $request)
    {
        $teacher = Lbteacher::find($request->teacher_id);

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Teacher not found'
            ]);
        }

        $teacher->name = $request->name;
        $teacher->phone = $request->phone;
        $teacher->save();

        return response()->json([
            'success' => true,
            'teacher' => $teacher
        ]);
    }

    public function changePassword(Request $request)
    {
        $teacher = Lbteacher::find($request->teacher_id);

        if (!$teacher) {
            return response()->json([
                "success" => false,
                "message" => "Teacher not found"
            ]);
        }

        if (!Hash::check($request->current_password, $teacher->password)) {
            return response()->json([
                "success" => false,
                "message" => "Current password is incorrect"
            ]);
        }

        $teacher->password = Hash::make($request->new_password);
        $teacher->save();

        return response()->json([
            "success" => true,
            "message" => "Password updated successfully",
            "teacher" => $teacher
        ]);
    }
    public function forgotPassword(Request $request)
    {
        try {

            $teacher = Lbteacher::where('email', $request->email)->first();

            if (!$teacher) {

                return response()->json([
                    "success" => false,
                    "message" => "No account found with this email address"
                ]);

            }

            DB::table('password_resets')
                ->where('email', $request->email)
                ->where('user_type', 'teacher')
                ->delete();

            $token = Str::random(60);

            DB::table('password_resets')->insert([
                'email' => $request->email,
                'user_type' => 'teacher',
                'token' => Hash::make($token),
                'created_at' => Carbon::now()
            ]);

            Mail::to($request->email)->send(new TeacherResetPasswordMail($token, $request->email));

            return response()->json([
                "success" => true,
                "message" => "Password reset link has been sent to your email"
            ]);

        } catch (\Exception $e) {

            return response()->json([
                "success" => false,
                "error" => $e->getMessage()
            ]);

        }
    }

    public function resetPassword(Request $request)
    {
        try {

            $record = DB::table('password_resets')
                ->where('email', $request->email)
                ->where('user_type', 'teacher')
                ->first();

            if (!$record) {

                return response()->json([
                    "success" => false,
                    "message" => "Invalid or expired reset link"
                ]);

            }

            if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {

                DB::table('password_resets')->where('email', $request->email)->where('user_type', 'teacher')->delete();

                return response()->json([
                    "success" => false,
                    "message" => "Reset link has expired. Please request a new one"
                ]);

            }

            if (!Hash::check($request->token, $record->token)) {

                return response()->json([
                    "success" => false,
                    "message" => "Invalid reset link"
                ]);

            }

            $teacher = Lbteacher::where('email', $request->email)->first();

            if (!$teacher) {

                return response()->json([
                    "success" => false,
                    "message" => "Teacher not found"
                ]);

            }

            $teacher->password = Hash::make($request->new_password);
            $teacher->save();

            DB::table('password_resets')->where('email', $request->email)->where('user_type', 'teacher')->delete();

            return response()->json([
                "success" => true,
                "message" => "Password has been reset successfully"
            ]);

        } catch (\Exception $e) {

            return response()->json([
                "success" => false,
                "error" => $e->getMessage()
            ]);

        }
    }
}