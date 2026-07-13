<?php

namespace App\Support;

use App\Models\EventScheduleItem;
use App\Models\ScheduleItem;
use Illuminate\Support\Carbon;

readonly class TimelineItem
{
    public function __construct(
        public string $source,
        public EventScheduleItem|ScheduleItem $model,
        public Carbon $tanggal,
        public ?string $jam,
        public string $acara,
        public ?string $lokasi,
        public ?string $catatan,
    ) {}

    public static function fromModel(EventScheduleItem|ScheduleItem $model, string $source): self
    {
        return new self(
            source: $source,
            model: $model,
            tanggal: $model->tanggal,
            jam: $model->jam,
            acara: $model->acara,
            lokasi: $model->lokasi,
            catatan: $model->catatan,
        );
    }

    public function tanggalLabel(): string
    {
        return IndonesianDate::label($this->tanggal);
    }

    public function isPast(): bool
    {
        return $this->tanggal->lt(today());
    }

    public function sortKey(): string
    {
        return $this->tanggal->format('Y-m-d').'-'.($this->jam ?? '99:99');
    }
}
