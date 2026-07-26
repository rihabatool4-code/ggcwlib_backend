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

    /* ── Toggle Student Suspend/Activate ── */
    public function toggleStudentStatus(Request $request)
    {
        try {
            $student = Lbstudent::find($request->student_id);

            if (!$student) {
                return response()->json(["success" => false, "message" => "Student not found"]);
            }

            $student->status = ($student->status === "Suspended") ? "Active" : "Suspended";
            $student->save();

            return response()->json([
                "success" => true,
                "message" => "Student " . strtolower($student->status) . " successfully",
                "student" => $student
            ]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }

    /* ── Toggle Teacher Suspend/Activate ── */
    public function toggleTeacherStatus(Request $request)
    {
        try {
            $teacher = Lbteacher::find($request->teacher_id);

            if (!$teacher) {
                return response()->json(["success" => false, "message" => "Teacher not found"]);
            }

            $teacher->status = ($teacher->status === "Suspended") ? "Active" : "Suspended";
            $teacher->save();

            return response()->json([
                "success" => true,
                "message" => "Teacher " . strtolower($teacher->status) . " successfully",
                "teacher" => $teacher
            ]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }
}