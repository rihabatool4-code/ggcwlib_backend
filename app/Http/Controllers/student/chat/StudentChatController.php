<?php

namespace App\Http\Controllers\student\chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\general\conversation\Lbconversation;
use App\Models\general\chat\Lbchat;
use App\Models\general\dispute\Lbdispute; // adjust this namespace to match wherever your Lbdispute model actually lives
use App\Models\admin\Lbbook;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Parser as PdfParser;

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
     * Builds a plain-text catalog listing from the real GGCW Library books
     * table, so SmartLib AI only ever recommends books that actually exist.
     * Capped at 200 books to keep the prompt from getting too large.
     */
    private function libraryBooksContext()
    {
        $books = Lbbook::select('id', 'title', 'author', 'dept', 'total_copies')
            ->take(200)
            ->get();

        if ($books->isEmpty()) {
            return "(No books currently found in the catalog.)";
        }

        return $books->map(function ($book) {
            $copies = $book->available_copies;
            $status = $copies > 0 ? "{$copies} copies available" : "currently unavailable";
            return "- \"{$book->title}\" by {$book->author} (Dept: {$book->dept}) — {$status}";
        })->implode("\n");
    }

    /**
     * Shared system prompt so text chat and file-summary calls stay consistent.
     * Now includes the real library catalog so recommendations are grounded
     * in actual GGCW Library inventory instead of the model guessing titles.
     */
    private function smartLibSystemPrompt()
    {
        $booksList = $this->libraryBooksContext();

        return "You are SmartLib AI, a friendly and knowledgeable library assistant for GGCW Library.
You help students with three things only:
1. Summarizing notes and PDF books
2. Recommending books from the library
3. Creating flash cards from summaries

When recommending books, ONLY recommend titles from the catalog below — this is the real, current GGCW Library inventory. Never invent a book or author that isn't in this list. If nothing in the catalog fits what the student is asking for, say so honestly instead of making something up. Mention availability (copies available) when relevant.

GGCW Library Catalog:
{$booksList}

Formatting rules (IMPORTANT - follow exactly):
- Use **text** for bold words or headings
- Start every bullet point on a new line with •
- Keep responses concise, friendly, and well-structured
- Use relevant emojis sparingly where appropriate
- If the student asks something unrelated to books, notes, summaries, recommendations, or flash cards, politely redirect them back to these topics";
    }

    /**
     * POST /student/smartlib-ai/chat
     * body: { messages: [{role, content}, ...] }
     *
     * SmartLib AI assistant with conversation memory — sends the recent
     * chat history so it remembers context across turns.
     */
    public function smartLibChat(Request $request)
    {
        $request->validate([
            'messages' => 'required|array|min:1',
            'messages.*.role' => 'required|in:user,assistant',
            'messages.*.content' => 'required|string|max:2000',
        ]);

        // Keep only the last 12 messages (6 exchanges) so token usage stays sane
        $history = array_slice($request->input('messages'), -12);

        $chatMessages = array_merge(
            [['role' => 'system', 'content' => $this->smartLibSystemPrompt()]],
            $history
        );

        $response = Http::withToken(env('OPENAI_API_KEY'))
            ->timeout(30)
            ->post("https://api.openai.com/v1/chat/completions", [
                "model" => "gpt-5.4-mini",
                "messages" => $chatMessages,
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

    /**
     * POST /student/smartlib-ai/flashcard-points
     * body: { content }
     *
     * Condenses an AI chat reply into short, flash-card style bullet points
     * (separate lightweight AI call — keeps flash cards clean instead of
     * dumping the whole chat reply into the card).
     */
    public function generateFlashcardPoints(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:4000',
        ]);

        $response = Http::withToken(env('OPENAI_API_KEY'))
            ->timeout(30)
            ->post("https://api.openai.com/v1/chat/completions", [
                "model" => "gpt-5.4-mini",
                "messages" => [
                    [
                        'role' => 'system',
                        'content' => 'You condense study material into flash-card style bullet points. '
                            . 'Rules: return ONLY 4 to 6 short bullet points, each starting with "• ", '
                            . 'each under 15 words, capturing only the most important facts. '
                            . 'No headings, no intro text, no bold markers, no extra commentary.'
                    ],
                    ['role' => 'user', 'content' => $request->input('content')],
                ],
                "temperature" => 0.4,
            ]);

        if ($response->failed()) {
            Log::error('SmartLib AI flashcard error: ' . $response->body());
            return response()->json([
                'success' => false,
                'message' => 'Could not generate flash card points.',
            ], 500);
        }

        $data = $response->json();
        $points = $data['choices'][0]['message']['content'] ?? '';

        return response()->json([
            'success' => true,
            'points' => $points,
        ]);
    }

    /**
     * POST /student/smartlib-ai/upload-file
     * multipart form-data: { file, prompt? }
     *
     * Accepts a PDF or TXT file, extracts its text, then asks
     * SmartLib AI to summarize/work with that content.
     */
    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,txt|max:10240', // 10 MB
            'prompt' => 'nullable|string|max:500',
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());
        $extractedText = '';

        try {
            if ($extension === 'pdf') {
                $parser = new PdfParser();
                $pdf = $parser->parseFile($file->getRealPath());
                $extractedText = $pdf->getText();
            } else {
                $extractedText = file_get_contents($file->getRealPath());
            }
        } catch (\Exception $e) {
            Log::error('SmartLib AI file parse error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Could not read this file. Please try another one.',
            ], 500);
        }

        // Trim so we don't blow past the model's context limits
        $extractedText = mb_substr(trim($extractedText), 0, 12000);

        if ($extractedText === '') {
            return response()->json([
                'success' => false,
                'message' => 'This file appears to be empty or unreadable (maybe a scanned PDF?).',
            ], 422);
        }

        $userPrompt = $request->input('prompt') ?: 'Summarize this document';

        $response = Http::withToken(env('OPENAI_API_KEY'))
            ->timeout(60)
            ->post("https://api.openai.com/v1/chat/completions", [
                "model" => "gpt-5.4-mini",
                "messages" => [
                    ['role' => 'system', 'content' => $this->smartLibSystemPrompt()],
                    ['role' => 'user', 'content' => $userPrompt . ":\n\n" . $extractedText],
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