@props(['tanggalLabel', 'jam' => null, 'acara', 'lokasi' => null, 'catatan' => null, 'isPast' => false, 'badge' => null])

<div {{ $attributes->class(['flex items-start justify-between gap-4 border-b border-chip py-4', 'opacity-45' => $isPast]) }}>
    <div class="flex gap-4">
        <div class="mt-2 h-2 w-2 flex-shrink-0 rounded-full {{ $isPast ? 'bg-taupe' : 'bg-gold' }}"></div>
        <div>
            <div class="flex flex-wrap items-center gap-2">
                <div class="text-[15px] font-semibold text-maroon-dark">{{ $acara }}</div>
                @if ($badge)
                    <span class="rounded-full bg-chip px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-maroon">{{ $badge }}</span>
                @endif
            </div>
            <div class="mt-1 text-[13px] font-semibold text-maroon">{{ $tanggalLabel }}{{ $jam ? ' · '.$jam : '' }}</div>
            @if ($lokasi)
                <div class="mt-0.5 text-sm text-taupe">{{ $lokasi }}</div>
            @endif
            @if ($catatan)
                <div class="mt-2 text-[13px] italic text-taupe">{{ $catatan }}</div>
            @endif
        </div>
    </div>
    @isset($actions)
        <div class="flex flex-shrink-0 gap-2">{{ $actions }}</div>
    @endisset
</div>
