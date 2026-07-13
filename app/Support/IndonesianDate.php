<?php

namespace App\Support;

use Illuminate\Support\Carbon;

class IndonesianDate
{
    private const MONTHS = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
    ];

    public static function label(Carbon $date): string
    {
        return $date->day.' '.self::MONTHS[(int) $date->format('n')].' '.$date->year;
    }
}
