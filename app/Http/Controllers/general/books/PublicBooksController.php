<?php

namespace App\Http\Controllers\general\books;

use App\Http\Controllers\Controller;
use App\Models\admin\Lbbook;
use Illuminate\Http\Request;

class PublicBooksController extends Controller
{
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


}