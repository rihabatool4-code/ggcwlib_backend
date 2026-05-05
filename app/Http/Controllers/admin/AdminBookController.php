<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Lbbook;
use Illuminate\Http\Request;

class AdminBookController extends Controller
{
    public function fetchAllBooks(){
        // return response()->json(["request"]);
        $books = Lbbook::all();
        // return response()->json(["success" => true, "books" => $books]);
         return response()->json(["success" => true, "books" => $books]);
    }
}
