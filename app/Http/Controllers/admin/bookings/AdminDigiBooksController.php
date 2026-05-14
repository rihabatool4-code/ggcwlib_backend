<?php

namespace App\Http\Controllers\admin\bookings;

use App\Http\Controllers\Controller;
use App\Models\admin\Lbebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminDigiBooksController extends Controller
{
    // ── 1. Upload Ebook ──
    public function uploadEbook(Request $request)
    {
        try {
            if ($request->hasFile('pdf_file')) {
                $file     = $request->file('pdf_file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path     = $file->storeAs('public/ebooks', $fileName);

                $ebook = Lbebook::create([
                    'title'    => $request->title,
                    'author'   => $request->author,
                    'dept'     => $request->dept,
                    'pdf_file' => $fileName,
                ]);

                if ($ebook) {
                    return response()->json([
                        "success" => true,
                        "message" => "Ebook uploaded successfully",
                        "ebook"   => $ebook
                    ]);
                }
            }
            return response()->json([
                "success" => false,
                "message" => "File not received"
            ]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }

    // ── 2. Load All Ebooks ──
    public function loadAllEbooks()
    {
        try {
            $ebooks = Lbebook::latest()->get();
            return response()->json([
                "success" => true,
                "ebooks"  => $ebooks
            ]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }

    // ── 3. Update Ebook ──
    public function updateEbook(Request $request, $id)
    {
        try {
            $ebook = Lbebook::find($id);
            if (!$ebook) {
                return response()->json([
                    "success" => false,
                    "message" => "Ebook not found"
                ]);
            }

            $ebook->title  = $request->title  ?? $ebook->title;
            $ebook->author = $request->author ?? $ebook->author;
            $ebook->dept   = $request->dept   ?? $ebook->dept;

            if ($request->hasFile('pdf_file')) {
                // Delete old file
                Storage::delete('public/ebooks/' . $ebook->pdf_file);
                $file     = $request->file('pdf_file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/ebooks', $fileName);
                $ebook->pdf_file = $fileName;
            }

            $ebook->save();
            return response()->json([
                "success" => true,
                "message" => "Ebook updated successfully",
                "ebook"   => $ebook
            ]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }

    // ── 4. Delete Ebook ──
    public function deleteEbook($id)
    {
        try {
            $ebook = Lbebook::find($id);
            if ($ebook) {
                Storage::delete('public/ebooks/' . $ebook->pdf_file);
                $ebook->delete();
                return response()->json([
                    "success" => true,
                    "message" => "Ebook deleted successfully"
                ]);
            }
            return response()->json([
                "success" => false,
                "message" => "Ebook not found"
            ]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }
}