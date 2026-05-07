<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Lbbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminBookController extends Controller
{
    // GET — Fetch All Books
  
    public function fetchAllBooks()
    {
        try {
            $books = Lbbook::latest()->get();

            return response()->json([
                "success" => true,
                "books"   => $books
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Failed to fetch books.",
                "error"   => $e->getMessage()
            ], 500);
        }
    }

    // ══════════════════════════════════════════
    // POST — Add New Book
    // ══════════════════════════════════════════
    public function addBook(Request $request)
    {
        try {
            $imgPath = null;

            // ── Image upload ──
            if ($request->hasFile('img')) {
                $imgPath = $request->file('img')
                                   ->store('books', 'public');
            }

            $book = Lbbook::create([
                'title'        => $request->title,
                'author'       => $request->author,
                'accession_no' => $request->accession_no,
                'dept'         => $request->dept,
                'total_copies' => $request->total_copies,
                'img'          => $imgPath,
                'is_donated'   => $request->is_donated ?? false,
            ]);

            return response()->json([
                "success" => true,
                "message" => "Book added successfully.",
                "book"    => $book
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Failed to add book.",
                "error"   => $e->getMessage()
            ], 500);
        }
    }

    // ══════════════════════════════════════════
    // POST — Update Book
    // ══════════════════════════════════════════
    public function updateBook(Request $request, $id)
    {
        try {
            $book = Lbbook::find($id);

            if (!$book) {
                return response()->json([
                    "success" => false,
                    "message" => "Book not found."
                ], 404);
            }

            // ── New image upload (old delete) ──
            if ($request->hasFile('img')) {
                // Delete old image if exists
                if ($book->img) {
                    Storage::disk('public')->delete($book->img);
                }
                $book->img = $request->file('img')->store('books', 'public');
            }

            $book->title        = $request->title        ?? $book->title;
            $book->author       = $request->author       ?? $book->author;
            $book->accession_no = $request->accession_no ?? $book->accession_no;
            $book->dept         = $request->dept         ?? $book->dept;
            $book->total_copies = $request->total_copies ?? $book->total_copies;
            $book->is_donated   = $request->is_donated   ?? $book->is_donated;

            $book->save();

            return response()->json([
                "success" => true,
                "message" => "Book updated successfully.",
                "book"    => $book
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Failed to update book.",
                "error"   => $e->getMessage()
            ], 500);
        }
    }

    // ══════════════════════════════════════════
    // DELETE — Delete Book
    // ══════════════════════════════════════════
    public function deleteBook($id)
    {
        try {
            $book = Lbbook::find($id);

            if (!$book) {
                return response()->json([
                    "success" => false,
                    "message" => "Book not found."
                ], 404);
            }

            // ── Delete image from storage ──
            if ($book->img) {
                Storage::disk('public')->delete($book->img);
            }

            $book->delete();

            return response()->json([
                "success" => true,
                "message" => "Book deleted successfully."
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Failed to delete book.",
                "error"   => $e->getMessage()
            ], 500);
        }
    }
}