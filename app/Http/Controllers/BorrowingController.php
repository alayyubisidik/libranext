<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Fine;
use App\Models\User;
use App\Services\AlertService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BorrowingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $borrowings = Borrowing::with(['user', 'book', 'processedBy'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('borrow_code', 'like', "%{$search}%")
                      ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                      ->orWhereHas('book', fn ($b) => $b->where('title', 'like', "%{$search}%"));
                });
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('dashboard.borrowings.index', compact('borrowings'));
    }

    public function create()
    {
        $members = User::role('member')
            ->where('member_status', 'active')
            ->orderBy('name')
            ->get();

        $books = Book::with('category')
            ->where('status', 'active')
            ->orderBy('title')
            ->get()
            ->filter(fn ($book) => $book->available_stock > 0);

        return view('dashboard.borrowings.create', compact('members', 'books'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'     => ['required', 'exists:users,id'],
            'book_id'     => ['required', 'exists:books,id'],
            'borrow_date' => ['required', 'date'],
            'due_date'    => ['required', 'date', 'after_or_equal:borrow_date'],
        ]);

        $member = User::findOrFail($validated['user_id']);
        $book = Book::findOrFail($validated['book_id']);

        if ($member->member_status !== 'active') {
            AlertService::error('Member is inactive and cannot borrow books.');
            return back()->withInput();
        }

        if ($book->status !== 'active') {
            AlertService::error('Selected book is not available for borrowing.');
            return back()->withInput();
        }

        if ($book->available_stock <= 0) {
            AlertService::error('Selected book is out of stock.');
            return back()->withInput();
        }

        $activeBorrowingsCount = Borrowing::where('user_id', $member->id)
            ->where('status', 'borrowed')
            ->count();

        if ($activeBorrowingsCount >= 3) {
            AlertService::error('Member has reached the maximum of 3 active borrowings.');
            return back()->withInput();
        }

        $alreadyBorrowingThisBook = Borrowing::where('user_id', $member->id)
            ->where('book_id', $book->id)
            ->where('status', 'borrowed')
            ->exists();

        if ($alreadyBorrowingThisBook) {
            AlertService::error('Member is already borrowing this book.');
            return back()->withInput();
        }

        DB::transaction(function () use ($validated, $member, $book) {
            Borrowing::create([
                'user_id'     => $member->id,
                'book_id'     => $book->id,
                'borrow_code' => $this->generateUniqueBorrowCode(),
                'borrow_date' => $validated['borrow_date'],
                'due_date'    => $validated['due_date'],
                'status'      => 'borrowed',
            ]);
        });

        AlertService::created('Borrowing record created successfully.');

        return to_route('dashboard.borrowings.index');
    }

    public function show(Borrowing $borrowing)
    {
        $borrowing->loadMissing(['user', 'book.category', 'processedBy', 'fine.payments']);

        return view('dashboard.borrowings.show', compact('borrowing'));
    }

    public function returnBook(Borrowing $borrowing)
    {
        if ($borrowing->status === 'returned') {
            AlertService::error('This borrowing has already been returned.');
            return back();
        }

        DB::transaction(function () use ($borrowing) {
            $returnedAt = now();
            $overdueDays = 0;
            $fine = null;

            if ($returnedAt->gt($borrowing->due_date->endOfDay())) {
                $overdueDays = (int) $borrowing->due_date->startOfDay()->diffInDays($returnedAt->startOfDay());
            }

            $borrowing->update([
                'status'       => 'returned',
                'returned_at'  => $returnedAt,
                'processed_by' => user()->id,
            ]);

            if ($overdueDays > 0) {
                $ratePerDay = 500;
                Fine::create([
                    'borrowing_id' => $borrowing->id,
                    'rate_per_day' => $ratePerDay,
                    'overdue_days' => $overdueDays,
                    'amount'       => $overdueDays * $ratePerDay,
                    'status'       => 'unpaid',
                ]);
            }
        });

        AlertService::updated('Book returned successfully.');

        return to_route('dashboard.borrowings.show', $borrowing);
    }

    private function generateUniqueBorrowCode(): string
    {
        do {
            $code = 'BRW-' . strtoupper(Str::random(8));
        } while (Borrowing::where('borrow_code', $code)->exists());

        return $code;
    }
}
