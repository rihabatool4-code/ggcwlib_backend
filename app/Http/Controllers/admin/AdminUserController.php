<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\teacher\Lbteacher;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function registerTeacher(Request $request)
    {
        try {
            // return response()->json(["request" => $request->toArray()]);
            // Step 7: Password ko Hash karna zaroori hai
            // $teacherData = $request->all();
            // $teacherData['password'] = \Illuminate\Support\Facades\Hash::make($request->password);

            // $teacher = Lbteacher::create($teacherData);
            $teacher = Lbteacher::create($request->all());

            if ($teacher != null) {
                return response()->json(["success" => true, "message" => "Teacher created successfully", "Teacher" => $teacher]);
            }

            return response()->json(["success" => false, "message" => "Account cannot be created at the moment"]);
        } catch (\Exception $e) {
            // return response()->json(["success" => false, "message" => $e->getMessage()], 500);
            return response()->json(["error" => $e->getMessage()]);
        }
    }
    public function loadAllTeacher()
    {
        try {
            $teachers = Lbteacher::all();
            if ($teachers != null) {
                return response()->json(["success" => true, "teachers" => $teachers]);
            } else {
                return response()->json(["success" => false, "message" => "No record found"]);
            }
        } catch (\Exception $e) {
            // return response()->json(["success" => false, "message" => $e->getMessage()], 500);
            return response()->json(["error" => $e->getMessage()]);
        }
    }
}
