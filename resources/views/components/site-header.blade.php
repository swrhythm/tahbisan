@props(['showLogo' => false])

<div class="flex items-center justify-between border-b border-border px-6 py-5 sm:px-12">
    <div>
        @if ($showLogo)
            <a href="{{ route('public.landing') }}" class="font-serif text-2xl font-semibold tracking-wide text-maroon">Tahbisan</a>
        @else
            {{ $left ?? '' }}
        @endif
    </div>
    <div class="flex items-center gap-2.5">
        {{ $slot }}
    </div>
</div>
