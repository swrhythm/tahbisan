<x-layouts.app title="Beranda">
    <div class="min-h-screen bg-cream">
        <x-site-header :show-logo="true">
            <a href="{{ route('login') }}" class="rounded-sm border border-maroon px-5 py-2.5 text-sm font-semibold text-maroon transition-colors hover:bg-maroon hover:text-cream">Masuk</a>
        </x-site-header>

        <div class="mx-auto max-w-2xl px-6 pb-14 pt-18 text-center">
            <div class="mb-4 text-[13px] font-semibold uppercase tracking-[3px] text-gold">Selamat Datang</div>
            <h1 class="font-serif text-4xl font-semibold leading-tight text-maroon-dark sm:text-5xl">Tahbisan Diakon &amp; Imam</h1>
            <div class="mt-4 font-serif text-lg italic leading-relaxed text-taupe">
                &ldquo;Aku memilih kamu&hellip; dan menetapkan kamu, supaya kamu pergi dan menghasilkan buah.&rdquo;
            </div>
            <div class="mt-9 flex items-center justify-center gap-3.5">
                <div class="h-px w-12 bg-gold-light"></div>
                <div class="text-xl text-gold">&#10013;</div>
                <div class="h-px w-12 bg-gold-light"></div>
            </div>
        </div>

        <div class="mx-auto max-w-5xl px-6 pb-24">
            <div class="mb-5 text-[13px] font-semibold uppercase tracking-[2px] text-taupe">Pilih Tahbisan</div>

            @if ($events->isEmpty())
                <div class="py-12 text-center text-[15px] text-taupe">Belum ada tahbisan yang dijadwalkan.</div>
            @else
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($events as $event)
                        <a href="{{ route('public.event', $event) }}" class="block rounded-md border border-border bg-white p-7 transition-all hover:-translate-y-0.5 hover:shadow-lg">
                            <div class="mb-3.5 flex items-center justify-between">
                                <span class="rounded-full bg-chip px-2.5 py-1 text-[11px] font-bold uppercase tracking-wide text-maroon">{{ $event->candidates_count }} Calon</span>
                                <span class="text-xs font-semibold text-gold">{{ $event->daysLabel() }}</span>
                            </div>
                            <div class="font-serif text-2xl font-semibold text-maroon-dark">{{ $event->dateLabel() }}</div>
                            <div class="mt-2 text-sm text-taupe">{{ $event->jam }} &middot; {{ $event->lokasi }}</div>
                            <div class="mt-5 text-sm font-semibold text-maroon">Lihat Detail &rarr;</div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
