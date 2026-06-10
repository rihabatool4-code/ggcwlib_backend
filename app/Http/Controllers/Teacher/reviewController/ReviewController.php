<?php
namespace App\Http\Controllers\Teacher\reviewController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\general\reviews\LbReview;
use App\Models\teacher\Lbteacher;
use App\Models\student\Lbstudent;
use Illuminate\Support\Facades\Log;

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
    $reviews = LbReview::get();

    foreach ($reviews as $review) {

        if ($review->lbteacher_id) {

            $teacher = Lbteacher::where('id', $review->lbteacher_id)->first();

            $review->reviewer_name = $teacher ? $teacher->name : "Unknown Teacher";
            $review->reviewer_role = "Teacher";

        }
        elseif ($review->lbstudent_id) {

            $student = Lbstudent::where('id', $review->lbstudent_id)->first();

            $review->reviewer_name = $student ? $student->fullName : "Unknown Student";
            $review->reviewer_role = "Student";

        }
        else {

            $review->reviewer_name = "Unknown User";
            $review->reviewer_role = "Unknown";

        }
    }
Log::info($reviews->toArray());
    return response()->json([
        'success' => true,
        'reviews' => $reviews
    ]);
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