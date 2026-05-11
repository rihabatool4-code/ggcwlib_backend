<?php

namespace App\Http\Controllers\admin\bookings;

use App\Http\Controllers\Controller;
use App\Models\general\bookings\lbbooking;
use Illuminate\Http\Request;

class AdminBookingsController extends Controller
{
    public function fetchAllBookings () {
        $bookings = lbbooking::with('lbstudent','lbbook')->get();

        if($bookings){
            return response()->json(["success" => true, "bookings" => $bookings]);
        }
        else{
            return response()->json(["success" => false, "message" => "No bookings yet!"]);
        }
    }
}
