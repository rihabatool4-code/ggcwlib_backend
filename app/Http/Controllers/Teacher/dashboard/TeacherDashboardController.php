<?php

namespace App\Http\Controllers\Teacher\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\general\bookings\Lbbooking;
use App\Models\general\dispute\Lbdispute;
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
     * Fetch return date alerts for currently issued books (teacher).
     */
    public function fetchTeacherReturnAlerts(Request $request)
    {
        try {

            $teacherId = $request->lbteacher_id;
            $today = Carbon::today();

            $alerts = Lbbooking::with('lbbook')
                ->where('lbteacher_id', $teacherId)
                ->where('status', 'issued')
                ->orderBy('due_date', 'asc')
                ->limit(5)
                ->get()
                ->map(function ($booking) use ($today) {

                    $dueDate  = Carbon::parse($booking->due_date)->startOfDay();
                    $daysLeft = $today->diffInDays($dueDate, false); // negative = overdue

                    if ($daysLeft < 0) {
                        $type  = 'danger';
                        $badge = abs($daysLeft) . ' days overdue';
                    } elseif ($daysLeft <= 3) {
                        $type  = 'warning';
                        $badge = $daysLeft . ' days left';
                    } else {
                        $type  = 'success';
                        $badge = $daysLeft . ' days left';
                    }

                    return [
                        'id'    => $booking->id,
                        'title' => optional($booking->lbbook)->title,
                        'due'   => $booking->due_date,
                        'badge' => $badge,
                        'type'  => $type,
                    ];
                });

            return response()->json([

                'success' => true,

                'alerts' => $alerts,

            ], 200);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' => 'Error while fetching return date alerts.',

                'error' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile(),

            ], 500);
        }
    }

    /**
     * Fetch recent disputes raised against / by this teacher.
     * Returns latest 3 disputes, newest first.
     */
    public function fetchTeacherRecentDisputes(Request $request)
    {
        try {

            $teacherId = $request->lbteacher_id;

            $disputes = Lbdispute::where('lbteacher_id', $teacherId)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get()
                ->map(function ($dispute) {

                    return [
                        'id'          => $dispute->id,
                        'title'       => $dispute->subject,
                        'description' => $dispute->description,
                        'status'      => $dispute->status,
                        'created_at'  => $dispute->created_at,
                    ];
                });

            return response()->json([

                'success' => true,

                'disputes' => $disputes,

            ], 200);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' => 'Error while fetching teacher recent disputes.',

                'error' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile(),

            ], 500);
        }
    }
}