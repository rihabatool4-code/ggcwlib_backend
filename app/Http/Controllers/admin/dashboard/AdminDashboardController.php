<?php

namespace App\Http\Controllers\admin\dashboard;

use App\Http\Controllers\Controller;
use App\Models\admin\Lbbook;
use App\Models\general\bookings\Lbbooking;
use App\Models\note\Lbnote;
use App\Models\general\dispute\Lbdispute;
use App\Models\student\Lbstudent;
use App\Models\teacher\Lbteacher;
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
        $totalBooks = Lbbook::count();

        $totalStudents = Lbstudent::count();
        $totalTeachers = Lbteacher::count();
        $activeUsers   = $totalStudents + $totalTeachers;

        $pendingReservations = Lbbooking::where('status', 'reserved')->count();

        $overdueBooks = Lbbooking::where('status', 'overdue')->count();

        $notesUploaded = Lbnote::count();

        $booksIssuedToday = Lbbooking::where('status', 'issued')
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
        $disputes = Lbdispute::orderBy('created_at', 'desc')
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