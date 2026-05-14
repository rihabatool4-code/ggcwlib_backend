<?php

namespace App\Http\Controllers\general\mydispute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\general\dispute\Lbdispute;

class MyDisputeController extends Controller
{
    public function index()
    {
        return response()->json(Lbdispute::all());
    }

    public function store(Request $request)
    {
        $dispute = Lbdispute::create([
            'subject' => $request->subject,
            'relatedbooks' => $request->relatedbooks,
            'category' => $request->category,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Dispute Added Successfully',
            'data' => $dispute
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
            'subject' => $request->subject,
            'relatedbooks' => $request->relatedbooks,
            'category' => $request->category,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Dispute Updated Successfully',
            'data' => $dispute
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