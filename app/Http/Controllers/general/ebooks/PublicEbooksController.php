<?php

namespace App\Http\Controllers\general\ebooks;

use App\Http\Controllers\Controller;
use App\Models\admin\Lbebook;
use Illuminate\Http\Request;

class PublicEbooksController extends Controller
{
     // ── 2. Load All Ebooks ──
    public function loadAllEbooks()
    {
        try {
            $ebooks = Lbebook::latest()->get();
            return response()->json([
                "success" => true,
                "ebooks"  => $ebooks
            ]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }
}
