<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\teacher\Lbteacher;
use App\Models\student\Lbstudent;   
use App\Models\general\conversation\Lbconversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function registerTeacher(Request $request)
    {
        try {
            $teacherData = $request->all();
            $teacherData['password'] = Hash::make($request->password);
            $teacher = Lbteacher::create($teacherData);

             if ($teacher != null) {

             Lbconversation::create(['lbteacher_id' => $teacher->id,'type' => 'ai']);

    return response()->json([
        "success" => true,
        "message" => "Teacher created successfully",
        "Teacher" => $teacher
    ]);
     }

            return response()->json(["success" => false, "message" => "Account cannot be created at the moment"]);
        } catch (\Exception $e) {
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
            return response()->json(["error" => $e->getMessage()]);
        }
    }

    // ─── ADD THIS FUNCTION ───────────────────────────────────────
    public function loadAllStudents()
    {
        try {
            $students = Lbstudent::all();
            if ($students != null) {
                return response()->json(["success" => true, "students" => $students]);
            } else {
                return response()->json(["success" => false, "message" => "No record found"]);
            }
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }
    
}