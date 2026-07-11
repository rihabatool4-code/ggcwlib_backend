<?php

namespace App\Http\Controllers\student\notes;

use App\Http\Controllers\Controller;
use App\Models\general\Lbfavouritebook;
use App\Models\note\Lbnote;
use Illuminate\Http\Request;

class StudentSavedNotesController extends Controller
{
    // ── Save a note ──
    public function saveNote(Request $request)
    {
        try {
            // Already saved check (FIXED: was checking $request->student_id / $request->note_id
            // which don't exist in the payload — always matched null==null rows and falsely
            // reported "already saved". Now checks the actual keys sent: lbstudent_id / lbnote_id)
            $exists = Lbfavouritebook::where('lbstudent_id', $request->lbstudent_id)
                                      ->where('lbnote_id', $request->lbnote_id)
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

    // ── Save an ebook (Save for Later) ──
    public function saveEbook(Request $request)
    {
        try {
            $exists = Lbfavouritebook::where('lbstudent_id', $request->lbstudent_id)
                                      ->where('lbebook_id', $request->lbebook_id)
                                      ->first();
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ebook already saved'
                ]);
            }

            $saved = Lbfavouritebook::create($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Ebook saved successfully',
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

 
    // ── Remove saved note ──
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

    // ── Remove saved ebook ──
    public function removeSavedEbook($id)
    {
        try {
            $saved = Lbfavouritebook::find($id);
            if ($saved) {
                $saved->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Removed from saved ebooks'
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