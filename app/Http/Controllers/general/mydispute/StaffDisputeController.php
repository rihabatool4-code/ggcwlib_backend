<?php

namespace App\Http\Controllers\general\mydispute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\general\dispute\Lbdispute;

class StaffDisputeController extends Controller
{
    // =========================
    // FETCH TEACHER'S DISPUTES ONLY
    // =========================
    public function viewAllDisputes(Request $request)
    {
        $disputes = Lbdispute::where('lbteacher_id', $request->lbteacher_id)->get();
        return response()->json(["disputes" => $disputes]);
    }

    // =========================
    // CREATE DISPUTE
    // =========================
    public function store(Request $request)
    {
        $dispute = Lbdispute::create([
            'lbteacher_id' => $request->lbteacher_id,
            'lbbook_id'    => $request->lbbook_id,
            'raisedby'     => 'teacher',
            'subject'      => $request->subject,
            'category'     => $request->category,
            'description'  => $request->description,
            'status'       => 'open',
        ]);

        return response()->json([
            'message' => 'Staff Dispute Added Successfully',
            'data'    => $dispute
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
            'lbbook_id'   => $request->lbbook_id,
            'subject'     => $request->subject,
            'category'    => $request->category,
            'description' => $request->description,
            'status'      => $request->status,
        ]);

        return response()->json([
            'message' => 'Staff Dispute Updated Successfully',
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
            'message' => 'Staff Dispute Deleted Successfully'
        ]);
    }
}