<?php

namespace App\Http\Controllers\Teacher\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Models ko yahan import kiya gaya hai:
use App\Models\general\bookings\Lbbooking; 
use App\Models\general\dispute\Lbdispute;
// Agar aapke notes model ka namespace different hai toh uske mutabiq adjust karein, standard yehi hoga:
use App\Models\note\Lbnote; 

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

        $totalIssuedBooks = Lbbooking::where([
            'lbteacher_id' => $teacherId,
            'status'       => 'issued',
        ])->count();

        $totalReservedBooks = Lbbooking::where([
            'lbteacher_id' => $teacherId,
            'status'       => 'reserved',
        ])->count();

        $totalNotesUploaded = Lbnote::where('lbteacher_id', $teacherId)->count();

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

        $disputes = Lbdispute::where('lbteacher_id', $teacherId)
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