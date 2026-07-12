<?php

namespace App\Http\Controllers\Frater;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        /** @var Candidate $candidate */
        $candidate = Auth::guard('frater')->user();
        $candidate->load(['event', 'scheduleItems', 'wishlistItems.claims']);

        $tab = in_array($request->query('tab'), ['informasi', 'biography', 'wishlist'], true)
            ? $request->query('tab')
            : 'informasi';

        $editingId = $request->query('edit');
        $editingItem = $editingId
            ? $candidate->scheduleItems->firstWhere('id', (int) $editingId)
            : null;

        return view('frater.dashboard', [
            'candidate' => $candidate,
            'tab' => $tab,
            'editingItem' => $editingItem,
        ]);
    }
}
