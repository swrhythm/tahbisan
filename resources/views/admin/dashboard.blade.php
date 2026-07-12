<x-layouts.app title="Admin">
    <div class="min-h-screen bg-cream">
        <x-site-header>
            <x-slot:left>
                <div class="font-serif text-xl font-semibold text-maroon-dark">Admin</div>
            </x-slot:left>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="rounded-sm border border-border px-4 py-2 text-[13px] font-semibold text-taupe">Keluar</button>
            </form>
        </x-site-header>

        <div class="mx-auto max-w-4xl px-6 pb-24 pt-8">
            <div class="mb-8 flex gap-2 border-b border-border">
                <a href="{{ route('admin.dashboard', ['tab' => 'create']) }}"
                   class="border-b-2 px-4.5 py-3 text-sm font-semibold {{ $tab === 'create' ? 'border-maroon text-maroon' : 'border-transparent text-taupe' }}">Buat Event</a>
                <a href="{{ route('admin.dashboard', ['tab' => 'manage']) }}"
                   class="border-b-2 px-4.5 py-3 text-sm font-semibold {{ $tab === 'manage' ? 'border-maroon text-maroon' : 'border-transparent text-taupe' }}">Kelola Event &amp; Akun</a>
            </div>

            @if ($tab === 'create')
                <div class="max-w-md rounded-md border border-border bg-white p-6">
                    <form method="POST" action="{{ route('admin.events.store') }}">
                        @csrf
                        <label class="mb-1.5 block text-[13px] font-semibold text-maroon-dark">Tanggal Tahbisan</label>
                        <input type="date" name="date" value="{{ old('date') }}" class="mb-1.5 w-full rounded-sm border border-border px-3 py-2.5 text-sm">
                        @error('date')<div class="mb-2.5 text-xs text-red">{{ $message }}</div>@enderror

                        <label class="mb-1.5 mt-2.5 block text-[13px] font-semibold text-maroon-dark">Jam</label>
                        <input type="text" name="jam" placeholder="mis. 09.00 WIB" value="{{ old('jam') }}" class="mb-4 w-full rounded-sm border border-border px-3 py-2.5 text-sm">

                        <label class="mb-1.5 block text-[13px] font-semibold text-maroon-dark">Lokasi</label>
                        <input type="text" name="lokasi" placeholder="mis. Gereja Katedral, Jakarta" value="{{ old('lokasi') }}" class="mb-5 w-full rounded-sm border border-border px-3 py-2.5 text-sm">

                        <button type="submit" class="rounded-sm bg-maroon px-5.5 py-2.5 text-[13px] font-semibold text-cream">Buat Event</button>
                    </form>
                </div>
            @else
                @if ($events->isEmpty())
                    <div class="py-12 text-center text-sm text-taupe">Belum ada event.</div>
                @else
                    @foreach ($events as $event)
                        <div class="mb-4 rounded-md border border-border bg-white p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="font-serif text-xl font-semibold text-maroon-dark">{{ $event->dateLabel() }}</div>
                                    <div class="mt-0.5 text-[13px] text-taupe">{{ $event->jam }} &middot; {{ $event->lokasi }}</div>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.dashboard', ['tab' => 'manage', 'form_event' => $event->id]) }}" class="rounded-sm bg-maroon px-3.5 py-2 text-xs font-semibold text-cream">Tambah Calon</a>
                                    <form method="POST" action="{{ route('admin.events.destroy', $event) }}" data-confirm="Hapus event ini beserta seluruh calon di dalamnya?">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="rounded-sm border border-border px-3.5 py-2 text-xs font-semibold text-red">Hapus</button>
                                    </form>
                                </div>
                            </div>

                            @if ($formEventId === $event->id)
                                <div class="mt-4 grid grid-cols-1 gap-2.5 border-t border-chip pt-4 sm:grid-cols-[1fr_1fr_1fr_auto] sm:items-end">
                                    <form method="POST" action="{{ route('admin.candidates.store') }}" class="contents">
                                        @csrf
                                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                                        <div>
                                            <div class="mb-1 text-xs font-semibold text-maroon-dark">Kategori</div>
                                            <select name="category" class="w-full rounded-sm border border-border bg-white px-2.5 py-2 text-[13px]">
                                                <option value="diakon">Calon Diakon</option>
                                                <option value="imam">Calon Imam</option>
                                            </select>
                                        </div>
                                        <div>
                                            <div class="mb-1 text-xs font-semibold text-maroon-dark">Nama</div>
                                            <input type="text" name="name" placeholder="Nama lengkap" class="w-full rounded-sm border border-border px-2.5 py-2 text-[13px]">
                                        </div>
                                        <div>
                                            <div class="mb-1 text-xs font-semibold text-maroon-dark">Password</div>
                                            <input type="text" name="password" placeholder="Password" class="w-full rounded-sm border border-border px-2.5 py-2 text-[13px]">
                                        </div>
                                        <button type="submit" class="rounded-sm bg-maroon px-4 py-2.5 text-xs font-semibold text-cream">Simpan</button>
                                    </form>
                                </div>
                                @if ($errors->any())
                                    <div class="mt-2 text-xs text-red">{{ $errors->first() }}</div>
                                @endif
                            @endif

                            @if ($event->candidates->isEmpty())
                                <div class="mt-3.5 text-[13px] italic text-taupe">Belum ada calon terdaftar.</div>
                            @else
                                <div class="mt-4 flex flex-col gap-3.5 border-t border-chip pt-4">
                                    @foreach ($event->candidates as $candidate)
                                        <div class="rounded-md border border-chip bg-cream p-3.5">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <span class="rounded-full bg-chip px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-maroon">{{ $candidate->categoryLabel() }}</span>
                                                    <span class="ml-2.5 text-sm font-semibold text-maroon-dark">{{ $candidate->displayName() }}</span>
                                                </div>
                                                <div class="flex gap-2">
                                                    <a href="{{ route('public.profile', $candidate) }}" class="rounded-sm border border-maroon px-3 py-1.5 text-xs font-semibold text-maroon">Lihat Publik</a>
                                                    <form method="POST" action="{{ route('admin.candidates.destroy', $candidate) }}" data-confirm="Hapus calon {{ $candidate->name }}?">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="rounded-sm border border-border px-3 py-1.5 text-xs font-semibold text-red">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>

                                            @if ($candidate->wishlistItems->isNotEmpty())
                                                <div class="mt-3 border-t border-[#eadfc8] pt-3">
                                                    <div class="mb-2 text-[11px] font-bold uppercase tracking-wide text-taupe">Wishlist &mdash; Detail Pemberi</div>
                                                    @foreach ($candidate->wishlistItems as $item)
                                                        @php $given = $item->givenQty(); @endphp
                                                        <div class="border-b border-chip py-1.5">
                                                            <div class="flex justify-between text-xs font-semibold text-maroon-dark">
                                                                <span>{{ $item->nama }}</span>
                                                                <span class="font-medium text-taupe">{{ $item->isUnlimited() ? "{$given} diberikan · tanpa batas" : "{$given} / {$item->qty}" }}</span>
                                                            </div>
                                                            @foreach ($item->claims as $claim)
                                                                <div class="mt-0.5 pl-3 text-[11px] text-taupe">{{ $claim->nama }} &mdash; x{{ $claim->qty }}</div>
                                                            @endforeach
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif
            @endif
        </div>
    </div>
</x-layouts.app>
