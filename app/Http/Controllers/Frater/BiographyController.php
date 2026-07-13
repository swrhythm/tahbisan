<?php

namespace App\Http\Controllers\Frater;

use App\Http\Controllers\Controller;
use App\Support\HtmlSanitizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BiographyController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'biography' => ['nullable', 'string'],
        ]);

        $biography = HtmlSanitizer::biography($data['biography'] ?? '');

        Auth::guard('frater')->user()->update(['biography' => $biography]);

        return redirect()->route('frater.dashboard', ['tab' => 'biography'])
            ->with('toast', 'Biography disimpan.');
    }
}
