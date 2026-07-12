<?php

namespace App\Http\Controllers\Frater;

use App\Http\Controllers\Controller;
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

        Auth::guard('frater')->user()->update(['biography' => $data['biography'] ?? '']);

        return redirect()->route('frater.dashboard', ['tab' => 'biography'])
            ->with('toast', 'Biography disimpan.');
    }
}
