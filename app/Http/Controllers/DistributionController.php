<?php

namespace App\Http\Controllers;

use App\Models\DistributionEvent;
use App\Models\DistributionRecord;
use App\Models\Home;
use Illuminate\Http\Request;

class DistributionController extends Controller
{
    public function index()
    {
        $events = DistributionEvent::withCount([
            'records',
            'records as tokens_given_count' => fn($q) => $q->where('token_given', true),
            'records as collected_count' => fn($q) => $q->where('collected', true),
        ])->latest('event_date')->get();

        return view('mahal.distributions.index', compact('events'));
    }

    public function create()
    {
        $activeHomesCount = Home::active()->count();
        return view('mahal.distributions.create', compact('activeHomesCount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'event_date' => 'required|date',
            'items_description' => 'nullable|string|max:500',
            'status' => 'required|in:planned,active,completed',
        ]);

        $event = DistributionEvent::create($validated);

        // Create a record for every active home
        $activeHomes = Home::active()->get();
        foreach ($activeHomes as $home) {
            DistributionRecord::create([
                'distribution_event_id' => $event->id,
                'home_id' => $home->id,
            ]);
        }

        return redirect()->route('mahal.distributions.show', $event)->with('success', 'Distribution event created with ' . $activeHomes->count() . ' homes.');
    }

    public function show(DistributionEvent $distribution)
    {
        $records = $distribution->records()->with('home')->get()->sortBy(function ($r) {
            return intval(preg_replace('/\D/', '', $r->home->home_number));
        });

        $totalHomes = $records->count();
        $tokensGiven = $records->where('token_given', true)->count();
        $collected = $records->where('collected', true)->count();

        return view('mahal.distributions.show', compact('distribution', 'records', 'totalHomes', 'tokensGiven', 'collected'));
    }

    public function toggleTokenGiven(DistributionRecord $record)
    {
        $record->update([
            'token_given' => !$record->token_given,
            'token_given_at' => !$record->token_given ? now() : null,
        ]);

        return response()->json([
            'success' => true,
            'token_given' => $record->token_given,
            'token_given_at' => $record->token_given_at?->format('d M Y h:i A'),
        ]);
    }

    public function toggleCollected(DistributionRecord $record)
    {
        $record->update([
            'collected' => !$record->collected,
            'collected_at' => !$record->collected ? now() : null,
        ]);

        return response()->json([
            'success' => true,
            'collected' => $record->collected,
            'collected_at' => $record->collected_at?->format('d M Y h:i A'),
        ]);
    }

    public function updateStatus(Request $request, DistributionEvent $distribution)
    {
        $request->validate(['status' => 'required|in:planned,active,completed']);
        $distribution->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Event status updated to ' . $request->status . '.');
    }
}
