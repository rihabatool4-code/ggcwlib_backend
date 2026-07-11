<?php

namespace App\Http\Controllers\admin\bookConfig;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\bookConfig\LbBookconfig;

class BookConfigController extends Controller
{
    /**
     * ==========================================
     * Get Book Configuration
     * ==========================================
     */

    public function getBookConfiguration()
    {

        $bookConfiguration = LbBookconfig::first();

        return response()->json([

            "success" => true,

            "bookConfiguration" => $bookConfiguration

        ]);

    }

    /**
     * ==========================================
     * Create / Update Book Configuration
     * ==========================================
     */

    public function updateBookConfiguration(Request $request)
    {

        $validatedData = $request->validate([

            "fine_per_day" => "required|integer",

            "max_issue_days" => "required|integer",

            "max_books_student" => "required|integer",

            "max_books_staff" => "required|integer",

            "lost_book_fine" => "required|integer",

            "damaged_book_fine" => "required|integer",

        ]);

        $bookConfiguration = LbBookconfig::first();

        if ($bookConfiguration) {

            $bookConfiguration->update($validatedData);

        } else {

            $bookConfiguration = LbBookconfig::create($validatedData);

        }

        return response()->json([

            "success" => true,

            "message" => "Book Configuration Updated Successfully.",

            "bookConfiguration" => $bookConfiguration

        ]);

    }

}