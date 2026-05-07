<?php

namespace App\Http\Controllers\admin\auth;

use App\Http\Controllers\Controller;
use App\Models\admin\Lbadmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function adminLogin(Request $request)
    {
        return response()->json(["request" => $request->toArray()]);
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
}