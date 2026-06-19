<?php

namespace App\Http\Controllers\general\mydispute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\general\dispute\Lbdispute;

class StaffDisputeController extends Controller
{

    // =========================
    // CREATE DISPUTE
    // =========================
    public function add(Request $request)
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
        'success' => true,
        'message' => 'Staff Dispute Added Successfully',
        'data'    => $dispute
    ]);
}

    // =========================
    // FETCH SINGLE DISPUTE
    // =========================
   public function fetchAllDisputes(Request $request)
    {
        // return response()->json(['request' => $request->toArray()]);
        // return response()->json(Lbdispute::find($request->lbstudent_id));
        $disputes = Lbdispute::where(['lbteacher_id' => $request->lbteacher_id])->get();
        return response()->json(['success' => true, 'disputes' => $disputes]);
    }


    // =========================
    // UPDATE DISPUTE
    // =========================
    public function update(Request $request)
{
    $dispute = Lbdispute::where([
        'id' => $request->id,
        'lbteacher_id' => $request->lbteacher_id
    ])->first();

    if (!$dispute) {
        return response()->json([
            'success' => false,
            'message' => 'Dispute not found'
        ]);
    }

    $dispute->update([
        'lbbook_id'    => $request->lbbook_id,
        'subject'      => $request->subject,
        'category'     => $request->category,
        'description'  => $request->description,
        'status'       => $request->status,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Staff Dispute Updated Successfully',
        'data'    => $dispute
    ]);
}
    // =========================
    // DELETE DISPUTE
    // =========================
   public function delete(Request $request)
{
    $dispute = Lbdispute::where([
        'id' => $request->id,
        'lbteacher_id' => $request->lbteacher_id
    ])->first();

    if (!$dispute) {
        return response()->json([
            'success' => false,
            'message' => 'Dispute not found'
        ]);
    }

    $dispute->delete();

    return response()->json([
        'success' => true,
        'message' => 'Staff Dispute Deleted Successfully'
    ]);
}
}