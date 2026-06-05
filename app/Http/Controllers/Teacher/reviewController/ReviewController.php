<?php
namespace App\Http\Controllers\Teacher\reviewController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\general\reviews\LbReview;

class ReviewController extends Controller
{
    // Submit review
    public function submitReview(Request $request)
    {
        $review = LbReview::create([
            'lbteacher_id' => $request->lbteacher_id,
            'rating'       => $request->rating,
            'review'       => $request->review,
            'status'       => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully',
            'review'  => $review,
        ]);
    }

    // Load all reviews
    public function loadAllReviews(Request $request)
{
    $reviews = LbReview::where('lbteacher_id', $request->lbteacher_id)->get();
    return response()->json(['success' => true, 'reviews' => $reviews]);
}

    // Approve
    public function approveReview(Request $request)
    {
        LbReview::where('id', $request->review_id)->update(['status' => 'approved']);
        return response()->json(['success' => true]);
    }

    // Reject
    public function rejectReview(Request $request)
    {
        LbReview::where('id', $request->review_id)->update(['status' => 'rejected']);
        return response()->json(['success' => true]);
    }

    // Delete
    public function deleteReview(Request $request)
    {
        LbReview::where('id', $request->review_id)->delete();
        return response()->json(['success' => true]);
    }
}