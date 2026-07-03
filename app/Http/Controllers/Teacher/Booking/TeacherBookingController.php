<?php

namespace App\Http\Controllers\Teacher\Booking;

use App\Http\Controllers\Controller;
use App\Models\general\bookings\lbbooking;
use App\Models\admin\Lbbook;
use Illuminate\Http\Request;

class TeacherBookingController extends Controller
{
    public function newReservation(Request $request)
    {
        try {
            $book = Lbbook::find($request->lbbook_id);

            if (!$book) {
                return response()->json([
                    "success" => false,
                    "message" => "Book not found."
                ]);
            }

            // Same availability guard as the student flow.
            if ($book->available_copies <= 0) {
                return response()->json([
                    "success" => false,
                    "message" => "No copies of this book are available right now."
                ]);
            }

            $booking = lbbooking::create($request->all());

            if ($booking != null) {
                return response()->json(['success' => true, "booking" => $booking]);
            } else {
                return response()->json(['success' => false, "message" => "Cannot Reserve book at the moment please try again later"]);
            }

        } catch (\Exception $e) {
            return response()->json(['success' => false, "message" => $e->getMessage()]);
        }
    }

    public function loadMyBookings(Request $request)
    {
        try {
            $teacher_id = $request->teacher_id;

            $bookings = lbbooking::with('lbbook')
                ->where('lbteacher_id', $teacher_id)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success'  => true,
                'bookings' => $bookings
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}