<?php

namespace App\Http\Controllers;

use App\Models\Home;
use App\Models\MahalDonation;
use App\Models\DistributionEvent;
use Illuminate\Http\Request;

class MahalDashboardController extends Controller
{
    public function index()
    {
        $totalHomes = Home::count();
        $activeHomes = Home::active()->count();
        $totalDonations = MahalDonation::sum('amount');
        $thisMonthDonations = MahalDonation::whereMonth('date', now()->month)->whereYear('date', now()->year)->sum('amount');
        $activeEvents = DistributionEvent::where('status', 'active')->count();
        $recentDonations = MahalDonation::with(['home', 'creator'])->latest()->take(5)->get();
        $upcomingEvents = DistributionEvent::where('status', '!=', 'completed')
            ->orderBy('event_date')
            ->take(3)
            ->get();

        return view('mahal.dashboard', compact(
            'totalHomes', 'activeHomes', 'totalDonations',
            'thisMonthDonations', 'activeEvents', 'recentDonations', 'upcomingEvents'
        ));
    }
}
