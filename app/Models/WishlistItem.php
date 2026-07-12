<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['candidate_id', 'nama', 'qty'])]
class WishlistItem extends Model
{
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function claims(): HasMany
    {
        return $this->hasMany(WishlistClaim::class)->orderBy('id');
    }

    public function isUnlimited(): bool
    {
        return (int) $this->qty === 0;
    }

    public function givenQty(): int
    {
        return (int) $this->claims->sum('qty');
    }

    public function isFulfilled(): bool
    {
        return ! $this->isUnlimited() && $this->givenQty() >= $this->qty;
    }

    public function remainingQty(): int
    {
        return $this->isUnlimited() ? PHP_INT_MAX : max(0, $this->qty - $this->givenQty());
    }

    public function progressLabel(): string
    {
        $given = $this->givenQty();

        return $this->isUnlimited()
            ? "{$given} orang telah memberi — tanpa batas"
            : "{$given} dari {$this->qty} terpenuhi";
    }
}
