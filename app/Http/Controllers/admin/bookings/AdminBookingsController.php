<?php

namespace App\Http\Controllers\admin\bookings;

use App\Http\Controllers\Controller;
use App\Models\general\bookings\lbbooking;
use Illuminate\Http\Request;

class AdminBookingsController extends Controller
{
    public function fetchAllBookings (Request $request) {
        $bookings = lbbooking::with('lbstudent','lbbook')->where(['status' => $request->status])->get();

        if($bookings){
            return response()->json(["success" => true, "bookings" => $bookings]);
        }
        else{
            return response()->json(["success" => false, "message" => "No bookings yet!"]);
        }
    }
}
