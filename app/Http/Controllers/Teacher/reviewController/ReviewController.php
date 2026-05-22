<?php

namespace App\Http\Controllers\Teacher\reviewController;

use App\Http\Controllers\Controller;
use App\Models\Teacher\Lbreview;
use Illuminate\Http\Request;


class ReviewController extends Controller
{
    public function submitReview(Request $request)
    {
        $review = new Lbreview();
        $review->lbteacher_id = $request->lbteacher_id;
        $review->rating = $request->rating;
        $review->review = $request->review;
        $review->save();

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully'
        ]);
    }
}