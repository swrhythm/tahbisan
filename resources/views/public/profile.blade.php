<x-layouts.app :title="$candidate->displayName()">
    <div class="min-h-screen bg-cream">
        <x-site-header>
            <x-slot:left>
                <a href="{{ route('public.event', $candidate->event) }}" class="text-sm font-semibold text-maroon">&larr; Kembali</a>
            </x-slot:left>
            <a href="{{ route('login') }}" class="rounded-sm border border-maroon px-5 py-2.5 text-sm font-semibold text-maroon transition-colors hover:bg-maroon hover:text-cream">Masuk</a>
        </x-site-header>

        <div class="mx-auto max-w-3xl px-6 pb-24 pt-14">
            <div class="mb-10 text-center">
                <span class="rounded-full bg-chip px-3.5 py-1.5 text-[11px] font-bold uppercase tracking-wide text-maroon">{{ $candidate->categoryLabel() }}</span>
                <div class="mt-4.5 font-serif text-4xl font-semibold text-maroon-dark sm:text-5xl">{{ $candidate->displayName() }}</div>
                <div class="mt-2.5 text-[15px] text-taupe">{{ $candidate->event->dateLabel() }} &middot; {{ $candidate->event->lokasi }}</div>
            </div>

            <div class="mb-9 flex justify-center gap-2 border-b border-border">
                @foreach (['informasi' => 'Informasi', 'biography' => 'Biography', 'wishlist' => 'Wishlist'] as $key => $label)
                    <a href="{{ route('public.profile', $candidate).'?tab='.$key }}"
                       class="border-b-2 px-5 py-3 text-sm font-semibold {{ $tab === $key ? 'border-maroon text-maroon' : 'border-transparent text-taupe' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            @if ($tab === 'informasi')
                @if ($timeline->isEmpty())
                    <div class="py-12 text-center text-sm text-taupe">Jadwal belum tersedia.</div>
                @else
                    <div>
                        @foreach ($timeline as $item)
                            <x-timeline-item
                                :tanggal-label="$item->tanggalLabel()"
                                :jam="$item->jam"
                                :acara="$item->acara"
                                :lokasi="$item->lokasi"
                                :catatan="$item->catatan"
                                :is-past="$item->isPast()"
                            />
                        @endforeach
                    </div>
                @endif
            @elseif ($tab === 'biography')
                @if ($candidate->biography)
                    <div class="biography-content">{!! $candidate->biography !!}</div>
                @else
                    <div class="text-lg text-taupe">Biography belum tersedia.</div>
                @endif
            @else
                <div>
                    <div class="mb-6 text-sm text-taupe">Sudah terpenuhi? Cek dulu di sini sebelum memberi, agar tidak dobel.</div>

                    @if ($candidate->wishlistItems->isEmpty())
                        <div class="py-12 text-center text-sm text-taupe">Wishlist belum tersedia.</div>
                    @else
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($candidate->wishlistItems as $item)
                                <div class="rounded-md border border-border bg-white p-5">
                                    <div class="text-[15px] font-semibold text-maroon-dark">{{ $item->nama }}</div>
                                    <div class="mt-1.5 text-[13px] text-taupe">{{ $item->progressLabel() }}</div>
                                    @if (! $item->isUnlimited())
                                        <div class="mt-2.5 h-1.5 overflow-hidden rounded-full bg-chip">
                                            <div class="h-full {{ $item->isFulfilled() ? 'bg-green' : 'bg-gold' }}" style="width: {{ min(100, (int) round(($item->givenQty() / max(1, $item->qty)) * 100)) }}%"></div>
                                        </div>
                                    @endif

                                    @if ($item->isFulfilled())
                                        <div class="mt-3.5 text-[13px] font-semibold text-green">&check; Terpenuhi &mdash; terima kasih!</div>
                                    @else
                                        <a href="{{ route('public.profile', $candidate).'?tab=wishlist&give='.$item->id }}" class="mt-3.5 block w-full rounded-sm bg-maroon py-2.5 text-center text-[13px] font-semibold text-cream">Saya Ingin Memberi</a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        </div>

        @if ($giveItem)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-[#2b241f]/50 p-5">
                <div class="w-full max-w-sm rounded-lg bg-cream p-8">
                    <div class="font-serif text-xl font-semibold text-maroon-dark">{{ $giveItem->nama }}</div>
                    <div class="mb-5 mt-1.5 text-[13px] text-taupe">{{ $giveItem->progressLabel() }}</div>

                    <form method="POST" action="{{ route('wishlist.claim', $giveItem) }}">
                        @csrf
                        <label class="mb-1.5 block text-[13px] font-semibold text-maroon-dark">Nama Anda</label>
                        <input type="text" name="nama" required placeholder="Nama Anda" value="{{ old('nama') }}"
                               class="mb-4 w-full rounded-sm border border-border px-3 py-2.5 text-sm">
                        @error('nama')<div class="-mt-3 mb-4 text-xs text-red">{{ $message }}</div>@enderror

                        <label class="mb-1.5 block text-[13px] font-semibold text-maroon-dark">Jumlah yang ingin diberikan</label>
                        <input type="number" name="qty" min="1" value="{{ old('qty', 1) }}"
                               class="mb-6 w-full rounded-sm border border-border px-3 py-2.5 text-sm">
                        @error('qty')<div class="-mt-5 mb-4 text-xs text-red">{{ $message }}</div>@enderror

                        <div class="flex gap-2.5">
                            <a href="{{ route('public.profile', $candidate).'?tab=wishlist' }}" class="flex-1 rounded-sm border border-border py-2.5 text-center text-sm font-semibold text-taupe">Batal</a>
                            <button type="submit" class="flex-1 rounded-sm bg-maroon py-2.5 text-sm font-semibold text-cream">Konfirmasi</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
