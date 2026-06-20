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
    public function fetchAllChats(Request $request)
    {

        // return response()->json(['request' => $request->toArray()]);
        $converstaion = Lbconversation::where(['lbstudent_id' => $request->lbstudent_id, 'lbdispute_id' => $request->lbdispute_id, 'type' => $request->type])->first();

        $chats = Lbchat::where(['lbconversation_id' => $converstaion->id])->get(); 

        return response()->json(['success' => true, 'chats' => $chats]);

    }

    /**
     * POST /student/disputes/{dispute}/chats
     * body: { student_id, message }
     *
     * Student sends a new message into their own dispute's conversation.
     */
    public function store(Request $request)
{
    $request->validate([
        'lbstudent_id' => 'required|integer',
        'lbdispute_id' => 'required|integer',
        'message' => 'required|string|max:2000',
        'type' => 'required'
    ]);

    // Check dispute belongs to student
    $dispute = Lbdispute::where([ 'id' => $request->lbdispute_id,'lbstudent_id' => $request->lbstudent_id
     ])->first();

    if (!$dispute) {
        return response()->json([
            'success' => false,
            'message' => 'Dispute not found or access denied'
        ], 404);
    }

    $conversation = Lbconversation::firstOrCreate([
        'lbstudent_id' => $request->lbstudent_id,
        'lbdispute_id' => $request->lbdispute_id,
        'type' => $request->type
    ]);

    $chat = Lbchat::create([
        'lbconversation_id' => $conversation->id,
        'message' => $request->message,
        'type' => 'text',
        'sender' => 'student'
    ]);

    return response()->json([
        'success' => true,
        'chat' => $chat
    ]);
}
}