<?php

namespace App\Http\Controllers\Admin\chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\general\conversation\Lbconversation;
use App\Models\general\chat\Lbchat;
use App\Models\general\dispute\Lbdispute;

class AdminChatController extends Controller
{
    // =========================
    // FETCH ALL CHATS
    // =========================
    public function fetchAllChats(Request $request)
    {
        $dispute = Lbdispute::where('id', $request->lbdispute_id)->first();

        if (!$dispute) {
            return response()->json([
                'success' => false,
                'message' => 'Dispute not found'
            ], 404);
        }

        $conversation = Lbconversation::firstOrCreate([
            'lbdispute_id' => $request->lbdispute_id,
            'type' => 'dispute'
        ]);

        $chats = Lbchat::where('lbconversation_id', $conversation->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'lbconversation_id' => $conversation->id,
            'dispute' => $dispute,
            'chats' => $chats
        ]);
    }

    // =========================
    // STORE CHAT MESSAGE
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'lbdispute_id' => 'required|integer',
            'message' => 'required|string|max:2000',
        ]);

        $dispute = Lbdispute::where('id', $request->lbdispute_id)->first();

        if (!$dispute) {
            return response()->json([
                'success' => false,
                'message' => 'Dispute not found'
            ], 404);
        }

        $conversation = Lbconversation::firstOrCreate([
            'lbdispute_id' => $request->lbdispute_id,
            'type' => 'dispute'
        ]);

        $chat = Lbchat::create([
            'lbconversation_id' => $conversation->id,
            'message' => $request->message,
            'type' => 'text',
            'sender' => 'admin',
        ]);

        return response()->json([
            'success' => true,
            'chat' => $chat
        ], 201);
    }
}