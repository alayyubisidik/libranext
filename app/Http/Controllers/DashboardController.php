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

        // Member dashboard logic (if needed later)
        $memberStats = [
            'active_borrowings' => Borrowing::where('user_id', user()->id)->where('status', 'borrowed')->count(),
            'unpaid_fines'      => Fine::where('user_id', user()->id)->where('status', 'unpaid')->sum('amount'),
        ];

        return view('dashboard.index', compact('memberStats'));
    }
}
