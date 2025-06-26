<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ImageEditorController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $storagePath = 'editor/images/' . date('Y') . '/' . date('m');
        // $path = uploadFile($request, 'file', $storagePath);
        $uploadFile = uploadFile($request, 'file', $storagePath);
        if ($uploadFile !== false) {
            // $path = url('/' . $uploadFile['path']);
            $path = url(Storage::url($uploadFile['path']));

            return response()->json(['success' => 'Image uploaded successfully.', 'image' => $path]);
        } else {
            return response()->json(['message' => 'Gagal mengunggah file'], 500);
        }
    }
}
