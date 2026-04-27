<?php

namespace App\Http\Controllers\student\auth;

use App\Http\Controllers\Controller;
use App\Models\student\Lbstudent;
use Illuminate\Http\Request;

class StudentAuthController extends Controller
{
    public function studentRegister(Request $request)
    {
        // return response()->json(["request"=>$request->toArray()]);

        try {
            $student = Lbstudent::create($request->all());
            if ($student != null) {
                return response()->json(["success" => true, "message" => "Student created successfully", "student" => $student]);
            } else {
                return response()->json(["success" => false, "message" => "Account cannot created at the moment"]);
            }
        } catch (\Exception $e) {
            // return $e->getMessage();
        }
    }

    public function studentLogin(Request $request)
    {
        // return response()->json(['request'=>$request->toArray()]);
        try {
            $student = Lbstudent::where(["email" => $request->email, "password" => $request->password])->first();

            if ($student != null) {
                return response()->json(["success" => true, "student" => $student]);
            } else {
                return response()->json(["success" => falase, "message" => "Wrong credentials, try again"]);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
