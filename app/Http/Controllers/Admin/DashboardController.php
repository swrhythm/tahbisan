<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $request->query('tab') === 'manage' ? 'manage' : 'create';

        $events = Event::with('candidates.wishlistItems.claims')
            ->orderBy('date')
            ->get();

        $formEventId = $request->query('form_event');

        return view('admin.dashboard', [
            'tab' => $tab,
            'events' => $events,
            'formEventId' => $formEventId ? (int) $formEventId : null,
        ]);
    }
}
