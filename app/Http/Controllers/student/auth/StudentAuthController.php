<?php

namespace App\Http\Controllers\student\auth;

use App\Http\Controllers\Controller;
use App\Models\student\Lbstudent;
use App\Models\general\conversation\Lbconversation;
use Illuminate\Http\Request;
use App\Mail\StudentResetPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class StudentAuthController extends Controller
{
    public function studentRegister(Request $request)
    {
        try {

            // ── Email: fully unique across the system ──
            $existingEmail = Lbstudent::where('email', $request->email)->first();

            if ($existingEmail) {

                return response()->json([
                    "success" => false,
                    "message" => "This email is already registered."
                ]);

            }

            // ── Roll No: unique only within the same session ──
            // (e.g. CS-41 can exist in 2022-2026 session AND 2023-2027 session,
            // but not twice inside the same session)
            $existingRollNo = Lbstudent::where('roll_no', $request->roll_no)
          ->where('session', $request->input('session'))
          ->first();
  
            if ($existingRollNo) {

                return response()->json([
                    "success" => false,
                    "message" => "This Roll No is already registered for this session."
                ]);

            }

            $student = Lbstudent::create($request->except('password'));

            if ($student != null) {

                $student->update([
                    "password" => Hash::make($request->password)
                ]);
                Lbconversation::create(['lbstudent_id' => $student->id, 'type' => 'ai']);

                return response()->json([
                    "success" => true,
                    "message" => "Student created successfully",
                    "student" => $student
                ]);

            } else {

                return response()->json([
                    "success" => false,
                    "message" => "Account cannot be created at the moment"
                ]);

            }

        } catch (QueryException $e) {

            // DB-level unique constraint safety net (race condition ya missed check ke liye)
            if ($e->getCode() == 23000) {

                return response()->json([
                    "success" => false,
                    "message" => "This Roll No is already registered for this session, or email already exists."
                ]);

            }

            return response()->json([
                "success" => false,
                "error" => $e->getMessage()
            ]);

        } catch (\Exception $e) {

            return response()->json([
                "success" => false,
                "error" => $e->getMessage()
            ]);

        }
    }

    public function studentLogin(Request $request)
{
    try {

        $candidates = Lbstudent::where('roll_no', $request->roll_no)->get();

        $student = null;

        foreach ($candidates as $candidate) {
            if (Hash::check($request->password, $candidate->password)) {
                $student = $candidate;
                break;
            }
        }

        if (!$student) {

            return response()->json([
                "success" => false,
                "message" => "Invalid Roll No or password"
            ]);

        }

        // ── Block suspended accounts from logging in ──
        if ($student->status === "Suspended") {

            return response()->json([
                "success" => false,
                "message" => "Your account has been suspended. Please contact the library administration."
            ]);

        }

        $token = auth('Lbstudent')->claims(['guard' => 'student'])->login($student);

        $conversation = Lbconversation::where('lbstudent_id', $student->id)->where('type', 'ai')
        ->first();

        return response()->json([
            "success" => true,
            "token" => $token,
            "student" => $student,
            "lbconversation" => $conversation
        ]);

    } catch (\Exception $e) {

        return response()->json([
            "success" => false,
            "error" => $e->getMessage()
        ]);

    }
    }

    // ── AUTO-LOGIN ENDPOINT ──
    // Frontend refresh hone par localStorage se token uthayega aur is endpoint ko
    // call karega. Yeh token verify karke logged-in student ka data wapas bhej deta hai.
    public function me(Request $request)
    {
        try {

            $student = auth('Lbstudent')->user();

            if (!$student) {

                return response()->json([
                    "success" => false,
                    "message" => "Unauthenticated"
                ], 401);

            }

            $conversation = Lbconversation::where('lbstudent_id', $student->id)->where('type', 'ai')
            ->first();

            return response()->json([
                "success" => true,
                "student" => $student,
                "lbconversation" => $conversation
            ]);

        } catch (\Exception $e) {

            return response()->json([
                "success" => false,
                "error" => $e->getMessage()
            ], 401);

        }
    }
    public function refresh()
{
    try {
        $newToken = JWTAuth::parseToken()->refresh();

        return response()->json([
            'success' => true,
            'token' => $newToken,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Token refresh nahi ho saka.',
        ], 401);
    }
 }

    public function updateProfile(Request $request)
    {
        $student = Lbstudent::find($request->student_id);

        if (!$student) {

            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ]);

        }

        $student->fullName = $request->fullName;
        $student->phone = $request->phone;

        $student->save();

        return response()->json([
            'success' => true,
            'student' => $student
        ]);
    }

    public function changePassword(Request $request)
  {
        $student = Lbstudent::find($request->lbstudent_id);

        if (!$student) {

            return response()->json([
                "success" => false,
                "message" => "Student not found"
            ]);
        }

        if (!Hash::check($request->current_password, $student->password)) {

            return response()->json([
                "success" => false,
                "message" => "Current password is incorrect"
            ]);

        }

        $student->password = Hash::make($request->new_password);
        $student->save();

        return response()->json([
            "success" => true,
            "message" => "Password updated successfully",
            "student" => $student
        ]);
    }
  public function updateNotifications(Request $request)
{
    $student = Lbstudent::find($request->lbstudent_id);

    if (!$student) {

        return response()->json([
            "success" => false,
            "message" => "Student not found"
        ]);

    }
    $student->emailNotifications = $request->email_notifications;
    $student->inappNotifications = $request->inapp_notifications;

    $student->save();

    return response()->json([
        "success" => true,
        "student" => $student
    ]);
    }

    public function forgotPassword(Request $request)
    {
        try {

            $student = Lbstudent::where('email', $request->email)->first();

            if (!$student) {

                return response()->json([
                    "success" => false,
                    "message" => "No account found with this email address"
                ]);

            }

            // Purane tokens is email ke liye delete kar do
            DB::table('password_resets')
                ->where('email', $request->email)
                ->where('user_type', 'student')
                ->delete();

            $token = Str::random(60);

            DB::table('password_resets')->insert([
                'email' => $request->email,
                'user_type' => 'student',
                'token' => Hash::make($token),
                'created_at' => Carbon::now()
            ]);

            Mail::to($request->email)->send(new StudentResetPasswordMail($token, $request->email));

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
                ->where('user_type', 'student')
                ->first();

            if (!$record) {

                return response()->json([
                    "success" => false,
                    "message" => "Invalid or expired reset link"
                ]);

            }

            // Token 60 minute ke baad expire ho jayega
            if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {

                DB::table('password_resets')->where('email', $request->email)->where('user_type', 'student')->delete();

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

            $student = Lbstudent::where('email', $request->email)->first();

            if (!$student) {

                return response()->json([
                    "success" => false,
                    "message" => "Student not found"
                ]);

            }

            $student->password = Hash::make($request->new_password);
            $student->save();

            DB::table('password_resets')->where('email', $request->email)->where('user_type', 'student')->delete();

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