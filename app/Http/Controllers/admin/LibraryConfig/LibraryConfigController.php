<?php

namespace App\Http\Controllers\Admin\LibraryConfig;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\LibraryConfig\LibraryConfig;
use Illuminate\Support\Facades\Auth;
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
     * Only the admin with id = 1 is allowed to change this.
     * ============================================
     */

    public function updateLibraryConfiguration(Request $request)
    {
        $admin = Auth::guard('Lbadmin')->user();

        if (!$admin || (int) $admin->id !== 1) {
            return response()->json([
                "success" => false,
                "message" => "You are not authorized to update the library configuration.",
            ], 403);
        }

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