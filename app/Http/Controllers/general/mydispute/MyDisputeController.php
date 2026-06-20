<?php

namespace App\Http\Controllers\general\mydispute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\general\dispute\Lbdispute;
use Log;

class MyDisputeController extends Controller
{
   public function index(Request $request)
{
    try {
        // Frontend se bheja gaya ID pakadna (Dono options check karte hain)
        $studentId = $request->query('student_id') ?: $request->query('lbstudent_id');

        // Agar ID na mile toh crash na ho, khali array chali jaye
        if (!$studentId) {
            return response()->json([], 200);
        }

        // Database query jo direct fetch karegi
        $disputes = Lbdispute::where('lbstudent_id', $studentId)
            ->where('raisedby', 'student')
            ->orderBy('id', 'desc') // Taake naya dispute sabse upar dikhe
            ->get();

        return response()->json($disputes, 200);

    } catch (\Exception $e) {
        // Agar koi crash ho toh error details frontend console mein dikhein (500 crash page ke bajaye)
        return response()->json([
            'message' => 'Backend Fetch Error',
            'error' => $e->getMessage()
        ], 500);
    }
}
    public function store(Request $request)
    {
        try {
            // Mapping keys properly from standard JSON body payload
            $dispute = Lbdispute::create([
                'lbstudent_id' => $request->input('student_id') ?? $request->input('lbstudent_id'),
                'lbbook_id'    => $request->input('lbbook_id'),
                'raisedby'     => $request->input('raisedby', 'student'),
                'subject'      => $request->input('subject'),
                'category'     => $request->input('category'),
                'description'  => $request->input('description'),
                'status'       => 'open',
            ]);

            return response()->json([
                'message' => 'Dispute Added Successfully',
                'data'    => $dispute
            ], 200);

        } catch (\Exception $e) {
            // Agar database level par column missing error aaye toh catch ho sake
            return response()->json([
                'message' => 'Database Query Error',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function fetchAllDisputes(Request $request)
    {
        // return response()->json(['request' => $request->toArray()]);
        // return response()->json(Lbdispute::find($request->lbstudent_id));
        $disputes = Lbdispute::where(['lbstudent_id' => $request->lbstudent_id])->get();
        return response()->json(['success' => true, 'disputes' => $disputes]);
    }

    public function update(Request $request, $id)
    {
        $dispute = Lbdispute::find($id);
        if (!$dispute) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $dispute->update([
            'lbbook_id'   => $request->input('lbbook_id'),
            'raisedby'    => $request->input('raisedby'),
            'subject'     => $request->input('subject'),
            'category'    => $request->input('category'),
            'description' => $request->input('description'),
            'status'      => $request->input('status'),
        ]);

        return response()->json([
            'message' => 'Dispute Updated Successfully',
            'data'    => $dispute
        ]);
    }

    public function destroy($id)
    {
        $dispute = Lbdispute::find($id);
        if ($dispute) {
            $dispute->delete();
        }

        return response()->json([
            'message' => 'Dispute Deleted Successfully'
        ]);
    }
}