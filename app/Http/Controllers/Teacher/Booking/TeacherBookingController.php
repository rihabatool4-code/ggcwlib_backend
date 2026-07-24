<?php

namespace App\Http\Controllers\Teacher\Booking;

use App\Http\Controllers\Controller;
use App\Models\general\bookings\lbbooking;
use App\Models\admin\Lbbook;
use App\Models\teacher\Lbteacher;
use App\Http\Controllers\Teacher\notifications\TeacherNotificationController;
use App\Http\Controllers\admin\notificaion\AdminNotificationController;
use App\Models\admin\bookConfig\LbBookconfig;
use App\Services\ReservationExpiryService;
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

            if ($book->available_copies <= 0) {
                return response()->json([
                    "success" => false,
                    "message" => "No copies of this book are available right now."
                ]);
            }

            $teacherId = $request->lbteacher_id;

            // ── Same book already reserved/issued (not yet returned) ──
            $alreadyHasThisBook = lbbooking::where('lbteacher_id', $teacherId)
                ->where('lbbook_id', $request->lbbook_id)
                ->whereIn('status', ['reserved', 'issued'])
                ->exists();

            if ($alreadyHasThisBook) {
                return response()->json([
                    "success" => false,
                    "message" => "You have already reserved this book. Please return it before reserving it again."
                ]);
            }

            // ── Book config se max allowed books nikalein ──
$bookConfig = LbBookconfig::first();
$maxBooksAllowed = $bookConfig->max_books_staff ?? 7;   // agar config na mile to 7 default

// ── Max active reservations for a teacher (ab dynamic) ──
$activeCount = lbbooking::where('lbteacher_id', $teacherId)
    ->whereIn('status', ['reserved', 'issued'])
    ->count();

if ($activeCount >= $maxBooksAllowed) {
    return response()->json([
        "success" => false,
        "message" => "You have reached the maximum limit of {$maxBooksAllowed} active reservations. Please return a book before reserving another."
    ]);
}

            $booking = lbbooking::create($request->all());

            if ($booking != null) {
                $teacher = Lbteacher::find($booking->lbteacher_id);

                TeacherNotificationController::notifyTeacher(
                    $booking->lbteacher_id,
                    'Reservation Placed',
                    "Your reservation for \"{$book->title}\" has been placed successfully.",
                    'book'
                );

                AdminNotificationController::notifyAllAdmins(
                    'New Reservation',
                    ($teacher->name ?? 'A teacher') . " reserved \"{$book->title}\".",
                    'book'
                );

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

            ReservationExpiryService::expireOldReservations();

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