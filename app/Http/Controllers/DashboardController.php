<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\Fine;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

            $chartDates = collect(range(6, 0))->map(fn ($daysAgo) => today()->subDays($daysAgo));
            $chartLabels = $chartDates->map(fn ($date) => $date->format('d M'));
            $chartBorrowings = $chartDates->map(fn ($date) => Borrowing::whereDate('borrow_date', $date)->count());
            $chartReturns = $chartDates->map(fn ($date) => Borrowing::whereDate('returned_at', $date)->count());
            $chartOverdue = $chartDates->map(fn ($date) => Borrowing::where('status', 'overdue')->whereDate('due_date', $date)->count());

            $overdueBorrowings = Borrowing::with(['user', 'book', 'fine'])
                ->where('status', 'overdue')
                ->orderBy('due_date')
                ->limit(5)
                ->get();

            $recentBorrowings = Borrowing::with(['user', 'book'])
                ->latest()
                ->limit(5)
                ->get();

            $mostBorrowedBooks = Book::with('category')
                ->withCount('borrowings')
                ->has('borrowings')
                ->orderByDesc('borrowings_count')
                ->limit(5)
                ->get();

            $lowStockBooks = Book::where('stock', '<=', $lowStockThreshold)
                ->orderBy('stock')
                ->limit(5)
                ->get();

            $newBooks = Book::with(['category', 'media'])
                ->latest()
                ->limit(5)
                ->get();

            $popularCategories = Category::query()
                ->select('categories.*', DB::raw('COUNT(borrowings.id) as borrowings_count'))
                ->join('books', 'books.category_id', '=', 'categories.id')
                ->join('borrowings', 'borrowings.book_id', '=', 'books.id')
                ->withCount('books')
                ->groupBy('categories.id', 'categories.name', 'categories.slug', 'categories.description', 'categories.status', 'categories.created_at', 'categories.updated_at')
                ->orderByDesc('borrowings_count')
                ->limit(5)
                ->get();

            $maxCategoryBorrowings = max((int) $popularCategories->max('borrowings_count'), 1);

            $attendanceStats = [
                'today' => Attendance::whereDate('check_in_at', $today)->count(),
                'week' => Attendance::whereBetween('check_in_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'month' => Attendance::whereBetween('check_in_at', [now()->startOfMonth(), now()->endOfMonth()])->count(),
            ];

            $attendanceChart = $chartDates->map(fn ($date) => Attendance::whereDate('check_in_at', $date)->count());

            $newMembers = User::role('member')
                ->with('media')
                ->orderByRaw('COALESCE(joined_at, created_at) DESC')
                ->limit(5)
                ->get();

            $mostActiveMembers = User::role('member')
                ->withCount('borrowings')
                ->has('borrowings')
                ->orderByDesc('borrowings_count')
                ->limit(5)
                ->get();

            $memberVisits = User::role('member')
                ->withCount('attendances')
                ->has('attendances')
                ->orderByDesc('attendances_count')
                ->limit(5)
                ->get();

            $highestOutstandingFines = User::role('member')
                ->select('users.*', DB::raw('SUM(fines.amount) as unpaid_fines_total'))
                ->join('borrowings', 'borrowings.user_id', '=', 'users.id')
                ->join('fines', 'fines.borrowing_id', '=', 'borrowings.id')
                ->where('fines.status', 'unpaid')
                ->groupBy('users.id', 'users.name', 'users.email', 'users.email_verified_at', 'users.password', 'users.member_code', 'users.phone', 'users.address', 'users.date_of_birth', 'users.member_status', 'users.joined_at', 'users.remember_token', 'users.created_at', 'users.updated_at')
                ->orderByDesc('unpaid_fines_total')
                ->limit(5)
                ->get();

            $memberStatusSummary = [
                'active' => User::role('member')->where('member_status', 'active')->count(),
                'inactive' => User::role('member')->where('member_status', 'inactive')->count(),
            ];

            return view('dashboard.index', compact(
                'stats',
                'needsAttention',
                'todaySummary',
                'chartLabels',
                'chartBorrowings',
                'chartReturns',
                'chartOverdue',
                'overdueBorrowings',
                'recentBorrowings',
                'mostBorrowedBooks',
                'lowStockBooks',
                'newBooks',
                'popularCategories',
                'maxCategoryBorrowings',
                'attendanceStats',
                'attendanceChart',
                'newMembers',
                'mostActiveMembers',
                'memberVisits',
                'highestOutstandingFines',
                'memberStatusSummary'
            ));
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
