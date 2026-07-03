<?php

namespace App\Http\Controllers\teacher\ebooks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\general\Lbfavouritebook;

class TeacherSavedPdfsController extends Controller
{
    // ── Save an ebook (Save for Later) ──
    public function saveEbook(Request $request)
    {
        try {
            $exists = Lbfavouritebook::where('lbteacher_id', $request->lbteacher_id)
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
 
    // ── Get all saved ebooks of a teacher ──
    public function getSavedEbooks($teacher_id)
    {
        try {
            $saved = Lbfavouritebook::where('lbteacher_id', $teacher_id)
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
 
    // ── Remove a saved ebook ──
    public function removeSavedEbook($id)
    {
        try {
            $saved = Lbfavouritebook::find($id);
            if ($saved) {
                $saved->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Removed from saved PDFs'
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
