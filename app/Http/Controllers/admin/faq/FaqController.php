<?php

namespace App\Http\Controllers\admin\faq;

use App\Http\Controllers\Controller;

use App\Models\admin\faq\Lbfaq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    /**
     * POST /api/admin/getAllFaqs
     * Optional body params: audience=Student|Teacher, search=keyword
     */
    public function getAllFaqs(Request $request)
    {
        $data = Lbfaq::query();

        if ($request->filled('audience')) {
            $data->where('audience', $request->audience);
        }

        if ($request->filled('search')) {
            $data->where('question', 'like', '%' . $request->search . '%');
        }

        $data = $data->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    /**
     * POST /api/admin/createFaq
     */
    public function createFaq(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'audience' => 'required|in:Student,Teacher',
            'question' => 'required|string|max:255',
            'answer'   => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = LbFaq::create($request->only(['audience', 'question', 'answer']));

        return response()->json([
            'status' => true,
            'message' => 'FAQ created successfully',
            'data' => $data,
        ], 201);
    }

    /**
     * POST /api/admin/updateFaq
     * Body params: id, audience, question, answer
     */
    public function updateFaq(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'       => 'required|exists:faqs,id',
            'audience' => 'required|in:Student,Teacher',
            'question' => 'required|string|max:255',
            'answer'   => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = LbFaq::find($request->id);
        $data->update($request->only(['audience', 'question', 'answer']));

        return response()->json([
            'status' => true,
            'message' => 'FAQ updated successfully',
            'data' => $data,
        ]);
    }

    /**
     * POST /api/admin/deleteFaq
     * Body params: id
     */
    public function deleteFaq(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:faqs,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = LbFaq::find($request->id);
        $data->delete();

        return response()->json([
            'status' => true,
            'message' => 'FAQ deleted successfully',
        ]);
    }
}