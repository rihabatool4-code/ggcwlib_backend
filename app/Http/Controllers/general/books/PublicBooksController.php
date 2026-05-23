<?php

namespace App\Http\Controllers\general\books;

use App\Http\Controllers\Controller;
use App\Models\admin\Lbbook;
use Illuminate\Http\Request;

class PublicBooksController extends Controller
{
    public function fetchAllBooks()
    {
        $books = Lbbook::all();

        return response()->json([
            "status" => 200,
            "books" => $books
        ]);
    }
}