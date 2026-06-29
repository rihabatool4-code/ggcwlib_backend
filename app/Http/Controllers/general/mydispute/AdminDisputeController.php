<?php

namespace App\Http\Controllers\general\mydispute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\general\dispute\Lbdispute;

class AdminDisputeController extends Controller
{
    // =========================
    // FETCH ALL DISPUTES
    // =========================
    public function fetchAllDisputes()
    {
        $disputes = Lbdispute::with(['lbteacher', 'lbstudent'])->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'disputes' => $disputes
        ]);
    }

    // =========================
    // RESOLVE DISPUTE
    // =========================
    public function resolve(Request $request)
{
    $dispute = Lbdispute::where('id', $request->id)->first();

    if (!$dispute) {
        return response()->json([
            'success' => false,
            'message' => 'Dispute not found'
        ], 404);
    }

    $dispute->update([
        'status' => 'resolved'
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Dispute resolved successfully',
        'data' => $dispute
    ]);
}

    // =========================
    // DELETE DISPUTE
    // =========================
    public function delete(Request $request)
    {
        $dispute = Lbdispute::where('id', $request->id)->first();

        if (!$dispute) {
            return response()->json([
                'success' => false,
                'message' => 'Dispute not found'
            ], 404);
        }

        $dispute->delete();

        return response()->json([
            'success' => true,
            'message' => 'Dispute Deleted Successfully'
        ]);
    }
}