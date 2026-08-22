<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Notification;

class WebhookController extends Controller
{
    public function midtrans(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        try {
            $notification = new Notification();
        } catch (\Exception $e) {
            // Log the error for debugging
            \Illuminate\Support\Facades\Log::error('Midtrans Webhook Error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed processing notification'], 400);
        }

        $transaction = $notification->transaction_status;
        $type = $notification->payment_type;
        $orderId = $notification->order_id;
        $fraud = $notification->fraud_status;

        $payment = Payment::where('order_id', $orderId)->first();

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        DB::transaction(function () use ($transaction, $type, $fraud, $payment, $notification) {
            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $payment->update([
                            'status' => 'pending',
                            'payment_type' => $type,
                            'raw_response' => json_decode(json_encode($notification), true)
                        ]);
                    } else {
                        $this->markAsPaid($payment, $type, $notification);
                    }
                }
            } elseif ($transaction == 'settlement') {
                $this->markAsPaid($payment, $type, $notification);
            } elseif ($transaction == 'pending') {
                $payment->update([
                    'status' => 'pending',
                    'payment_type' => $type,
                    'raw_response' => json_decode(json_encode($notification), true)
                ]);
            } elseif ($transaction == 'deny') {
                $this->markAsFailed($payment, 'failed', $type, $notification);
            } elseif ($transaction == 'expire') {
                $this->markAsFailed($payment, 'expired', $type, $notification);
            } elseif ($transaction == 'cancel') {
                $this->markAsFailed($payment, 'cancelled', $type, $notification);
            }
        });

        return response()->json(['message' => 'Notification processed successfully']);
    }

    private function markAsPaid($payment, $type, $notification)
    {
        $payment->update([
            'status' => 'paid',
            'payment_type' => $type,
            'transaction_id' => $notification->transaction_id ?? null,
            'paid_at' => now(),
            'raw_response' => json_decode(json_encode($notification), true)
        ]);

        $fine = $payment->fine;
        if ($fine && $fine->status !== 'paid') {
            $fine->update([
                'status' => 'paid'
            ]);
        }
    }

    private function markAsFailed($payment, $status, $type, $notification)
    {
        $payment->update([
            'status' => $status,
            'payment_type' => $type,
            'transaction_id' => $notification->transaction_id ?? null,
            'raw_response' => json_decode(json_encode($notification), true)
        ]);
    }
}
