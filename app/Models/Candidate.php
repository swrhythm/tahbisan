<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

#[Fillable(['event_id', 'category', 'name', 'password', 'biography'])]
#[Hidden(['password'])]
class Candidate extends Authenticatable
{
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function scheduleItems(): HasMany
    {
        return $this->hasMany(ScheduleItem::class)->orderBy('id');
    }

    public function wishlistItems(): HasMany
    {
        return $this->hasMany(WishlistItem::class)->orderBy('id');
    }

    public function categoryLabel(): string
    {
        return $this->category === 'imam' ? 'Calon Imam' : 'Calon Diakon';
    }

    public function displayName(): string
    {
        return ($this->category === 'imam' ? 'Diakon ' : 'Fr. ').$this->name;
    }
}
