<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use App\Services\AlertService;
use Illuminate\Http\Request;

class FineController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status', 'unpaid'); // Default show unpaid

        $fines = Fine::with(['borrowing.user', 'borrowing.book'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('borrowing', function ($q) use ($search) {
                    $q->where('borrow_code', 'like', "%{$search}%")
                      ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                      ->orWhereHas('book', fn ($b) => $b->where('title', 'like', "%{$search}%"));
                });
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('dashboard.fines.index', compact('fines'));
    }

    public function show(Fine $fine)
    {
        $fine->loadMissing(['borrowing.user', 'borrowing.book', 'payments']);

        return view('dashboard.fines.show', compact('fine'));
    }

    public function waive(Fine $fine)
    {
        if ($fine->status !== 'unpaid') {
            AlertService::error('Only unpaid fines can be waived.');
            return back();
        }

        $fine->update([
            'status' => 'waived'
        ]);

        AlertService::updated('Fine waived successfully.');

        return back();
    }
}
