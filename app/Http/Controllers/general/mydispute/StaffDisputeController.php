<?php

namespace App\Http\Controllers\general\mydispute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\general\dispute\Lbdispute;

class StaffDisputeController extends Controller
{
    // // =========================
    // FETCH ALL DISPUTES
    // =========================

    public function viewAllDisputes(Request $request)
    {
        // return response()->json(["request" => $request->toArray()]);
        $disputes = Lbdispute::where(["lbteacher_id"=> $request->lbteacher_id])->get();
        return response()->json(["disputes" => $disputes]);
    }
    
    
    public function index()
    {
        return response()->json(Lbdispute::all());
    }

    // =========================
    // CREATE DISPUTE
    // =========================
    public function store(Request $request)
    {

        $dispute = Lbdispute::create([
            'subject' => $request->subject,
            'relatedbooks' => $request->relatedbooks,
            'category' => $request->category,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Staff Dispute Added Successfully',
            'data' => $dispute
        ]);
    }

    // =========================
    // FETCH SINGLE DISPUTE
    // =========================
    public function show($id)
    {
        return response()->json(Lbdispute::find($id));
    }

    // =========================
    // UPDATE DISPUTE
    // =========================
    public function update(Request $request, $id)
    {

        $dispute = Lbdispute::find($id);

        $dispute->update([
            'subject' => $request->subject,
            'relatedbooks' => $request->relatedbooks,
            'category' => $request->category,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Staff Dispute Updated Successfully',
            'data' => $dispute
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
            'message' => 'Staff Dispute Deleted Successfully'
        ]);
    }
}
