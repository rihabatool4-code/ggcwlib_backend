<?php

namespace App\Http\Controllers\Admin\LibraryConfig;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\LibraryConfig\LibraryConfig;
use Illuminate\Support\Facades\Log;

class LibraryConfigController extends Controller
{

    /**
     * ============================================
     * Get Library Configuration
     * ============================================
     */

    public function getLibraryConfiguration()
    {

        $libraryConfig = LibraryConfig::first();

        return response()->json([

            "success" => true,
            "libraryConfig" => $libraryConfig

        ]);

    }



    /**
     * ============================================
     * Create / Update Library Configuration
     * ============================================
     */

    public function updateLibraryConfiguration(Request $request)
    {
        Log::info($request->all());
        $validatedData = $request->validate([

            "library_name"  => "required|string|max:255",

            "email"         => "required|email|max:255",

            "phone"         => "required|string|max:20",

            "working_hours" => "required|string|max:255",

            "address"       => "required|string|max:500",

        ]);


        $libraryConfig = LibraryConfig::first();


        if ($libraryConfig) {

            $libraryConfig->update($validatedData);

        } else {

            $libraryConfig = LibraryConfig::create($validatedData);

        }


        return response()->json([

            "success" => true,

            "message" => "Library configuration updated successfully.",

            "libraryConfig" => $libraryConfig

        ]);

    }

}