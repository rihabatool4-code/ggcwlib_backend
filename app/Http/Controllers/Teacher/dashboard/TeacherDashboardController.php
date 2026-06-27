<?php

namespace App\Http\Controllers\Teacher\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeacherDashboardController extends Controller
{
    /**
     * Fetch all dashboard stats for a teacher:
     *  - Total issued books
     *  - Total reserved books
     *  - Total notes uploaded
     */
    public function fetchTeacherStatsForDashboard(Request $request)
    {
        $teacherId = $request->lbteacher_id;

        $totalIssuedBooks = lbbooking::where([
            'lbteacher_id' => $teacherId,
            'status'       => 'issued',
        ])->count();

        $totalReservedBooks = lbbooking::where([
            'lbteacher_id' => $teacherId,
            'status'       => 'reserved',
        ])->count();

        $totalNotesUploaded = lbnotes::where('lbteacher_id', $teacherId)->count();

        return response()->json([
            'success'            => true,
            'totalIssued'        => $totalIssuedBooks,
            'totalReserved'      => $totalReservedBooks,
            'totalNotesUploaded' => $totalNotesUploaded,
        ]);
    }

    /**
     * Fetch recent disputes raised against / by this teacher.
     * Returns latest 5 disputes, newest first.
     */
    public function fetchTeacherRecentDisputes(Request $request)
    {
        $teacherId = $request->lbteacher_id;

        $disputes = lbdispute::where('lbteacher_id', $teacherId)
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
