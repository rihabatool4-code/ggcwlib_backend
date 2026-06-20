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
    public function fetchAllChats(Request $request)
    {

        // return response()->json(['request' => $request->toArray()]);
        $converstaion = Lbconversation::where(['lbteacher_id' => $request->lbteacher_id, 'lbdispute_id' => $request->lbdispute_id, 'type' => $request->type])->first();

        $chats = Lbchat::where(['lbconversation_id' => $converstaion->id])->get(); 

        return response()->json(['success' => true, 'chats' => $chats]);

    }

    /**
     * POST /teacher/disputes/{dispute}/chats
     * body: { teacher_id, message }
     */
  public function store(Request $request)
{
    $request->validate([
        'lbteacher_id' => 'required|integer',
        'lbdispute_id' => 'required|integer',
        'message' => 'required|string|max:2000',
        'type' => 'required'
    ]);

    $dispute = Lbdispute::where([
        'id' => $request->lbdispute_id,
        'lbteacher_id' => $request->lbteacher_id
    ])->first();

    if (!$dispute) {
        return response()->json([
            'success' => false,
            'message' => 'Dispute not found or access denied'
        ], 404);
    }

    $conversation = Lbconversation::firstOrCreate(
        [
            'lbteacher_id' => $request->lbteacher_id,
            'lbdispute_id' => $request->lbdispute_id,
            'type' => $request->type
        ]
    );

    $chat = Lbchat::create([
        'lbconversation_id' => $conversation->id,
        'message' => $request->message,
        'type' => 'text',
        'sender' => 'teacher'
    ]);

    return response()->json([
        'success' => true,
        'chat' => $chat
    ]);
}
}