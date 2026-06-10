<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048'
        ]);

        $path = $request->file('image')
                        ->store('images', 's3');

        return response()->json([
            'success' => true,
            'path' => $path
        ]); 
    }
}
