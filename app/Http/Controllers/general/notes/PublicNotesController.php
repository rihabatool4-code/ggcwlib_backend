<?php

namespace App\Http\Controllers\general\notes;

use App\Http\Controllers\Controller;
use App\Models\note\Lbnote;
use Illuminate\Http\Request;

class PublicNotesController extends Controller
{
    // ── Load ALL Notes (Public — no teacher filter) ──
    public function loadAllPublicNotes()
    {
        try {
            $notes = Lbnote::with('lbteacher')->latest()->get();

            return response()->json([
                "success" => true,
                "notes"   => $notes
            ]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }
}
