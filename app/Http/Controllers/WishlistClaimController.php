<?php

namespace App\Http\Controllers;

use App\Models\WishlistItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WishlistClaimController extends Controller
{
    public function store(Request $request, WishlistItem $wishlistItem): RedirectResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'qty' => ['required', 'integer', 'min:1'],
        ]);

        $profileUrl = route('public.profile', $wishlistItem->candidate_id).'?tab=wishlist';

        if ($wishlistItem->isFulfilled()) {
            return redirect($profileUrl)->with('toast', 'Wishlist ini sudah terpenuhi.');
        }

        $qty = $wishlistItem->isUnlimited()
            ? $data['qty']
            : min($data['qty'], $wishlistItem->remainingQty());

        $wishlistItem->claims()->create([
            'nama' => $data['nama'],
            'qty' => $qty,
        ]);

        return redirect($profileUrl)->with('toast', 'Terima kasih atas kebaikan hati Anda! 🙏');
    }
}
