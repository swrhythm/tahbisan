<?php

namespace App\Http\Controllers\Frater;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BiographyImageController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
        ]);

        $candidateId = Auth::guard('frater')->id();
        $file = $request->file('image');
        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();

        $path = $file->storeAs("biography/{$candidateId}", $filename, 'uploads');

        // Root-relative, not Storage::url() — keeps stored biography HTML
        // working even if APP_URL changes later.
        return response()->json([
            'url' => '/uploads/'.$path,
        ]);
    }
}
