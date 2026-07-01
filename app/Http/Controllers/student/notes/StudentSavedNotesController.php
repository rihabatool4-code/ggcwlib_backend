<?php

namespace App\Http\Controllers\student\notes;

use App\Http\Controllers\Controller;
use App\Models\general\Lbfavouritebook;
use App\Models\note\Lbnote;
use Illuminate\Http\Request;

class StudentSavedNotesController extends Controller
{
    // ── Save a note (or an ebook) ──
    public function saveNote(Request $request)
    {
        try {
            // Already saved check
            $exists = Lbfavouritebook::where('lbstudent_id', $request->student_id)
                                      ->where('lbnote_id', $request->note_id)
                                      ->first();
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Note already saved'
                ]);
            }

            // create 
               $saved = Lbfavouritebook::create($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Note saved successfully',
                'saved'   => $saved
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    // ── Get all saved notes of a student ──
    public function getSavedNotes($student_id)
    {
        try {
            $saved = Lbfavouritebook::where('lbstudent_id', $student_id)
                                     ->whereNotNull('lbnote_id')
                                     ->with('note')
                                     ->latest()
                                     ->get();

            return response()->json([
                'success' => true,
                'notes'   => $saved
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    // ── Get all saved ebooks of a student ──
    // Same table (Lbfavouritebook), just filtered on lbebook_id instead of lbnote_id.
    public function getSavedEbooks($student_id)
    {
        try {
            $saved = Lbfavouritebook::where('lbstudent_id', $student_id)
                                     ->whereNotNull('lbebook_id')
                                     ->with('ebook')
                                     ->latest()
                                     ->get();

            return response()->json([
                'success' => true,
                'ebooks'  => $saved
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    // ── Remove saved note or ebook (same table, same delete logic) ──
    public function removeSavedNote($id)
    {
        try {
            $saved = Lbfavouritebook::find($id);
            if ($saved) {
                $saved->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Removed from saved notes'
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Not found'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}