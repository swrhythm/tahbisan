<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['candidate_id', 'tanggal', 'jam', 'acara', 'lokasi', 'catatan'])]
class ScheduleItem extends Model
{
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
