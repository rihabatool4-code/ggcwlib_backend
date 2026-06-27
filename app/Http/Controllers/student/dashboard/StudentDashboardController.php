<?php

namespace App\Http\Controllers\student\dashboard;

use App\Http\Controllers\Controller;
use App\Models\general\bookings\lbbooking;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    public function fetchStudentStatsForDashboard(Request $request)
    {
        $studentId = $request->lbstudent_id;

        $totalIssuedBooks = lbbooking::where([
            'lbstudent_id' => $studentId,
            'status'       => 'issued',
        ])->count();

        $totalReservedBooks = lbbooking::where([
            'lbstudent_id' => $studentId,
            'status'       => 'reserved',
        ])->count();

        $totalOverdueBooks = lbbooking::where([
            'lbstudent_id' => $studentId,
            'status'       => 'overdue',
        ])->count();

        return response()->json([
            'success'       => true,
            'totalIssued'   => $totalIssuedBooks,
            'totalReserved' => $totalReservedBooks,
            'totalOverdue'  => $totalOverdueBooks,
        ]);
    }

    /**
     * Fetch recent disputes raised by this student.
     * Returns latest 5 disputes, newest first.
     */
    public function fetchStudentRecentDisputes(Request $request)
    {
        $studentId = $request->lbstudent_id;

        $disputes = lbdispute::where('lbstudent_id', $studentId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get([
                'id',
                'title',
                'description',
                'status',
                'created_at',
            ]);

        return response()->json([
            'success'  => true,
            'disputes' => $disputes,
        ]);
    }
}
