<?php

namespace App\Http\Controllers\student\booking;

use App\Http\Controllers\Controller;
use App\Models\general\bookings\lbbooking;
use App\Models\admin\Lbbook;
use App\Http\Controllers\student\notification\StudentNotificationController;
use App\Http\Controllers\admin\notificaion\AdminNotificationController;
use Illuminate\Http\Request;

class StudentBookingController extends Controller
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

            $booking = lbbooking::create($request->all());

            if ($booking != null) {
                $student = auth('Lbstudent')->user();

                StudentNotificationController::notifyStudent(
                    $booking->lbstudent_id,
                    'Reservation Placed',
                    "Your reservation for \"{$book->title}\" has been placed successfully.",
                    'book'
                );

                AdminNotificationController::notifyAllAdmins(
                    'New Reservation',
                    ($student->fullName ?? 'A student') . " reserved \"{$book->title}\".",
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
            $student = auth('Lbstudent')->user();

            $bookings = lbbooking::with('lbbook')
                ->where('lbstudent_id', $student->id)
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

    public function fetchAllBooks()
    {
        try {
            $books = Lbbook::latest()->get();

            return response()->json([
                "success" => true,
                "books"   => $books
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Failed to fetch books.",
                "error"   => $e->getMessage()
            ], 500);
        }
    }
}