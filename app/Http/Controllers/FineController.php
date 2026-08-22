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
                        ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('book', fn($b) => $b->where('title', 'like', "%{$search}%"));
                });
            })
            ->when($status, fn($query) => $query->where('status', $status))
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
    public function payCash(Fine $fine)
    {
        if ($fine->status !== 'unpaid') {
            AlertService::error('Fine is already paid or waived.');
            return back();
        }

        if ($fine->amount <= 0) {
            AlertService::error('Fine amount is zero.');
            return back();
        }

        $fine->loadMissing('borrowing.user');

        \Illuminate\Support\Facades\DB::transaction(function () use ($fine) {
            $payment = \App\Models\Payment::create([
                'fine_id' => $fine->id,
                'user_id' => $fine->borrowing->user_id,
                'order_id' => 'CASH-' . time() . '-' . $fine->id,
                'method' => 'cash',
                'amount' => $fine->amount,
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            $fine->update([
                'status' => 'paid'
            ]);
        });

        AlertService::success('Cash payment recorded successfully.');

        return back();
    }
}
