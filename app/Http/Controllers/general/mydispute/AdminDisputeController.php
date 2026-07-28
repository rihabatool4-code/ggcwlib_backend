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
        // ✅ FIX: Sort disputes so the one with the most recent chat activity
        // (from either student or teacher side) shows up on top, not just the
        // most recently CREATED dispute. Falls back to the dispute's own
        // created_at when it has no messages yet.
        $disputes = Lbdispute::with(['lbteacher', 'lbstudent', 'lbbook'])
            ->select('lbdisputes.*')
            ->selectSub(function ($query) {
                $query->from('lbchats')
                    ->join('lbconversations', 'lbchats.lbconversation_id', '=', 'lbconversations.id')
                    ->whereColumn('lbconversations.lbdispute_id', 'lbdisputes.id')
                    ->where('lbconversations.type', 'dispute')
                    ->selectRaw('MAX(lbchats.created_at)');
            }, 'last_message_at')
            ->orderByRaw('COALESCE(last_message_at, lbdisputes.created_at) DESC')
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