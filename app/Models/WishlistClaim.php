<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['wishlist_item_id', 'nama', 'qty'])]
class WishlistClaim extends Model
{
    public function wishlistItem(): BelongsTo
    {
        return $this->belongsTo(WishlistItem::class);
    }
}
