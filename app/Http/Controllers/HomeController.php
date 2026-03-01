<?php

namespace App\Http\Controllers;

use App\Models\Home;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Home::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('home_number', 'like', "%{$s}%")
                  ->orWhere('owner_name', 'like', "%{$s}%")
                  ->orWhere('contact_number', 'like', "%{$s}%")
                  ->orWhere('address', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $homes = $query->orderBy('home_number')->get();
        $totalHomes = Home::count();
        $activeHomes = Home::active()->count();

        return view('mahal.homes.index', compact('homes', 'totalHomes', 'activeHomes'));
    }

    public function create()
    {
        return view('mahal.homes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'home_number' => 'required|string|max:50|unique:homes,home_number',
            'owner_name' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'members_count' => 'nullable|integer|min:1',
            'address' => 'nullable|string|max:500',
        ]);

        Home::create($validated);

        if ($request->has('add_another')) {
            return redirect()->route('mahal.homes.create')->with('success', 'Home #' . $validated['home_number'] . ' added! Add another below.');
        }

        return redirect()->route('mahal.homes.index')->with('success', 'Home #' . $validated['home_number'] . ' added successfully.');
    }

    public function edit(Home $home)
    {
        return view('mahal.homes.edit', compact('home'));
    }

    public function update(Request $request, Home $home)
    {
        $validated = $request->validate([
            'home_number' => 'required|string|max:50|unique:homes,home_number,' . $home->id,
            'owner_name' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'members_count' => 'nullable|integer|min:1',
            'address' => 'nullable|string|max:500',
        ]);

        $home->update($validated);
        return redirect()->route('mahal.homes.index')->with('success', 'Home #' . $home->home_number . ' updated.');
    }

    public function destroy(Home $home)
    {
        $number = $home->home_number;
        $home->delete();
        return redirect()->route('mahal.homes.index')->with('success', 'Home #' . $number . ' deleted.');
    }

    public function toggleActive(Home $home)
    {
        $home->update(['is_active' => !$home->is_active]);
        $status = $home->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', 'Home #' . $home->home_number . ' ' . $status . '.');
    }
}
