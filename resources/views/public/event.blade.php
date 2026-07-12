<x-layouts.app title="Detail Tahbisan">
    <div class="min-h-screen bg-cream">
        <x-site-header>
            <x-slot:left>
                <a href="{{ route('public.landing') }}" class="text-sm font-semibold text-maroon">&larr; Semua Tahbisan</a>
            </x-slot:left>
            <a href="{{ route('login') }}" class="rounded-sm border border-maroon px-5 py-2.5 text-sm font-semibold text-maroon transition-colors hover:bg-maroon hover:text-cream">Masuk</a>
        </x-site-header>

        <div class="mx-auto max-w-4xl px-6 pb-24 pt-14">
            <div class="mb-11 text-center">
                <div class="font-serif text-4xl font-semibold text-maroon-dark">{{ $event->dateLabel() }}</div>
                <div class="mt-2.5 text-[15px] text-taupe">{{ $event->jam }} &middot; {{ $event->lokasi }}</div>
            </div>

            <div class="mb-5 text-[13px] font-semibold uppercase tracking-[2px] text-taupe">Calon yang Ditahbiskan</div>

            @if ($event->candidates->isEmpty())
                <div class="py-12 text-center text-sm text-taupe">Belum ada calon yang terdaftar.</div>
            @else
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($event->candidates as $candidate)
                        <a href="{{ route('public.profile', $candidate) }}" class="block rounded-md border border-border bg-white p-6.5 transition-all hover:-translate-y-0.5 hover:shadow-lg">
                            <span class="rounded-full bg-chip px-2.5 py-1 text-[11px] font-bold uppercase tracking-wide text-maroon">{{ $candidate->categoryLabel() }}</span>
                            <div class="mt-3.5 font-serif text-2xl font-semibold text-maroon-dark">{{ $candidate->displayName() }}</div>
                            <div class="mt-4.5 text-sm font-semibold text-maroon">Lihat Detail &rarr;</div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
