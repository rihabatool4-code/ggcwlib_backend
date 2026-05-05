<?php

namespace App\Http\Controllers\student\auth;

use App\Http\Controllers\Controller;
use App\Models\student\Lbstudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentAuthController extends Controller
{
    public function studentRegister(Request $request)
    {
        // return response()->json(["request"=>$request->toArray()]);

        try {
            $student = Lbstudent::create($request->except('password'));
            if ($student != null) {
                 $student->update([
                    "password" => Hash::make($request->password)
                ]);
                return response()->json(["success" => true, "message" => "Student created successfully", "student" => $student]);
            } else {
                return response()->json(["success" => false, "message" => "Account cannot created at the moment"]);
            }
        } catch (\Exception $e) {
            return response()->json(["erorr" => $e->getMessage()]);
        }
    }

    public function studentLogin(Request $request)
{
    // return response()->json(["request" => $request->toArray()]);
    try {
        $credentials = $request->only('email', 'password');

        if (!$token = auth('Lbstudent')->attempt($credentials)) {
            return response()->json([
                "success" => false,
                "message" => "Invalid credentials"
            ]);
        }

        $student = auth('Lbstudent')->user();

        return response()->json([
            "success" => true,
            "token" => $token,
            "student" => $student
        ]);

    } catch (\Exception $e) {
        return response()->json(["error" => $e->getMessage()]);
    }
}
}