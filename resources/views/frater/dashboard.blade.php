<x-layouts.app title="Dashboard">
    <div class="min-h-screen bg-cream">
        <x-site-header>
            <x-slot:left>
                <div class="font-serif text-xl font-semibold text-maroon-dark">{{ $candidate->displayName() }}</div>
                <div class="text-xs text-taupe">{{ $candidate->categoryLabel() }} &middot; {{ $candidate->event->dateLabel() }}</div>
            </x-slot:left>
            <a href="{{ route('public.profile', $candidate) }}" class="rounded-sm border border-maroon px-4 py-2 text-[13px] font-semibold text-maroon">Lihat Halaman Publik</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="rounded-sm border border-border px-4 py-2 text-[13px] font-semibold text-taupe">Keluar</button>
            </form>
        </x-site-header>

        <div class="mx-auto max-w-3xl px-6 pb-24 pt-8">
            <div class="mb-8 flex gap-2 border-b border-border">
                @foreach (['informasi' => 'Informasi', 'biography' => 'Biography', 'wishlist' => 'Wishlist'] as $key => $label)
                    <a href="{{ route('frater.dashboard', ['tab' => $key]) }}"
                       class="border-b-2 px-4.5 py-3 text-sm font-semibold {{ $tab === $key ? 'border-maroon text-maroon' : 'border-transparent text-taupe' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            @if ($tab === 'informasi')
                <div class="mb-7 rounded-md border border-border bg-white p-5">
                    <div class="mb-3.5 text-[13px] font-bold text-maroon-dark">{{ $editingItem ? 'Edit Jadwal' : 'Tambah Jadwal' }}</div>

                    <form method="POST" action="{{ $editingItem ? route('frater.informasi.update', $editingItem) : route('frater.informasi.store') }}">
                        @csrf
                        @if ($editingItem) @method('PUT') @endif

                        <div class="mb-3 grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <input type="text" name="tanggal" placeholder="Tanggal (mis. 12 Okt 2026)" value="{{ old('tanggal', $editingItem->tanggal ?? '') }}" class="rounded-sm border border-border px-3 py-2.5 text-sm">
                            <input type="text" name="jam" placeholder="Jam (mis. 09:00 WIB)" value="{{ old('jam', $editingItem->jam ?? '') }}" class="rounded-sm border border-border px-3 py-2.5 text-sm">
                        </div>
                        <input type="text" name="acara" placeholder="Nama Acara (mis. Misa Tahbisan)" value="{{ old('acara', $editingItem->acara ?? '') }}" class="mb-3 w-full rounded-sm border border-border px-3 py-2.5 text-sm">
                        <input type="text" name="lokasi" placeholder="Lokasi" value="{{ old('lokasi', $editingItem->lokasi ?? '') }}" class="mb-3 w-full rounded-sm border border-border px-3 py-2.5 text-sm">
                        <textarea name="catatan" placeholder="Catatan (opsional)" rows="2" class="mb-3.5 w-full resize-y rounded-sm border border-border px-3 py-2.5 text-sm">{{ old('catatan', $editingItem->catatan ?? '') }}</textarea>

                        @foreach (['tanggal', 'jam', 'acara', 'lokasi', 'catatan'] as $field)
                            @error($field)<div class="mb-2 text-xs text-red">{{ $message }}</div>@enderror
                        @endforeach

                        <div class="flex gap-2.5">
                            <button type="submit" class="rounded-sm bg-maroon px-4.5 py-2.5 text-[13px] font-semibold text-cream">{{ $editingItem ? 'Update' : 'Tambah' }}</button>
                            @if ($editingItem)
                                <a href="{{ route('frater.dashboard', ['tab' => 'informasi']) }}" class="rounded-sm border border-border px-4.5 py-2.5 text-[13px] font-semibold text-taupe">Batal</a>
                            @endif
                        </div>
                    </form>
                </div>

                @foreach ($candidate->scheduleItems as $item)
                    <div class="flex items-start justify-between gap-4 border-b border-chip py-4">
                        <div>
                            <div class="text-sm font-semibold text-maroon-dark">{{ $item->acara }}</div>
                            <div class="mt-0.5 text-xs font-semibold text-maroon">{{ $item->tanggal }} &middot; {{ $item->jam }}</div>
                            <div class="mt-0.5 text-[13px] text-taupe">{{ $item->lokasi }}</div>
                        </div>
                        <div class="flex flex-shrink-0 gap-2">
                            <a href="{{ route('frater.dashboard', ['tab' => 'informasi', 'edit' => $item->id]) }}" class="rounded-sm border border-border px-3 py-1.5 text-xs font-semibold text-maroon">Edit</a>
                            <form method="POST" action="{{ route('frater.informasi.destroy', $item) }}" data-confirm="Hapus jadwal ini?">
                                @csrf @method('DELETE')
                                <button type="submit" class="rounded-sm border border-border px-3 py-1.5 text-xs font-semibold text-red">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @elseif ($tab === 'biography')
                <form method="POST" action="{{ route('frater.biography.update') }}">
                    @csrf
                    <textarea name="biography" placeholder="Ceritakan perjalanan panggilan Anda hingga tahbisan..." rows="12"
                              class="mb-4 w-full resize-y rounded-md border border-border px-4 py-4 font-serif text-base leading-relaxed">{{ old('biography', $candidate->biography) }}</textarea>
                    <button type="submit" class="rounded-sm bg-maroon px-5.5 py-2.5 text-[13px] font-semibold text-cream">Simpan Biography</button>
                </form>
            @else
                <div class="mb-7 rounded-md border border-border bg-white p-5">
                    <div class="mb-3.5 text-[13px] font-bold text-maroon-dark">Tambah Barang</div>
                    <form method="POST" action="{{ route('frater.wishlist.store') }}">
                        @csrf
                        <div class="mb-3.5 grid grid-cols-1 gap-3 sm:grid-cols-[1fr_140px]">
                            <input type="text" name="nama" placeholder="Nama barang" value="{{ old('nama') }}" class="rounded-sm border border-border px-3 py-2.5 text-sm">
                            <input type="number" name="qty" min="0" placeholder="Qty (0=tanpa batas)" value="{{ old('qty', 0) }}" class="rounded-sm border border-border px-3 py-2.5 text-sm">
                        </div>
                        <div class="mb-3.5 text-xs text-taupe">Isi Qty 0 jika boleh diberikan oleh siapapun tanpa batas jumlah.</div>
                        <button type="submit" class="rounded-sm bg-maroon px-4.5 py-2.5 text-[13px] font-semibold text-cream">Tambah</button>
                    </form>
                </div>

                @foreach ($candidate->wishlistItems as $item)
                    @php $given = $item->givenQty(); @endphp
                    <div class="mb-3.5 rounded-md border border-border bg-white p-4.5">
                        <div class="flex items-center justify-between">
                            <div class="text-[15px] font-semibold text-maroon-dark">{{ $item->nama }}</div>
                            <div class="flex items-center gap-3">
                                <div class="text-xs font-semibold text-taupe">{{ $item->isUnlimited() ? "{$given} diberikan · tanpa batas" : "{$given} / {$item->qty}" }}</div>
                                <form method="POST" action="{{ route('frater.wishlist.destroy', $item) }}" data-confirm="Hapus barang ini?">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="rounded-sm border border-border px-2.5 py-1 text-xs font-semibold text-red">Hapus</button>
                                </form>
                            </div>
                        </div>
                        @if ($item->claims->isEmpty())
                            <div class="mt-2.5 text-xs italic text-gold">Belum ada yang memberi.</div>
                        @else
                            <div class="mt-3 border-t border-chip pt-3">
                                @foreach ($item->claims as $claim)
                                    <div class="flex justify-between py-1 text-[13px] text-taupe">
                                        <span>{{ $claim->nama }}</span>
                                        <span class="font-semibold text-maroon-dark">x{{ $claim->qty }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</x-layouts.app>
