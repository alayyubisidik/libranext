<?php

namespace App\Exports;

use App\Models\Fine;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FineExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;
    protected $status;

    public function __construct($startDate = null, $endDate = null, $status = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
    }

    public function collection()
    {
        $query = Fine::with(['borrowing.user', 'borrowing.book']);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }

        if ($this->status) {
            $query->where('payment_status', $this->status);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Borrow Code',
            'Member Name',
            'Book Title',
            'Late Days',
            'Fine Amount',
            'Payment Status',
            'Created At'
        ];
    }

    public function map($fine): array
    {
        return [
            $fine->borrowing->borrow_code,
            $fine->borrowing->user->name,
            $fine->borrowing->book->title,
            $fine->late_days,
            'Rp ' . number_format($fine->amount, 0, ',', '.'),
            ucfirst($fine->payment_status),
            $fine->created_at->format('Y-m-d H:i:s')
        ];
    }
}
