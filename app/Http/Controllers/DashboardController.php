<?php

namespace App\Http\Controllers;

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
            $stats = [
                'total_books'        => Book::count(),
                'total_members'      => User::role('member')->count(),
                'active_borrowings'  => Borrowing::where('status', 'borrowed')->count(),
                'overdue_borrowings' => Borrowing::where('status', 'borrowed')->where('due_date', '<', today())->count(),
                'available_stock'    => Book::sum('stock'),
                'unpaid_fines'       => Fine::where('status', 'unpaid')->sum('amount'),
            ];

            return view('dashboard.index', compact('stats'));
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
