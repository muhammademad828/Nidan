<?php

namespace App\Http\Controllers;

use App\Models\BespokePhotoUpload;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BespokeUploadController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'photo' => ['required', 'image', 'max:8192', 'mimes:jpeg,jpg,png,webp,gif'],
        ]);

        $path = $request->file('photo')->store('bespoke-uploads', 'public');

        BespokePhotoUpload::create([
            'user_id'           => $request->user()?->id,
            'path'              => $path,
            'original_filename' => $request->file('photo')->getClientOriginalName(),
        ]);

        return response()->json(['ok' => true]);
    }
}
