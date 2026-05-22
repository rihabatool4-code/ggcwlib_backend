<?php

namespace App\Http\Controllers\general\mydispute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\general\dispute\Lbdispute;

class MyDisputeController extends Controller
{
    public function index(Request $request)
    {
        $studentId = $request->query('student_id');

        $disputes = Lbdispute::when($studentId, function ($query) use ($studentId) {
                return $query->where('lbstudent_id', $studentId);
            })
            ->get();

        return response()->json($disputes);
    }

    public function store(Request $request)
    {
        $dispute = Lbdispute::create([
            'lbstudent_id' => $request->lbstudent_id,
            'lbbook_id'    => $request->lbbook_id,
            'raisedby'     => $request->raisedby,
            'subject'      => $request->subject,
            'category'     => $request->category,
            'description'  => $request->description,
            'status'       => 'open',
        ]);

        return response()->json([
            'message' => 'Dispute Added Successfully',
            'data'    => $dispute
        ]);
    }

    public function show($id)
    {
        return response()->json(Lbdispute::find($id));
    }

    public function update(Request $request, $id)
    {
        $dispute = Lbdispute::find($id);

        $dispute->update([
            'lbbook_id'   => $request->lbbook_id,
            'raisedby'    => $request->raisedby,
            'subject'     => $request->subject,
            'category'    => $request->category,
            'description' => $request->description,
            'status'      => $request->status,
        ]);

        return response()->json([
            'message' => 'Dispute Updated Successfully',
            'data'    => $dispute
        ]);
    }

    public function destroy($id)
    {
        $dispute = Lbdispute::find($id);
        $dispute->delete();

        return response()->json([
            'message' => 'Dispute Deleted Successfully'
        ]);
    }
}