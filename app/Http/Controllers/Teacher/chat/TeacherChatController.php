<?php

namespace App\Http\Controllers\Teacher\chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\general\conversation\Lbconversation;
use App\Models\general\chat\Lbchat;
use App\Models\general\dispute\Lbdispute; //  adjust namespace if your model lives elsewhere

class TeacherChatController extends Controller
{
    /**
     * GET /teacher/disputes/{dispute}/chats?teacher_id=123
     *
     * Same logic as the student version, but the ownership check is against
     * lbteacher_id instead of lbstudent_id — a teacher can only open chats
     * for disputes THEY raised.
     */
    public function index(Request $request, $disputeId)
    {
        $teacherId = $request->query('teacher_id');

        if (!$teacherId) {
            return response()->json(['message' => 'teacher_id is required'], 422);
        }

        $dispute = Lbdispute::where('id', $disputeId)
            ->where('lbteacher_id', $teacherId)
            ->first();

        if (!$dispute) {
            return response()->json(['message' => 'Dispute not found or access denied'], 404);
        }

        $conversation = Lbconversation::firstOrCreate(
            ['lbdispute_id' => $disputeId],
            [
                'lbteacher_id' => $teacherId,
                'type' => 'dispute',
            ]
        );

        $chats = $conversation->chats()->orderBy('created_at', 'asc')->get();

        return response()->json([
            'conversation_id' => $conversation->id,
            'chats' => $chats,
        ]);
    }

    /**
     * POST /teacher/disputes/{dispute}/chats
     * body: { teacher_id, message }
     */
    public function store(Request $request, $disputeId)
    {
        $request->validate([
            'teacher_id' => 'required|integer',
            'message'    => 'required|string|max:2000',
        ]);

        $dispute = Lbdispute::where('id', $disputeId)
            ->where('lbteacher_id', $request->teacher_id)
            ->first();

        if (!$dispute) {
            return response()->json(['message' => 'Dispute not found or access denied'], 404);
        }

        $conversation = Lbconversation::firstOrCreate(
            ['lbdispute_id' => $disputeId],
            [
                'lbteacher_id' => $request->teacher_id,
                'type' => 'dispute',
            ]
        );

        $chat = Lbchat::create([
            'lbconversation_id' => $conversation->id,
            'message' => $request->message,
            'type'    => 'text',
            'sender'  => 'teacher',
        ]);

        return response()->json($chat, 201);
    }
}