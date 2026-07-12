<x-layouts.app title="Masuk">
    <div class="flex min-h-screen flex-col">
        <div class="px-6 py-5 sm:px-12">
            <a href="{{ route('public.landing') }}" class="text-sm font-semibold text-maroon">&larr; Kembali</a>
        </div>

        <div class="flex flex-1 items-center justify-center px-6">
            <div class="w-full max-w-sm">
                <div class="mb-7 text-center font-serif text-3xl font-semibold text-maroon-dark">Masuk</div>

                <div class="mb-7 flex overflow-hidden rounded-md border border-border">
                    <a href="{{ route('login', ['tab' => 'frater']) }}"
                       class="flex-1 py-3 text-center text-[13px] font-semibold {{ $tab === 'frater' ? 'bg-maroon text-cream' : 'bg-white text-taupe' }}">Frater / Diakon</a>
                    <a href="{{ route('login', ['tab' => 'admin']) }}"
                       class="flex-1 py-3 text-center text-[13px] font-semibold {{ $tab === 'admin' ? 'bg-maroon text-cream' : 'bg-white text-taupe' }}">Admin</a>
                </div>

                @if ($tab === 'frater')
                    <form method="POST" action="{{ route('login.frater') }}">
                        @csrf
                        <label class="mb-1.5 block text-[13px] font-semibold text-maroon-dark">Pilih Nama</label>
                        <select name="candidate_id" class="mb-4 w-full rounded-sm border border-border bg-white px-3 py-2.5 text-sm">
                            <option value="">&mdash; Pilih &mdash;</option>
                            @foreach ($candidates as $c)
                                <option value="{{ $c->id }}" @selected(old('candidate_id') == $c->id)>{{ $c->displayName() }} ({{ $c->categoryLabel() }})</option>
                            @endforeach
                        </select>

                        <label class="mb-1.5 block text-[13px] font-semibold text-maroon-dark">Password</label>
                        <input type="password" name="password" placeholder="Password"
                               class="mb-2 w-full rounded-sm border border-border px-3 py-2.5 text-sm">

                        @if (session('loginError'))
                            <div class="mb-3 text-[13px] text-red">{{ session('loginError') }}</div>
                        @endif

                        <button type="submit" class="mt-2 w-full rounded-sm bg-maroon py-3 text-sm font-semibold text-cream">Masuk</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('login.admin') }}">
                        @csrf
                        <label class="mb-1.5 block text-[13px] font-semibold text-maroon-dark">Password Admin</label>
                        <input type="password" name="password" placeholder="Password"
                               class="mb-1.5 w-full rounded-sm border border-border px-3 py-2.5 text-sm">

                        @if (session('adminError'))
                            <div class="mb-3 text-[13px] text-red">{{ session('adminError') }}</div>
                        @endif

                        <button type="submit" class="mt-2 w-full rounded-sm bg-maroon py-3 text-sm font-semibold text-cream">Masuk</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
