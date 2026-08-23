<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaymentExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;
    protected $status;
    protected $method;

    public function __construct($startDate = null, $endDate = null, $status = null, $method = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
        $this->method = $method;
    }

    public function collection(): \Illuminate\Support\Collection
    {
        $query = Payment::with(['fine.borrowing.user', 'fine.borrowing.book', 'user']);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }
        
        if ($this->method) {
            $query->where('payment_method', $this->method);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'Member Name',
            'Borrow Code',
            'Amount',
            'Payment Method',
            'Status',
            'Processed By',
            'Paid At'
        ];
    }

    public function map($payment): array
    {
        return [
            $payment->order_id,
            $payment->fine->borrowing->user->name,
            $payment->fine->borrowing->borrow_code,
            'Rp ' . number_format($payment->amount, 0, ',', '.'),
            ucfirst($payment->payment_method),
            ucfirst($payment->status),
            $payment->user ? $payment->user->name : '-',
            $payment->paid_at ? $payment->paid_at->format('Y-m-d H:i:s') : '-'
        ];
    }
}
