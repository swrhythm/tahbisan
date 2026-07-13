<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventScheduleItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EventScheduleItemController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'event_id' => ['required', 'integer', 'exists:events,id'],
            'tanggal' => ['required', 'date'],
            'jam' => ['nullable', 'date_format:H:i'],
            'acara' => ['required', 'string', 'max:255'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'catatan' => ['nullable', 'string'],
        ]);

        $event = Event::findOrFail($data['event_id']);
        $event->scheduleItems()->create($data);

        return redirect()->route('admin.dashboard', ['tab' => 'manage', 'schedule_event' => $event->id])
            ->with('toast', 'Jadwal bersama ditambahkan.');
    }

    public function destroy(EventScheduleItem $eventScheduleItem): RedirectResponse
    {
        $eventId = $eventScheduleItem->event_id;
        $eventScheduleItem->delete();

        return redirect()->route('admin.dashboard', ['tab' => 'manage', 'schedule_event' => $eventId])
            ->with('toast', 'Jadwal bersama dihapus.');
    }
}
