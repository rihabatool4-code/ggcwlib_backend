<?php

namespace App\Http\Controllers\general\flashcard;

use App\Http\Controllers\Controller;
use App\Models\general\flashcard\Lbflashcard;
use Illuminate\Http\Request;

class TeacherFlashcardController extends Controller
{
     public function fetchAllFlashCards(Request $request)
    {
        $teacherId = $request->lbteacher_id;
 
        $flashCards = Lbflashcard::where('lbteacher_id', $teacherId)
            ->orderBy('created_at', 'desc')
            ->get([
                'id',
                'title',
                'subtitle',
                'type',
                'descryption',
                'created_at',
            ]);
 
        return response()->json([
            'success'    => true,
            'flashCards' => $flashCards,
        ]);
    }
 
    public function deleteFlashCard(Request $request)
    {
        $card = Lbflashcard::where('id', $request->id)
            ->where('lbteacher_id', $request->lbteacher_id)
            ->first();
 
        if (!$card) {
            return response()->json([
                'success' => false,
                'message' => 'Flash card not found.',
            ], 404);
        }
 
        $card->delete();
 
        return response()->json([
            'success' => true,
            'message' => 'Flash card deleted successfully.',
        ]);
    }
}
