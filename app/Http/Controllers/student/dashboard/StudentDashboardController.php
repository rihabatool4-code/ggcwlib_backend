<?php

namespace App\Http\Controllers\student\dashboard;

use App\Http\Controllers\Controller;
use App\Models\general\bookings\Lbbooking;
use App\Models\general\dispute\Lbdispute;
use Illuminate\Http\Request;
use Carbon\Carbon; // <--- Yeh line top par lazmi use karein date checking ke liye

class StudentDashboardController extends Controller
{
    /**
     * Fetch all dashboard stats for a student:
     */
    public function fetchStudentStatsForDashboard(Request $request)
    {
        $studentId = $request->lbstudent_id;

        // 1. Total Issued Books
        $totalIssuedBooks = Lbbooking::where([
            'lbstudent_id' => $studentId,
            'status'       => 'issued',
        ])->count();

        // 2. Total Reserved Books
        $totalReservedBooks = Lbbooking::where([
            'lbstudent_id' => $studentId,
            'status'       => 'reserved',
        ])->count();

        // 3. Total Overdue Books (Dynamic Check)
        // Woh books jo issued hain aur unki return/due date aaj se pehle ki thi
        $totalOverdueBooks = Lbbooking::where([
            'lbstudent_id' => $studentId,
            'status'       => 'issued', 
        ])
        ->where('due_date', '<', Carbon::now()) // check if due_date is in the past
        ->count();

        return response()->json([
            'success'       => true,
            'totalIssued'   => $totalIssuedBooks,
            'totalReserved' => $totalReservedBooks,
            'totalOverdue'  => $totalOverdueBooks,
        ]);
    }

    // ... aapka bakaya fetchStudentRecentDisputes method yahan rahega
}