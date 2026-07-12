<?php

namespace App\Http\Controllers\Frater;

use App\Http\Controllers\Controller;
use App\Models\WishlistItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistItemController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'qty' => ['nullable', 'integer', 'min:0'],
        ]);

        Auth::guard('frater')->user()->wishlistItems()->create([
            'nama' => $data['nama'],
            'qty' => $data['qty'] ?? 0,
        ]);

        return redirect()->route('frater.dashboard', ['tab' => 'wishlist'])
            ->with('toast', 'Barang ditambahkan.');
    }

    public function destroy(WishlistItem $wishlistItem): RedirectResponse
    {
        abort_unless($wishlistItem->candidate_id === Auth::guard('frater')->id(), 403);

        $wishlistItem->delete();

        return redirect()->route('frater.dashboard', ['tab' => 'wishlist'])
            ->with('toast', 'Barang dihapus.');
    }
}
