<?php

namespace App\Http\Controllers\general\mydispute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\general\dispute\Lbdispute;


class AdminDisputeController extends Controller
{
    // =========================
    // FETCH ALL DISPUTES (Student + Staff)
    // =========================
    public function index()
    {
        $disputes = Lbdispute::orderBy('created_at', 'desc')->get();
 
        return response()->json([
            'disputes' => $disputes
        ]);
    }
 
    // =========================
    // RESOLVE DISPUTE
    // =========================
    public function resolve($id)
    {
        $dispute = Lbdispute::find($id);
 
        if (!$dispute) {
            return response()->json(['message' => 'Dispute not found'], 404);
        }
 
        $dispute->update(['status' => 'resolved']);
 
        return response()->json([
            'message' => 'Dispute Resolved Successfully',
            'data'    => $dispute
        ]);
    }
 
    // =========================
    // DELETE DISPUTE
    // =========================
    public function destroy($id)
    {
        $dispute = Lbdispute::find($id);
        $dispute->delete();
 
        return response()->json([
            'message' => 'Dispute Deleted Successfully'
        ]);
    }
}
