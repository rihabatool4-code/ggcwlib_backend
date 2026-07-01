<?php

namespace App\Http\Controllers\Teacher\auth;

use App\Http\Controllers\Controller;
use App\Models\Teacher\Lbteacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

            return response()->json([
                "success" => true,
                "token"   => $token,
                "teacher" => $teacher
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

            return response()->json([
                "success" => true,
                "teacher" => $teacher
            ]);

        } catch (\Exception $e) {

            return response()->json([
                "success" => false,
                "error" => $e->getMessage()
            ], 401);

        }
    }

    public function updateProfile(Request $request)
    {
        $teacher = lbteacher::find($request->teacher_id);

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
}