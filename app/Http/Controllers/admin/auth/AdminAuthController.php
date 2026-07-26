<?php

namespace App\Http\Controllers\admin\auth;

use App\Http\Controllers\Controller;
use App\Models\admin\Lbadmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Mail\AdminResetPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminAuthController extends Controller
{
    public function adminRegister(Request $request)
    {
        try {
            $admin = Lbadmin::create($request->except('password'));
            if ($admin != null) {
                 $admin->update([
                    "password" => Hash::make($request->password)
                ]);
                return response()->json(["success" => true, "message" => "Admin created successfully", "admin" => $admin]);
            } else {
                return response()->json(["success" => false, "message" => "Account cannot created at the moment"]);
            }
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }

    public function adminLogin(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (!$token = auth('Lbadmin')->claims(['guard' => 'admin'])->attempt($credentials)) {
                return response()->json([
                    "success" => false,
                    "message" => "Invalid credentials"
                ]);
            }

            $admin = auth('Lbadmin')->user();

            return response()->json([
                "success" => true,
                "token" => $token,
                "admin" => $admin
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "error" => $e->getMessage()
            ]);
        }
    }

    // ── AUTO-LOGIN ENDPOINT ──
    // Same pattern as StudentAuthController::me(). Frontend refresh hone
    // par localStorage se token uthayega aur is endpoint ko call karega.
    // Yeh token verify karke logged-in admin ka data wapas bhej deta hai.
    public function me(Request $request)
    {
        try {

            $admin = auth('Lbadmin')->user();

            if (!$admin) {

                return response()->json([
                    "success" => false,
                    "message" => "Unauthenticated"
                ], 401);

            }

            return response()->json([
                "success" => true,
                "admin" => $admin
            ]);

        } catch (\Exception $e) {

            return response()->json([
                "success" => false,
                "error" => $e->getMessage()
            ], 401);

        }
    }

    public function loadAllSubAdmins()
    {
        try {
            $subadmins = Lbadmin::all();

            return response()->json([
                "success" => true,
                "data" => $subadmins
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "error" => $e->getMessage()
            ]);
        }
    }
    public function forgotPassword(Request $request)
    {
        try {

            $admin = Lbadmin::where('email', $request->email)->first();

            if (!$admin) {

                return response()->json([
                    "success" => false,
                    "message" => "No account found with this email address"
                ]);

            }

            DB::table('password_resets')
                ->where('email', $request->email)
                ->where('user_type', 'admin')
                ->delete();

            $token = Str::random(60);

            DB::table('password_resets')->insert([
                'email' => $request->email,
                'user_type' => 'admin',
                'token' => Hash::make($token),
                'created_at' => Carbon::now()
            ]);

            Mail::to($request->email)->send(new AdminResetPasswordMail($token, $request->email));

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
                ->where('user_type', 'admin')
                ->first();

            if (!$record) {

                return response()->json([
                    "success" => false,
                    "message" => "Invalid or expired reset link"
                ]);

            }

            if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {

                DB::table('password_resets')->where('email', $request->email)->where('user_type', 'admin')->delete();

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

            $admin = Lbadmin::where('email', $request->email)->first();

            if (!$admin) {

                return response()->json([
                    "success" => false,
                    "message" => "Admin not found"
                ]);

            }

            $admin->password = Hash::make($request->new_password);
            $admin->save();

            DB::table('password_resets')->where('email', $request->email)->where('user_type', 'admin')->delete();

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
    public function updateNotifications(Request $request)
{
    $admin = Lbadmin::find($request->lbadmin_id);

    if (!$admin) {

        return response()->json([
            "success" => false,
            "message" => "Admin not found"
        ]);

    }

    $admin->email_notif = $request->email_notifications;
    $admin->inApp_notif  = $request->inapp_notifications;

    $admin->save();

    return response()->json([
        "success" => true,
        "admin" => $admin
    ]);
    }
}