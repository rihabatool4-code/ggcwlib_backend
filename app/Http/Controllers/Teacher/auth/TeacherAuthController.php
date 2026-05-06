<?php

namespace App\Http\Controllers\Teacher\auth;

use App\Http\Controllers\Controller;
use App\Models\Teacher\Lbteacher;
use Illuminate\Http\Request;

class TeacherAuthController extends Controller
{
    public function teacherLogin(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (!$token = auth('Lbteacher')->attempt($credentials)) {
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
}