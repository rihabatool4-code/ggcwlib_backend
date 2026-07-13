<?php

namespace App\Http\Controllers\student\chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\general\conversation\Lbconversation;
use App\Models\general\chat\Lbchat;
use App\Models\general\dispute\Lbdispute; // adjust this namespace to match wherever your Lbdispute model actually lives
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StudentChatController extends Controller
{

   public function fetchAllChats(Request $request)
  {
    $conversation = Lbconversation::where([ 'lbstudent_id' => $request->lbstudent_id, 'lbdispute_id' => $request->lbdispute_id,
        'type' => $request->type])->first();

    // Agar conversation exist nahi karti
    if (!$conversation) {
        return response()->json(['success' => true,'chats' => [] ]);
    }

    $chats = Lbchat::where( 'lbconversation_id', $conversation->id)->get();

    return response()->json([
        'success' => true,
        'chats' => $chats
    ]);
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

    /**
     * POST /student/smartlib-ai/chat
     * body: { prompt }
     *
     * SmartLib AI assistant — summarizes notes/books, recommends books,
     * and creates flash cards. Talks to OpenAI's chat completions endpoint.
     */
    public function smartLibChat(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:2000',
        ]);

        $systemPrompt = "You are SmartLib AI, a friendly and knowledgeable library assistant for GGCW Library.
You help students with three things only:
1. Summarizing notes and PDF books
2. Recommending books from the library
3. Creating flash cards from summaries

Formatting rules (IMPORTANT - follow exactly):
- Use **text** for bold words or headings
- Start every bullet point on a new line with •
- Keep responses concise, friendly, and well-structured
- Use relevant emojis sparingly where appropriate
- If the student asks something unrelated to books, notes, summaries, recommendations, or flash cards, politely redirect them back to these topics";

        $response = Http::withToken(env('OPENAI_API_KEY'))
            ->timeout(30)
            ->post("https://api.openai.com/v1/chat/completions", [
                "model" => "gpt-5.4-mini",
                "messages" => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $request->prompt],
                ],
                "temperature" => 0.7,
            ]);

        if ($response->failed()) {
            Log::error('SmartLib AI OpenAI error: ' . $response->body());
            return response()->json([
                'success' => false,
                'message' => 'SmartLib AI is currently unavailable. Please try again later.',
            ], 500);
        }

        $data = $response->json();
        $reply = $data['choices'][0]['message']['content'] ?? 'Sorry, I could not generate a response.';

        return response()->json([
            'success' => true,
            'reply' => $reply,
        ]);
    }
}