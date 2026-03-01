<?php

namespace App\Http\Controllers;

use App\Models\MahalDonation;
use App\Models\Home;
use App\Models\Book;
use App\Models\Account;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahalDonationController extends Controller
{
    public function index(Request $request)
    {
        $query = MahalDonation::with(['home', 'book', 'account', 'category', 'creator']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('donor_name', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%")
                  ->orWhereHas('home', function ($hq) use ($s) {
                      $hq->where('home_number', 'like', "%{$s}%")
                         ->orWhere('owner_name', 'like', "%{$s}%");
                  });
            });
        }

        $donations = $query->latest('date')->get();
        $totalAmount = MahalDonation::sum('amount');
        $thisMonthAmount = MahalDonation::whereMonth('date', now()->month)->whereYear('date', now()->year)->sum('amount');
        $donorsCount = MahalDonation::distinct('home_id')->count('home_id');

        return view('mahal.donations.index', compact('donations', 'totalAmount', 'thisMonthAmount', 'donorsCount'));
    }

    public function create()
    {
        $homes = Home::active()->orderBy('home_number')->get();
        $books = Book::all();
        $accounts = Account::all();
        $categories = Category::where('type', 'income')->get();
        return view('mahal.donations.create', compact('homes', 'books', 'accounts', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'nullable|exists:books,id',
            'account_id' => 'nullable|exists:accounts,id',
            'category_id' => 'nullable|exists:categories,id',
            'home_id' => 'nullable|exists:homes,id',
            'amount' => 'required|numeric|min:0.01|max:99999999',
            'date' => 'required|date',
            'donor_name' => 'nullable|string|max:255',
            'payment_method' => 'required|string|in:Cash,Bank Transfer',
            'description' => 'nullable|string|max:500',
        ]);

        $validated['created_by'] = Auth::id();

        // Auto-generate receipt number from book
        if (!empty($validated['book_id'])) {
            $book = Book::findOrFail($validated['book_id']);
            $validated['receipt_no'] = $book->incrementReceiptNo();
        }

        // If home is selected and no donor_name given, use home owner name
        if (!empty($validated['home_id']) && empty($validated['donor_name'])) {
            $home = Home::find($validated['home_id']);
            $validated['donor_name'] = $home ? $home->owner_name : null;
        }

        MahalDonation::create($validated);

        if ($request->has('add_another')) {
            return redirect()->route('mahal.donations.create', [
                'book_id' => $validated['book_id'] ?? null,
                'account_id' => $validated['account_id'] ?? null,
                'category_id' => $validated['category_id'] ?? null,
            ])->with('success', 'Donation of ₹' . number_format($validated['amount'], 2) . ' recorded! Add another.');
        }

        return redirect()->route('mahal.donations.index')->with('success', 'Donation recorded successfully.');
    }

    public function destroy(MahalDonation $donation)
    {
        $donation->delete();
        return redirect()->route('mahal.donations.index')->with('success', 'Donation deleted.');
    }
}
