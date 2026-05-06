<?php

namespace App\Http\Controllers\Teacher\notes;

use App\Http\Controllers\Controller;
use App\Models\note\Lbnote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeacherNotesController extends Controller
{
    // ── 1. Upload Note ──
    public function uploadNote(Request $request)
    {
        try {
            if ($request->hasFile('pdf_file')) {
                $file     = $request->file('pdf_file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path     = $file->storeAs('public/notes', $fileName);

                $note = Lbnote::create([
                    'title'    => $request->title,
                    'subject'  => $request->subject,
                    'pdf_file' => $fileName,
                      'Lbteacher_id' => $request->Lbteacher_id,
                ]);

                if ($note) {
                    return response()->json([
                        "success" => true,
                        "message" => "Note uploaded successfully",
                        "note"    => $note
                    ]);
                }
            }
            return response()->json([
                "success" => false,
                "message" => "File not received"
            ]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }

    // ── 2. Load All Notes ──
    public function loadAllNotes($Lbteacher_id)
    {
        try {
            // $notes = Lbnote::where(['lbteacher_id'=>$request->lbteacher_id])->get();
                    $notes = Lbnote::where('Lbteacher_id', $Lbteacher_id)->latest()->get(); 
            // $notes = Lbnote::latest()->get();
            return response()->json([
                "success" => true,
                "notes"   => $notes
            ]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }
 
    // ── 3. Delete Note ──
    public function deleteNote($id)
    {
        try {
            $note = Lbnote::find($id);
            if ($note) {
                Storage::delete('public/notes/' . $note->pdf_file);
                $note->delete();
                return response()->json([
                    "success" => true,
                    "message" => "Note deleted successfully"
                ]);
            }
            return response()->json([
                "success" => false,
                "message" => "Note not found"
            ]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }

    // ── 4. Update Note ──
    public function updateNote(Request $request, $id)
    {
        try {
            $note = Lbnote::find($id);
            if (!$note) {
                return response()->json([
                    "success" => false,
                    "message" => "Note not found"
                ]);
            }

            $note->title   = $request->title   ?? $note->title;
            $note->subject = $request->subject  ?? $note->subject;

            if ($request->hasFile('pdf_file')) {
                Storage::delete('public/notes/' . $note->pdf_file);
                $file     = $request->file('pdf_file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/notes', $fileName);
                $note->pdf_file = $fileName;
            }

            $note->save();
            return response()->json([
                "success" => true,
                "message" => "Note updated successfully",
                "note"    => $note
            ]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }
}