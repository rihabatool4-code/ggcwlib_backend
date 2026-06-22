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