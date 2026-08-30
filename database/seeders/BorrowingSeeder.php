<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Fine;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BorrowingSeeder extends Seeder
{
    public function run(): void
    {
        $admin  = User::where('email', 'admin@libranext.id')->first();
        $members = User::role('member')->get();
        $books   = Book::all();

        if ($members->isEmpty() || $books->isEmpty()) {
            return;
        }

        $scenarios = [
            // returned — on time
            ['status' => 'returned', 'borrow_offset' => -60, 'due_offset' => -53, 'return_offset' => -55, 'overdue_days' => 0],
            ['status' => 'returned', 'borrow_offset' => -45, 'due_offset' => -38, 'return_offset' => -40, 'overdue_days' => 0],
            ['status' => 'returned', 'borrow_offset' => -30, 'due_offset' => -23, 'return_offset' => -25, 'overdue_days' => 0],
            // returned — overdue with paid fine
            ['status' => 'returned', 'borrow_offset' => -50, 'due_offset' => -43, 'return_offset' => -38, 'overdue_days' => 5, 'fine_status' => 'paid'],
            ['status' => 'returned', 'borrow_offset' => -40, 'due_offset' => -33, 'return_offset' => -25, 'overdue_days' => 8, 'fine_status' => 'paid'],
            // returned — overdue with waived fine
            ['status' => 'returned', 'borrow_offset' => -35, 'due_offset' => -28, 'return_offset' => -22, 'overdue_days' => 6, 'fine_status' => 'waived'],
            // overdue — active borrow past due date, unpaid fine
            ['status' => 'overdue', 'borrow_offset' => -20, 'due_offset' => -13, 'return_offset' => null, 'overdue_days' => 7, 'fine_status' => 'unpaid'],
            ['status' => 'overdue', 'borrow_offset' => -15, 'due_offset' => -8,  'return_offset' => null, 'overdue_days' => 3, 'fine_status' => 'unpaid'],
            // borrowed — active, still within due date
            ['status' => 'borrowed', 'borrow_offset' => -5,  'due_offset' => 2,  'return_offset' => null, 'overdue_days' => 0],
            ['status' => 'borrowed', 'borrow_offset' => -3,  'due_offset' => 4,  'return_offset' => null, 'overdue_days' => 0],
            // pending — waiting approval
            ['status' => 'pending',  'borrow_offset' => -1,  'due_offset' => 6,  'return_offset' => null, 'overdue_days' => 0],
        ];

        $usedPairs = [];

        foreach ($members as $member) {
            $availableBooks = $books->shuffle();
            $count = 0;

            foreach ($scenarios as $scenario) {
                if ($count >= 3) break;

                $book = $availableBooks->first(function ($b) use ($member, $usedPairs) {
                    return !in_array($member->id . '-' . $b->id, $usedPairs);
                });

                if (!$book) continue;

                $usedPairs[] = $member->id . '-' . $book->id;
                $count++;

                $borrowDate = now()->addDays($scenario['borrow_offset'])->startOfDay();
                $dueDate    = now()->addDays($scenario['due_offset'])->startOfDay();
                $returnedAt = $scenario['return_offset'] !== null
                    ? now()->addDays($scenario['return_offset'])->setTime(rand(8, 17), rand(0, 59))
                    : null;

                $borrowing = Borrowing::create([
                    'user_id'      => $member->id,
                    'book_id'      => $book->id,
                    'processed_by' => in_array($scenario['status'], ['borrowed', 'returned', 'overdue']) ? $admin->id : null,
                    'borrow_code'  => 'BRW-' . strtoupper(Str::random(8)),
                    'borrow_date'  => $borrowDate->format('Y-m-d'),
                    'due_date'     => $dueDate->format('Y-m-d'),
                    'returned_at'  => $returnedAt,
                    'status'       => $scenario['status'],
                ]);

                if (!empty($scenario['overdue_days']) && $scenario['overdue_days'] > 0) {
                    $ratePerDay   = 500;
                    $overdueDays  = $scenario['overdue_days'];
                    $amount       = $ratePerDay * $overdueDays;
                    $fineStatus   = $scenario['fine_status'] ?? 'unpaid';

                    $fine = Fine::create([
                        'borrowing_id' => $borrowing->id,
                        'rate_per_day' => $ratePerDay,
                        'overdue_days' => $overdueDays,
                        'amount'       => $amount,
                        'status'       => $fineStatus,
                    ]);

                    if ($fineStatus === 'paid') {
                        Payment::create([
                            'fine_id'      => $fine->id,
                            'user_id'      => $member->id,
                            'order_id'     => 'ORD-' . strtoupper(Str::random(10)),
                            'method'       => 'cash',
                            'payment_type' => 'cash',
                            'amount'       => $amount,
                            'status'       => 'paid',
                            'paid_at'      => $returnedAt ?? now()->subDays(rand(1, 5)),
                            'raw_response' => [],
                        ]);
                    }
                }
            }
        }
    }
}
