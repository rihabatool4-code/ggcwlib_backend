<?php

namespace App\Http\Controllers\student\dashboard;

use App\Http\Controllers\Controller;
use App\Models\general\bookings\Lbbooking;
use App\Models\general\dispute\Lbdispute;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StudentDashboardController extends Controller
{
    /**
     * Fetch all dashboard stats for a student:
     */
    public function fetchStudentStatsForDashboard(Request $request)
    {
        $studentId = $request->lbstudent_id;

        $totalIssuedBooks = Lbbooking::where([
            'lbstudent_id' => $studentId,
            'status'       => 'issued',
        ])->count();

        $totalReservedBooks = Lbbooking::where([
            'lbstudent_id' => $studentId,
            'status'       => 'reserved',
        ])->count();

        $totalOverdueBooks = Lbbooking::where([
            'lbstudent_id' => $studentId,
            'status'       => 'issued',
        ])
        ->where('due_date', '<', Carbon::now())
        ->count();

        return response()->json([
            'success'       => true,
            'totalIssued'   => $totalIssuedBooks,
            'totalReserved' => $totalReservedBooks,
            'totalOverdue'  => $totalOverdueBooks,
        ]);
    }

    /**
     * Fetch total outstanding fine for a student + the book it relates to.
     * NOTE: since there's no "is_paid" flag on lbbookings yet, this sums
     * every booking row where fine > 0 for this student. If you later add
     * a paid/unpaid flag, filter by that here so cleared fines drop off.
     */
    public function fetchStudentOutstandingFine(Request $request)
    {
        try {

            $studentId = $request->lbstudent_id;

            $totalFine = Lbbooking::where('lbstudent_id', $studentId)
                ->where('fine', '>', 0)
                ->sum('fine');

            $latestFineBooking = Lbbooking::with('lbbook')
                ->where('lbstudent_id', $studentId)
                ->where('fine', '>', 0)
                ->orderBy('updated_at', 'desc')
                ->first();

            return response()->json([

                'success'   => true,

                'totalFine' => (float) $totalFine,

                'bookTitle' => optional(optional($latestFineBooking)->lbbook)->title,

            ], 200);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' => 'Error while fetching outstanding fine.',

                'error' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile(),

            ], 500);
        }
    }

    /**
     * Fetch return date alerts for currently issued books.
     */
    public function fetchStudentReturnAlerts(Request $request)
    {
        try {

            $studentId = $request->lbstudent_id;
            $today = Carbon::today();

            $alerts = Lbbooking::with('lbbook')
                ->where('lbstudent_id', $studentId)
                ->where('status', 'issued')
                ->orderBy('due_date', 'asc')
                ->limit(5)
                ->get()
                ->map(function ($booking) use ($today) {

                    $dueDate  = Carbon::parse($booking->due_date)->startOfDay();
                    $daysLeft = $today->diffInDays($dueDate, false); // negative = overdue

                    if ($daysLeft < 0) {
                        $type  = 'danger';
                        $badge = abs($daysLeft) . 'd overdue';
                    } elseif ($daysLeft <= 3) {
                        $type  = 'warning';
                        $badge = $daysLeft . 'd left';
                    } else {
                        $type  = 'success';
                        $badge = $daysLeft . 'd left';
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
     * Fetch latest 3 disputes raised by this student, for the dashboard panel.
     */
    public function fetchStudentRecentDisputes(Request $request)
    {
        try {

            $studentId = $request->lbstudent_id;

            $disputes = Lbdispute::where('lbstudent_id', $studentId)
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

                'message' => 'Error while fetching student recent disputes.',

                'error' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile(),

            ], 500);
        }
    }
}