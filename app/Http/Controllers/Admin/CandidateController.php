<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'event_id' => ['required', 'integer', 'exists:events,id'],
            'category' => ['required', 'in:diakon,imam'],
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:4'],
        ]);

        $event = Event::findOrFail($data['event_id']);

        $event->candidates()->create([
            'category' => $data['category'],
            'name' => $data['name'],
            'password' => $data['password'],
        ]);

        return redirect()->route('admin.dashboard', ['tab' => 'manage'])
            ->with('toast', 'Calon & akun dibuat.');
    }

    public function destroy(Candidate $candidate): RedirectResponse
    {
        $candidate->delete();

        return redirect()->route('admin.dashboard', ['tab' => 'manage'])
            ->with('toast', 'Calon dihapus.');
    }
}
