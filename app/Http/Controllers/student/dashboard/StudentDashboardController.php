<?php

namespace App\Http\Controllers\student\dashboard;

use App\Http\Controllers\Controller;
use App\Models\general\bookings\lbbooking;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    public function fetchStudentStatsForDashboard(Request $request){
        // return response()->json(['request' => $request->toArray()]);
        $totalIssuedBooks = lbbooking::where(['lbstudent_id' => $request->lbstudent_id, 'status' => 'issued'])->get()->count();
        $totalReservedBooks = lbbooking::where(['lbstudent_id' => $request->lbstudent_id, 'status' => 'reserved'])->get()->count();
        
        return response()->json(['success' =>true, 'totalIssued' => $totalIssuedBooks, 'totalReserved' => $totalReservedBooks]);
    }
}
