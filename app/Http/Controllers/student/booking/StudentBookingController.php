<?php

namespace App\Http\Controllers\student\booking;

use App\Http\Controllers\Controller;
use App\Models\general\bookings\lbbooking;
use App\Models\admin\Lbbook;
use Illuminate\Http\Request;

class StudentBookingController extends Controller
{
    public function newReservation(Request $request)
    {
        // return response()->json(['request' => $request->toArray()]);

        $booking = lbbooking::create($request->all());
        if($booking != null)
        {
            return response()->json(['success' => true, "booking" => $booking]);
        }
        else{
            return response()->json(['success' => false, "message" => "Cannot Reserve book at the moment please try again later"]);
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