<?php

namespace App\Http\Controllers\admin\dashboard;

use App\Http\Controllers\Controller;
use App\Models\general\books\lbbooks;
use App\Models\general\bookings\lbbooking;
use App\Models\general\notes\lbnotes;
use App\Models\general\disputes\lbdispute;
use App\Models\student\lbstudent;
use App\Models\teacher\lbteacher;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    /**
     * Fetch all 6 stat card values for Admin Dashboard:
     *  1. Total Books
     *  2. Active Users (students + teachers)
     *  3. Pending Reservations
     *  4. Overdue Books
     *  5. Notes Uploaded
     *  6. Books Issued Today
     */
    public function fetchAdminStatsForDashboard(Request $request)
    {
        $totalBooks = lbbooks::count();

        $totalStudents = lbstudent::count();
        $totalTeachers = lbteacher::count();
        $activeUsers   = $totalStudents + $totalTeachers;

        $pendingReservations = lbbooking::where('status', 'reserved')->count();

        $overdueBooks = lbbooking::where('status', 'overdue')->count();

        $notesUploaded = lbnotes::count();

        $booksIssuedToday = lbbooking::where('status', 'issued')
            ->whereDate('created_at', Carbon::today())
            ->count();

        return response()->json([
            'success'             => true,
            'totalBooks'          => $totalBooks,
            'activeUsers'         => $activeUsers,
            'pendingReservations' => $pendingReservations,
            'overdueBooks'        => $overdueBooks,
            'notesUploaded'       => $notesUploaded,
            'booksIssuedToday'    => $booksIssuedToday,
        ]);
    }

    /**
     * Fetch recent disputes (all users) for Admin Dashboard.
     * Returns latest 5 disputes, newest first.
     */
    public function fetchAdminRecentDisputes(Request $request)
    {
        $disputes = lbdispute::orderBy('created_at', 'desc')
            ->limit(5)
            ->get([
                'id',
                'title',
                'status',
                'created_at',
            ]);

        return response()->json([
            'success'  => true,
            'disputes' => $disputes,
        ]);
    }
}