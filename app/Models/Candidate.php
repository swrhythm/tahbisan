<?php

namespace App\Models;

use App\Support\TimelineItem;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;

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
        return $this->hasMany(ScheduleItem::class)->orderBy('tanggal')->orderBy('jam');
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

    /**
     * The candidate's schedule: shared event-wide items plus their own
     * personal items, merged into a single chronological timeline.
     *
     * @return Collection<int, TimelineItem>
     */
    public function timeline(): Collection
    {
        $shared = $this->event->scheduleItems->map(
            fn (EventScheduleItem $item) => TimelineItem::fromModel($item, 'event')
        );

        $personal = $this->scheduleItems->map(
            fn (ScheduleItem $item) => TimelineItem::fromModel($item, 'personal')
        );

        return $shared->concat($personal)->sortBy(fn (TimelineItem $item) => $item->sortKey())->values();
    }
}
