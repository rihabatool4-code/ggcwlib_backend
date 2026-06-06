<?php

namespace App\Http\Controllers\admin\auth;

use App\Http\Controllers\Controller;
use App\Models\admin\Lbadmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function adminRegister(Request $request)
    {
        // return response()->json(["request"=>$request->toArray()]);

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
       // return response()->json(["request" => $request->toArray()]);
        try {
            $credentials = $request->only('email', 'password');

            if (!$token = auth('Lbadmin')->attempt($credentials)) {
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
    // 👇 Yeh naya function add karein saare subadmins ka data lane ke liye
    public function loadAllSubAdmins()
    {
        try {
            // Database se saare admins ka data fetch karein
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
}