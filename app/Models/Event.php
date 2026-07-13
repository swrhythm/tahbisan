<?php

namespace App\Models;

use App\Support\IndonesianDate;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['date', 'jam', 'lokasi'])]
class Event extends Model
{
    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class);
    }

    public function scheduleItems(): HasMany
    {
        return $this->hasMany(EventScheduleItem::class)->orderBy('tanggal')->orderBy('jam');
    }

    public function dateLabel(): string
    {
        return IndonesianDate::label($this->date);
    }

    public function daysLabel(): string
    {
        $today = today()->startOfDay();
        $target = $this->date->copy()->startOfDay();

        if ($target->gt($today)) {
            return 'H-'.$today->diffInDays($target);
        }

        if ($target->eq($today)) {
            return 'Hari Ini';
        }

        return 'Selesai';
    }
}
