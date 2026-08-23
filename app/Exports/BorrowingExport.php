<?php

namespace App\Exports;

use App\Models\Borrowing;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BorrowingExport implements FromCollection, WithHeadings, WithMapping
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
        $query = Borrowing::with(['user', 'book']);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('borrow_date', [$this->startDate, $this->endDate]);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Borrow Code',
            'Member Name',
            'Member Code',
            'Book Title',
            'Borrow Date',
            'Due Date',
            'Return Date',
            'Status'
        ];
    }

    public function map($borrowing): array
    {
        return [
            $borrowing->borrow_code,
            $borrowing->user->name,
            $borrowing->user->member_code,
            $borrowing->book->title,
            $borrowing->borrow_date->format('Y-m-d'),
            $borrowing->due_date->format('Y-m-d'),
            $borrowing->returned_at ? $borrowing->returned_at->format('Y-m-d') : '-',
            ucfirst($borrowing->status)
        ];
    }
}
