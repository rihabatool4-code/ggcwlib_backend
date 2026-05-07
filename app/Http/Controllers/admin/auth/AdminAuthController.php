<?php

namespace App\Http\Controllers\admin\auth;

use App\Http\Controllers\Controller;
use App\Models\admin\Lbadmin;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
public function adminLogin(Request $request)
    {
        // return response()->json(['request'=>$request->toArray()]);
        try {
             $credentials = $request->only('email', 'password');
            if (!$token = auth('Lbadmin')->attempt($credentials)) {
                return response()->json(["Message" => "Invalid credentials"]);
            }
            $admin = Lbadmin::where(["email" => $request->email, "password" =>  Hash::make($request->password)])->first();
             return response()->json(["token" => $token,"admin" => $admin]);

            //$student = Lbstudent::where(["email" => $request->email, "password" =>  $request->password])->first();

            //if ($student != null) {
                //return response()->json(["success" => true, "student" => $student]);
            //} else {
                //return response()->json(["success" => false, "message" => "Wrong credentials, try again"]);
            //}
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}