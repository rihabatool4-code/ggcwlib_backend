<?php

namespace App\Http\Controllers\admin\dashboard;

use App\Http\Controllers\Controller;
use App\Models\admin\Lbbook;
use App\Models\general\bookings\Lbbooking;
use App\Models\note\Lbnote;
use App\Models\general\dispute\Lbdispute;
use App\Models\general\reviews\LbReview;
use App\Models\student\Lbstudent;
use App\Models\teacher\Lbteacher;

use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    /**
     * Fetch all 6 stat card values for Admin Dashboard:
     * 1. Total Books
     * 2. Active Users (students + teachers)
     * 3. Pending Reservations
     * 4. Overdue Books
     * 5. Notes Uploaded
     * 6. Books Issued Today
     */
    public function fetchAdminStatsForDashboard(Request $request)
    {
        try {

            $totalBooks = Lbbook::count();

            $totalStudents = Lbstudent::count();
            $totalTeachers = Lbteacher::count();

            $activeUsers = $totalStudents + $totalTeachers;

            $pendingReservations = Lbbooking::where('status','reserved')->count();

            $overdueBooks = Lbbooking::where('status', 'issued')->whereDate('due_date', '<', Carbon::today())->count();
            
            $notesUploaded = Lbnote::count();

            $booksIssuedToday = Lbbooking::where('status','issued')->whereDate('updated_at',Carbon::today())->count();

            return response()->json([

                'success' => true,

                'totalBooks' => $totalBooks,

                'activeUsers' => $activeUsers,

                'pendingReservations' => $pendingReservations,

                'overdueBooks' => $overdueBooks,

                'notesUploaded' => $notesUploaded,

                'booksIssuedToday' => $booksIssuedToday,

            ], 200);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' => 'Error while fetching admin dashboard statistics.',

                'error' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile(),

            ], 500);
        }
    }



// ...

/**
 * Fetch latest 5 disputes for Admin Dashboard.
 */
public function fetchAdminRecentDisputes(Request $request)
{
    try {

        $disputes = Lbdispute::with(['lbstudent', 'lbteacher'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($dispute) {

                $isStudent = strtolower($dispute->raisedby) === 'student';

                return [
                    'id'         => $dispute->id,
                    'ticket'     => '#DSP-' . str_pad($dispute->id, 3, '0', STR_PAD_LEFT),
                    'name'       => $isStudent
                                        ? optional($dispute->lbstudent)->fullName
                                        : optional($dispute->lbteacher)->name,
                    'role'       => $isStudent ? 'Student' : 'Teacher',
                    'subject'    => $dispute->subject,
                    'status'     => $dispute->status,
                    'created_at' => $dispute->created_at,
                ];
            });

        return response()->json([

            'success' => true,

            'disputes' => $disputes,

        ], 200);

    } catch (\Exception $e) {

        return response()->json([

            'success' => false,

            'message' => 'Error while fetching admin recent disputes.',

            'error' => $e->getMessage(),

            'line' => $e->getLine(),

            'file' => $e->getFile(),

        ], 500);
    }
}

/**
 * Fetch latest 3 reviews for Admin Dashboard.
 */
public function fetchAdminRecentReviews(Request $request)
{
    try {

        $reviews = LbReview::with(['lbstudent', 'lbteacher'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($review) {

                $isStudent = !is_null($review->lbstudent_id);

                return [
                    'id'         => $review->id,
                    'name'       => $isStudent
                                        ? optional($review->lbstudent)->fullName
                                        : optional($review->lbteacher)->name,
                    'role'       => $isStudent ? 'Student' : 'Teacher',
                    'rating'     => $review->rating,
                    'review'     => $review->review,
                    'status'     => $review->status,
                    'created_at' => $review->created_at,
                ];
            });

        return response()->json([

            'success' => true,

            'reviews' => $reviews,

        ], 200);

    } catch (\Exception $e) {

        return response()->json([

            'success' => false,

            'message' => 'Error while fetching admin recent reviews.',

            'error' => $e->getMessage(),

            'line' => $e->getLine(),

            'file' => $e->getFile(),

        ], 500);
    }
}

/**
 * Fetch latest 4 requests (reservations + issues) for Admin Dashboard.
 */
public function fetchAdminRecentRequests(Request $request)
{
    try {

        $requests = Lbbooking::with(['lbstudent', 'lbbook'])
            ->whereIn('status', ['reserved', 'issued'])
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get()
            ->map(function ($booking) {

                return [
                    'id'         => $booking->id,
                    'name'       => optional($booking->lbstudent)->fullName,
                    'book'       => optional($booking->lbbook)->title,
                    'type'       => $booking->status === 'reserved' ? 'Reservation' : 'Issue',
                    'created_at' => $booking->created_at,
                ];
            });

        return response()->json([

            'success' => true,

            'requests' => $requests,

        ], 200);

    } catch (\Exception $e) {

        return response()->json([

            'success' => false,

            'message' => 'Error while fetching admin recent requests.',

            'error' => $e->getMessage(),

            'line' => $e->getLine(),

            'file' => $e->getFile(),

        ], 500);
    }
}
    /**
 * Fetch overdue book records (issued + due_date < today) for Admin Dashboard.
 */
public function fetchAdminOverdueAlerts(Request $request)
{
    try {

        $today = Carbon::today();

        $overdue = Lbbooking::with(['lbstudent', 'lbteacher', 'lbbook'])
            ->where('status', 'issued')
            ->whereDate('due_date', '<', $today)
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get()
            ->map(function ($booking) use ($today) {

                $daysOverdue = Carbon::parse($booking->due_date)->diffInDays($today);

                $isStudent = !is_null($booking->lbstudent_id);

                return [
                    'id'       => $booking->id,
                    'name'     => $isStudent
                                    ? optional($booking->lbstudent)->fullName
                                    : optional($booking->lbteacher)->name,
                    'roll_no'  => $isStudent ? optional($booking->lbstudent)->roll_no : null,
                    'role'     => $isStudent ? 'Student' : 'Teacher',
                    'book'     => optional($booking->lbbook)->title,
                    'due_date' => $booking->due_date,
                    'days'     => $daysOverdue,
                    'fine'     => $booking->fine ?? 0,
                ];
            });

        return response()->json([
            'success' => true,
            'overdue' => $overdue,
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error while fetching admin overdue book alerts.',
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
        ], 500);
    }
}
}