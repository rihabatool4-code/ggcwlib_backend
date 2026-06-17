<?php

namespace App\Http\Controllers\Admin\chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\general\conversation\Lbconversation;
use App\Models\general\chat\Lbchat;
use App\Models\general\dispute\Lbdispute; //  adjust namespace if needed

class AdminChatController extends Controller
{
    /**
     * GET /admin/disputes/{dispute}/chats
     *
     * No student_id/teacher_id check here — admin is a privileged role and
     * should be able to open the chat for ANY dispute, whether it was raised
     * by a student or a teacher. We still confirm the dispute itself exists.
     */
    public function index($disputeId)
    {
        $dispute = Lbdispute::find($disputeId);

        if (!$dispute) {
            return response()->json(['message' => 'Dispute not found'], 404);
        }

        // raisedby tells us whether this was a student or teacher dispute,
        // so we store the correct id on the conversation when it's first created.
        $conversation = Lbconversation::firstOrCreate(
            ['lbdispute_id' => $disputeId],
            [
                'lbstudent_id' => $dispute->raisedby === 'student' ? $dispute->lbstudent_id : null,
                'lbteacher_id' => $dispute->raisedby === 'teacher' ? $dispute->lbteacher_id : null,
                'type' => 'dispute',
            ]
        );

        $chats = $conversation->chats()->orderBy('created_at', 'asc')->get();

        return response()->json([
            'conversation_id' => $conversation->id,
            'dispute' => $dispute,
            'chats' => $chats,
        ]);
    }

    /**
     * POST /admin/disputes/{dispute}/chats
     * body: { message }
     *
     * Admin replies to a dispute. No admin_id required for this version —
     * add one later (e.g. from auth()->id()) if you want to track which
     * staff member replied.
     */
    public function store(Request $request, $disputeId)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $dispute = Lbdispute::find($disputeId);
        if (!$dispute) {
            return response()->json(['message' => 'Dispute not found'], 404);
        }

        $conversation = Lbconversation::firstOrCreate(
            ['lbdispute_id' => $disputeId],
            ['type' => 'dispute']
        );

        $chat = Lbchat::create([
            'lbconversation_id' => $conversation->id,
            'message' => $request->message,
            'type'    => 'text',
            'sender'  => 'admin',
        ]);

        return response()->json($chat, 201);
    }
}