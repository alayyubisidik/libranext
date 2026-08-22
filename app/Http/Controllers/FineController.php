<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use App\Services\AlertService;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Payment;
use Midtrans\Config;
use Midtrans\Snap;

class FineController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status'); // Default show all

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

        DB::transaction(function () use ($fine) {
            Payment::create([
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

    public function midtransCallback(Request $request)
    {
        $orderId = $request->input('order_id');
        $transactionStatus = $request->input('transaction_status');
        $fraudStatus = $request->input('fraud_status');

        $payment = Payment::where('order_id', $orderId)->first();

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        DB::transaction(function () use ($transactionStatus, $fraudStatus, $payment) {
            if ($transactionStatus === 'capture') {
                if ($fraudStatus === 'accept') {
                    $this->markPaymentAsPaid($payment);
                }
            } elseif ($transactionStatus === 'settlement') {
                $this->markPaymentAsPaid($payment);
            }
        });

        return response()->json(['message' => 'OK']);
    }

    private function markPaymentAsPaid(Payment $payment)
    {
        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        $fine = $payment->fine;
        if ($fine && $fine->status !== 'paid') {
            $fine->update(['status' => 'paid']);
        }
    }

    public function payMidtrans(Fine $fine)
    {
        // Check if member is paying their own fine or if it's admin
        if (!user()->hasRole('admin') && $fine->borrowing->user_id !== user()->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($fine->status !== 'unpaid') {
            AlertService::error('Fine is already paid or waived.');
            return back();
        }

        if ($fine->amount <= 0) {
            AlertService::error('Fine amount is zero.');
            return back();
        }

        $fine->loadMissing('borrowing.user');

        // Configure Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $orderId = 'MID-' . time() . '-' . $fine->id;

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $fine->amount,
            ],
            'customer_details' => [
                'first_name' => $fine->borrowing->user->name,
                'email' => $fine->borrowing->user->email,
            ],
            'item_details' => [
                [
                    'id' => $fine->id,
                    'price' => (int) $fine->amount,
                    'quantity' => 1,
                    'name' => 'Fine for overdue book: ' . $fine->borrowing->book->title,
                ]
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            DB::transaction(function () use ($fine, $orderId) {
                Payment::create([
                    'fine_id' => $fine->id,
                    'user_id' => $fine->borrowing->user_id,
                    'order_id' => $orderId,
                    'method' => 'midtrans',
                    'amount' => $fine->amount,
                    'status' => 'pending',
                ]);
            });

            return view('dashboard.fines.midtrans', compact('fine', 'snapToken'));

        } catch (\Exception $e) {
            AlertService::error('Failed to initiate Midtrans payment: ' . $e->getMessage());
            return back();
        }
    }
}
