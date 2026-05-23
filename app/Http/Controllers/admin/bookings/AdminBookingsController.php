<?php

namespace App\Http\Controllers\admin\bookings;

use App\Http\Controllers\Controller;
use App\Models\general\bookings\lbbooking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminBookingsController extends Controller
{
    /* ── Fetch All Bookings by status ── */
    public function fetchAllBookings(Request $request)
    {
        $bookings = lbbooking::with('lbstudent','lbteacher', 'lbbook')
            ->where(['status' => $request->status])
            ->get();

        if ($bookings) {
            return response()->json(["success" => true, "bookings" => $bookings]);
        } else {
            return response()->json(["success" => false, "message" => "No bookings yet!"]);
        }
    }

    /* ── Approve Reservation → status = issued ── */
    public function approveReservation(Request $request)
    {
        try {
            $booking = lbbooking::where('id', $request->booking_id)->first();
            if (!$booking) {
                return response()->json(["success" => false, "message" => "Booking not found."]);
            }

            $today   = Carbon::now()->toDateString();
            $dueDate = Carbon::now()->addDays(14)->toDateString();

            $booking->status     = "issued";
            $booking->issue_date = $today;
            $booking->due_date   = $dueDate;
            $booking->save();

            return response()->json([
                "success" => true,
                "message" => "Reservation approved and book issued successfully.",
                "booking" => $booking
            ]);

        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }

    /* ── Reject Reservation → delete record ── */
    public function rejectReservation(Request $request)
    {
        try {
            $booking = lbbooking::where('id', $request->booking_id)->first();

            if (!$booking) {
                return response()->json(["success" => false, "message" => "Booking not found."]);
            }

            $booking->delete();

            return response()->json([
                "success" => true,
                "message" => "Reservation rejected and deleted successfully."
            ]);

        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }

    /* ════════════════════════════════════════════
       Return Book — auto fine calculation
       Fine: Rs. 10 per day after due_date
    ════════════════════════════════════════════ */
    public function returnBook(Request $request)
    {
        try {
            $booking = lbbooking::where('id', $request->booking_id)->first();

            if (!$booking) {
                return response()->json(["success" => false, "message" => "Booking not found."]);
            }

            $today   = Carbon::now()->startOfDay();
            $dueDate = Carbon::parse($booking->due_date)->startOfDay();

            // ── Calculate fine ──
            $fine = 0;
            if ($today->gt($dueDate)) {
                $overdueDays = $today->diffInDays($dueDate); // days after due date
                $fine        = $overdueDays * 10;             // Rs. 10 per day
            }

            // ── Update booking ──
            $booking->status       = "returned";
            $booking->fine         = $fine > 0 ? $fine : null;
            $booking->save();

            return response()->json([
                "success"      => true,
                "message"      => "Book returned successfully.",
                "fine"         => $fine,
                "overdue_days" => $fine > 0 ? ($fine / 10) : 0,
                "booking"      => $booking
            ]);

        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }
}