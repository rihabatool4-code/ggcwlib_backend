<?php

namespace App\Http\Controllers\general\blogs;

use App\Http\Controllers\Controller;
use App\Models\admin\Lbblog;
use Illuminate\Http\Request;

class PublicBlogsController extends Controller
{
    /* ── Fetch All ── */
    public function fetchAllBlogs()
    {
        try {
            $blogs = Lbblog::latest()->get();
            return response()->json([
                'success' => true,
                'blogs'   => $blogs,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
