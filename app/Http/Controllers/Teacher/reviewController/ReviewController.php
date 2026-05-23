<?php

namespace App\Http\Controllers\Teacher\reviewController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\general\reviews\LbReview;

class ReviewController extends Controller
{
    public function submitReview(Request $request)
    {
        $review = LbReview::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully'
        ]);
    }
}