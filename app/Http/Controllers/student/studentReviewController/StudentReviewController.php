<?php

namespace App\Http\Controllers\student\studentReviewController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\general\reviews\LbReview;

class StudentReviewController extends Controller
{
    public function submitReview(Request $request)
{
    $data = $request->all();
    $data['status'] = 'Activate';

    $review = LbReview::create($data);

    return response()->json([
        'success' => true,
        'message' => 'Review submitted successfully',
        'review' => $review
    ]);
}
   public function loadAllReviews(Request $request)
{
    $reviews = LbReview::where(
        'lbstudent_id',
        $request->lbstudent_id
    )
    ->orderBy('id', 'desc')
    ->get();

    return response()->json([
        'success' => true,
        'reviews' => $reviews
    ]);
}
}