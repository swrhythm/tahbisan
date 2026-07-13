<?php

namespace App\Models;

use App\Support\IndonesianDate;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['event_id', 'tanggal', 'jam', 'acara', 'lokasi', 'catatan'])]
class EventScheduleItem extends Model
{
    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function tanggalLabel(): string
    {
        return IndonesianDate::label($this->tanggal);
    }

    public function isPast(): bool
    {
        return $this->tanggal->lt(today());
    }
}
