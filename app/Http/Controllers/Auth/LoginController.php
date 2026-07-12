<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Candidate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    public function show(Request $request): View
    {
        $tab = (session('tab') ?? $request->query('tab')) === 'admin' ? 'admin' : 'frater';

        $candidates = Candidate::with('event')->orderBy('name')->get();

        return view('auth.login', compact('tab', 'candidates'));
    }

    public function frater(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'candidate_id' => ['required', 'integer'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = 'login-frater:'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return back()->withInput()->with([
                'loginError' => 'Terlalu banyak percobaan. Coba lagi dalam beberapa saat.',
                'tab' => 'frater',
            ]);
        }

        $candidate = Candidate::find($data['candidate_id']);

        if (! $candidate || ! Hash::check($data['password'], $candidate->password)) {
            RateLimiter::hit($throttleKey, 60);

            return back()->withInput()->with([
                'loginError' => 'Pilih nama dan masukkan password yang benar.',
                'tab' => 'frater',
            ]);
        }

        RateLimiter::clear($throttleKey);

        Auth::guard('frater')->login($candidate);
        $request->session()->regenerate();

        return redirect()->route('frater.dashboard');
    }

    public function admin(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'password' => ['required', 'string'],
        ]);

        $throttleKey = 'login-admin:'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return back()->with([
                'adminError' => 'Terlalu banyak percobaan. Coba lagi dalam beberapa saat.',
                'tab' => 'admin',
            ]);
        }

        $admin = Admin::all()->first(fn (Admin $a) => Hash::check($data['password'], $a->password));

        if (! $admin) {
            RateLimiter::hit($throttleKey, 60);

            return back()->with([
                'adminError' => 'Password salah.',
                'tab' => 'admin',
            ]);
        }

        RateLimiter::clear($throttleKey);

        Auth::guard('admin')->login($admin);
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('frater')->logout();
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('public.landing');
    }
}
