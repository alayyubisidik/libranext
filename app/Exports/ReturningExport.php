<?php

namespace App\Exports;

use App\Models\Borrowing;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReturningExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection(): \Illuminate\Support\Collection
    {
        $query = Borrowing::with(['user', 'book', 'processedBy'])
            ->where('status', 'returned');

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('returned_at', [$this->startDate, $this->endDate]);
        }

        return $query->latest('returned_at')->get();
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
            'Processed By',
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
            $borrowing->returned_at->format('Y-m-d'),
            $borrowing->processedBy ? $borrowing->processedBy->name : '-',
        ];
    }
}
