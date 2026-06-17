<?php

namespace App\Http\Controllers\student\chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\general\conversation\Lbconversation;
use App\Models\general\chat\Lbchat;
use App\Models\general\dispute\Lbdispute; // adjust this namespace to match wherever your Lbdispute model actually lives

class StudentChatController extends Controller
{
    /**
     * GET /student/disputes/{dispute}/chats?student_id=123
     *
     * Returns the full chat history for one dispute.
     *
     * WHY the student_id check matters:
     * Without it, a student could change the dispute id in the URL/network tab
     * and read someone else's dispute chat. So before we return anything,
     * we confirm this dispute's lbstudent_id actually matches the student
     * making the request.
     */
    public function index(Request $request, $disputeId)
    {
        $studentId = $request->query('student_id');

        if (!$studentId) {
            return response()->json(['message' => 'student_id is required'], 422);
        }

        $dispute = Lbdispute::where('id', $disputeId)
            ->where('lbstudent_id', $studentId)
            ->first();

        if (!$dispute) {
            return response()->json(['message' => 'Dispute not found or access denied'], 404);
        }

        // firstOrCreate = "find this conversation, or make it if it doesn't exist yet"
        // in one atomic call. This means the very first time a student opens
        // the chat modal, a conversation row gets created automatically —
        // you never have to create it manually when the dispute is first raised.
        $conversation = Lbconversation::firstOrCreate(
            ['lbdispute_id' => $disputeId],
            [
                'lbstudent_id' => $studentId,
                'type' => 'dispute',
            ]
        );

        // orderBy created_at asc = oldest message first, like a normal chat thread
        $chats = $conversation->chats()->orderBy('created_at', 'asc')->get();

        return response()->json([
            'conversation_id' => $conversation->id,
            'chats' => $chats,
        ]);
    }

    /**
     * POST /student/disputes/{dispute}/chats
     * body: { student_id, message }
     *
     * Student sends a new message into their own dispute's conversation.
     */
    public function store(Request $request, $disputeId)
    {
        $request->validate([
            'student_id' => 'required|integer',
            'message'    => 'required|string|max:2000',
        ]);

        // Same ownership check as index() — never trust the frontend alone.
        $dispute = Lbdispute::where('id', $disputeId)
            ->where('lbstudent_id', $request->student_id)
            ->first();

        if (!$dispute) {
            return response()->json(['message' => 'Dispute not found or access denied'], 404);
        }

        $conversation = Lbconversation::firstOrCreate(
            ['lbdispute_id' => $disputeId],
            [
                'lbstudent_id' => $request->student_id,
                'type' => 'dispute',
            ]
        );

        $chat = Lbchat::create([
            'lbconversation_id' => $conversation->id,
            'message' => $request->message,
            'type'    => 'text',
            'sender'  => 'student', // hardcoded — never trust a sender value sent from frontend
        ]);

        return response()->json($chat, 201);
    }
}