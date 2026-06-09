<?php

namespace App\Http\Controllers\Admin\blogs;

use App\Http\Controllers\Controller;
use App\Models\admin\Lbblog;
use Illuminate\Http\Request;

class AdminBlogsController extends Controller
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

    /* ── Add Blog ── */
    public function addBlog(Request $request)
    {
        try {
            $imgPath = null;

            // ✅ Image upload — books wala same pattern
            if ($request->hasFile('img')) {
                $imgPath = $request->file('img')
                                   ->store('blogs', 'public');
            }

            $blog = Lbblog::create([
                'title'    => $request->title,
                'author'   => $request->author,
                'category' => $request->category,
                'excerpt'  => $request->excerpt,
                'content'  => $request->blog_content,
                'img'      => $imgPath,
                'tags'     => json_decode($request->tags, true) ?? [],
            ]);

            return response()->json([
                'success' => true,
                'blog'    => $blog,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /* ── Update Blog ── */
    public function updateBlog(Request $request, $id)
    {
        try {
            $blog = Lbblog::findOrFail($id);

            $imgPath = $blog->img; // purani image rakho by default

            // ✅ Nai image upload ho to replace karo
            if ($request->hasFile('img')) {
                // Purani image delete karo
                if ($blog->img) {
                    \Storage::disk('public')->delete($blog->img);
                }
                $imgPath = $request->file('img')
                                   ->store('blogs', 'public');
            }

            $blog->update([
                'title'    => $request->title    ?? $blog->title,
                'author'   => $request->author   ?? $blog->author,
                'category' => $request->category ?? $blog->category,
                'excerpt'  => $request->excerpt  ?? $blog->excerpt,
                'content'  => $request->blog_content  ?? $blog->content,
                'img'      => $imgPath,
                'tags'     => json_decode($request->tags, true) ?? $blog->tags,
            ]);

            return response()->json([
                'success' => true,
                'blog'    => $blog,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /* ── Delete Blog ── */
    public function deleteBlog($id)
    {
        try {
            $blog = Lbblog::findOrFail($id);

            // Image bhi delete karo
            if ($blog->img) {
                \Storage::disk('public')->delete($blog->img);
            }

            $blog->delete();

            return response()->json([
                'success' => true,
                'message' => 'Blog deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}