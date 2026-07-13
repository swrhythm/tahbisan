<?php

namespace App\Http\Controllers\Frater;

use App\Http\Controllers\Controller;
use App\Models\ScheduleItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleItemController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        Auth::guard('frater')->user()->scheduleItems()->create($data);

        return redirect()->route('frater.dashboard', ['tab' => 'informasi'])
            ->with('toast', 'Jadwal disimpan.');
    }

    public function update(Request $request, ScheduleItem $scheduleItem): RedirectResponse
    {
        abort_unless((int) $scheduleItem->candidate_id === (int) Auth::guard('frater')->id(), 403);

        $scheduleItem->update($this->validated($request));

        return redirect()->route('frater.dashboard', ['tab' => 'informasi'])
            ->with('toast', 'Jadwal disimpan.');
    }

    public function destroy(ScheduleItem $scheduleItem): RedirectResponse
    {
        abort_unless((int) $scheduleItem->candidate_id === (int) Auth::guard('frater')->id(), 403);

        $scheduleItem->delete();

        return redirect()->route('frater.dashboard', ['tab' => 'informasi'])
            ->with('toast', 'Jadwal dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'tanggal' => ['required', 'date'],
            'jam' => ['nullable', 'date_format:H:i'],
            'acara' => ['required', 'string', 'max:255'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'catatan' => ['nullable', 'string'],
        ]);
    }
}
