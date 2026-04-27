<?php

namespace App\Http\Controllers\Teacher\auth;

use App\Http\Controllers\Controller;
use App\Models\Teacher\Lbteacher;
use Illuminate\Http\Request;

class TeacherAuthController extends Controller
{
    public function registerteacher(Request $request)
    {
        try{
            $Teacher = Lbteacher::create($request->all());
            if($Teacher != null){
                return response()->json(["success" => true, "message" => "Teacher created successfully", "Teacher" => $Teacher]);
            }
            else{
                return response()->json(["success" => false, "message" => "Account cannot created at the moment"]);
            }
        } catch (\Exception $e){
            
        }
    }
}
