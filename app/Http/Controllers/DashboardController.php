<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Fine;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (user()->hasRole('admin')) {
            $today = today();
            $lowStockThreshold = 2;

            $stats = [
                'total_members'      => User::role('member')->count(),
                'active_members'     => User::role('member')->where('member_status', 'active')->count(),
                'total_books'        => Book::count(),
                'available_books'    => Book::sum('stock'),
                'active_borrowings'  => Borrowing::whereIn('status', ['pending', 'borrowed', 'overdue'])->count(),
                'overdue_borrowings' => Borrowing::where('status', 'overdue')->count(),
            ];

            $needsAttention = [
                'pending_borrowings'  => Borrowing::where('status', 'pending')->count(),
                'overdue_borrowings'  => $stats['overdue_borrowings'],
                'unpaid_fines_count'  => Fine::where('status', 'unpaid')->count(),
                'unpaid_fines_amount' => Fine::where('status', 'unpaid')->sum('amount'),
                'low_stock_books'     => Book::where('stock', '<=', $lowStockThreshold)->count(),
            ];

            $todaySummary = [
                'new_members'    => User::role('member')->whereDate('joined_at', $today)->count(),
                'new_borrowings' => Borrowing::whereDate('borrow_date', $today)->count(),
                'books_returned' => Borrowing::whereDate('returned_at', $today)->count(),
                'new_fines'      => Fine::whereDate('created_at', $today)->count(),
                'library_visits' => Attendance::whereDate('check_in_at', $today)->count(),
            ];

            return view('dashboard.index', compact('stats', 'needsAttention', 'todaySummary'));
        }

        // Member dashboard logic
        $user = user();
        
        $memberStats = [
            'active_borrowings' => Borrowing::where('user_id', $user->id)->where('status', 'borrowed')->count(),
            'unpaid_fines'      => Fine::whereHas('borrowing', function($query) use ($user) {
                                        $query->where('user_id', $user->id);
                                     })->where('status', 'unpaid')->sum('amount'),
            'total_fines'       => Fine::whereHas('borrowing', function($query) use ($user) {
                                        $query->where('user_id', $user->id);
                                     })->sum('amount'),
            'total_fines_paid'  => Fine::whereHas('borrowing', function($query) use ($user) {
                                        $query->where('user_id', $user->id);
                                     })->where('status', 'paid')->sum('amount'),
        ];

        $activeBorrowings = Borrowing::with('book')
            ->where('user_id', $user->id)
            ->where('status', 'borrowed')
            ->orderBy('due_date', 'asc')
            ->get();

        $borrowingHistory = Borrowing::with('book')
            ->where('user_id', $user->id)
            ->orderBy('borrow_date', 'desc')
            ->take(5)
            ->get();

        $unpaidFines = Fine::with('borrowing.book')
            ->whereHas('borrowing', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('status', 'unpaid')
            ->get();

        $fineHistory = Fine::with(['borrowing.book', 'payments'])
            ->whereHas('borrowing', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->get();

        return view('dashboard.index', compact('memberStats', 'activeBorrowings', 'borrowingHistory', 'unpaidFines', 'fineHistory', 'user'));
    }
}
