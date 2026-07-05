<?php

namespace App\Http\Controllers\general\mydispute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\general\dispute\Lbdispute;
use App\Http\Controllers\student\notification\StudentNotificationController;
use App\Http\Controllers\Teacher\notifications\TeacherNotificationController;
use Carbon\Carbon;

class AdminDisputeController extends Controller
{
    // =========================
    // FETCH ALL DISPUTES
    // =========================
    public function fetchAllDisputes()
    {
        $disputes = Lbdispute::with(['lbteacher', 'lbstudent', 'lbbook'])
                             ->orderBy('created_at', 'desc')
                             ->get();

        return response()->json(['success' => true, 'disputes' => $disputes]);
    }

    // =========================
    // RESOLVE DISPUTE
    // =========================
    public function resolve(Request $request)
    {
        $dispute = Lbdispute::with(['lbteacher', 'lbstudent', 'lbbook'])->where('id', $request->id)->first();

        if (!$dispute) {
            return response()->json([
                'success' => false,
                'message' => 'Dispute not found'
            ], 404);
        }

        $dispute->update([
            'status' => 'resolved'
        ]);

        // ── Notify the person who raised the dispute ──
        $resolvedOn = Carbon::now()->toDateString();

        if ($dispute->lbstudent_id) {
            StudentNotificationController::notifyStudent(
                $dispute->lbstudent_id,
                'Dispute Resolved',
                "Your dispute \"{$dispute->subject}\" has been resolved.",
                'dispute',
                [
                    'disputeId'  => $dispute->id,
                    'resolvedOn' => $resolvedOn,
                    'resolvedBy' => 'Admin',
                    'subject'    => $dispute->subject,
                    'resolution' => $dispute->description,
                    'action'     => 'This dispute has been marked as resolved.',
                ]
            );
        } elseif ($dispute->lbteacher_id) {
            TeacherNotificationController::notifyTeacher(
                $dispute->lbteacher_id,
                'Dispute Resolved',
                "Your dispute \"{$dispute->subject}\" has been resolved.",
                'dispute'
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Dispute resolved successfully',
            'data' => $dispute
        ]);
    }

    // =========================
    // DELETE DISPUTE
    // =========================
    public function delete(Request $request)
    {
        $dispute = Lbdispute::where('id', $request->id)->first();

        if (!$dispute) {
            return response()->json([
                'success' => false,
                'message' => 'Dispute not found'
            ], 404);
        }

        $dispute->delete();

        return response()->json([
            'success' => true,
            'message' => 'Dispute Deleted Successfully'
        ]);
    }
}