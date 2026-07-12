<?php

namespace App\Models;

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

    protected static array $months = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
    ];

    public function dateLabel(): string
    {
        return $this->date->day.' '.static::$months[(int) $this->date->format('n')].' '.$this->date->year;
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
