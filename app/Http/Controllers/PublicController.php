<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Event;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function landing(): View
    {
        $events = Event::withCount('candidates')
            ->orderBy('date')
            ->get();

        return view('public.landing', compact('events'));
    }

    public function event(Event $event): View
    {
        $event->load('candidates');

        return view('public.event', compact('event'));
    }

    public function profile(Request $request, Candidate $candidate): View
    {
        $candidate->load(['event.scheduleItems', 'scheduleItems', 'wishlistItems.claims']);

        $tab = in_array($request->query('tab'), ['informasi', 'biography', 'wishlist'], true)
            ? $request->query('tab')
            : 'informasi';

        $giveItemId = $request->query('give');
        $giveItem = $giveItemId
            ? $candidate->wishlistItems->firstWhere('id', (int) $giveItemId)
            : null;

        return view('public.profile', [
            'candidate' => $candidate,
            'timeline' => $candidate->timeline(),
            'tab' => $tab,
            'giveItem' => $giveItem,
        ]);
    }
}
