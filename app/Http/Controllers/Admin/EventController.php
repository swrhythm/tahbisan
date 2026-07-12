<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'date' => ['required', 'date'],
            'jam' => ['nullable', 'string', 'max:255'],
            'lokasi' => ['nullable', 'string', 'max:255'],
        ]);

        Event::create($data);

        return redirect()->route('admin.dashboard', ['tab' => 'manage'])
            ->with('toast', 'Event dibuat.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();

        return redirect()->route('admin.dashboard', ['tab' => 'manage'])
            ->with('toast', 'Event dihapus.');
    }
}
