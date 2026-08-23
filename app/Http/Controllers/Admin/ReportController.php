<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Fine;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Exports\BorrowingExport;
use App\Exports\ReturningExport;
use App\Exports\FineExport;
use App\Exports\PaymentExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function borrowings(Request $request)
    {
        $query = Borrowing::with(['user', 'book']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('borrow_date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $borrowings = $query->latest()->paginate(10);

        return view('dashboard.reports.borrowings', compact('borrowings'));
    }

    public function returnings(Request $request)
    {
        $query = Borrowing::with(['user', 'book', 'processedBy'])
            ->where('status', 'returned');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('returned_at', [$request->start_date, $request->end_date]);
        }

        $returnings = $query->latest('returned_at')->paginate(10);

        return view('dashboard.reports.returnings', compact('returnings'));
    }

    public function fines(Request $request)
    {
        $query = Fine::with(['borrowing.user', 'borrowing.book']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        $fines = $query->latest()->paginate(10);

        return view('dashboard.reports.fines', compact('fines'));
    }

    public function payments(Request $request)
    {
        $query = Payment::with(['fine.borrowing.user', 'fine.borrowing.book', 'user']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('method')) {
            $query->where('payment_method', $request->method);
        }

        $payments = $query->latest()->paginate(10);

        return view('dashboard.reports.payments', compact('payments'));
    }

    public function exportBorrowings(Request $request)
    {
        $type = $request->get('type', 'excel');

        if ($type === 'pdf') {
            $query = Borrowing::with(['user', 'book']);
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('borrow_date', [$request->start_date, $request->end_date]);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            $borrowings = $query->latest()->get();

            $pdf = Pdf::loadView('dashboard.reports.pdf.borrowings', compact('borrowings'));
            return $pdf->download('borrowings-report.pdf');
        }

        return Excel::download(new BorrowingExport($request->start_date, $request->end_date, $request->status), 'borrowings-report.xlsx');
    }

    public function exportReturnings(Request $request)
    {
        $type = $request->get('type', 'excel');

        $query = Borrowing::with(['user', 'book', 'processedBy'])->where('status', 'returned');
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('returned_at', [$request->start_date, $request->end_date]);
        }

        if ($type === 'pdf') {
            $returnings = $query->latest('returned_at')->get();
            $pdf = Pdf::loadView('dashboard.reports.pdf.returnings', compact('returnings'));
            return $pdf->download('returnings-report.pdf');
        }

        return Excel::download(new ReturningExport($request->start_date, $request->end_date), 'returnings-report.xlsx');
    }

    public function exportFines(Request $request)
    {
        $type = $request->get('type', 'excel');

        if ($type === 'pdf') {
            $query = Fine::with(['borrowing.user', 'borrowing.book']);
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
            }
            if ($request->filled('status')) {
                $query->where('payment_status', $request->status);
            }
            $fines = $query->latest()->get();

            $pdf = Pdf::loadView('dashboard.reports.pdf.fines', compact('fines'));
            return $pdf->download('fines-report.pdf');
        }

        return Excel::download(new FineExport($request->start_date, $request->end_date, $request->status), 'fines-report.xlsx');
    }

    public function exportPayments(Request $request)
    {
        $type = $request->get('type', 'excel');

        if ($type === 'pdf') {
            $query = Payment::with(['fine.borrowing.user', 'fine.borrowing.book', 'user']);
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('method')) {
                $query->where('payment_method', $request->method);
            }
            $payments = $query->latest()->get();

            $pdf = Pdf::loadView('dashboard.reports.pdf.payments', compact('payments'));
            return $pdf->download('payments-report.pdf');
        }

        return Excel::download(new PaymentExport($request->start_date, $request->end_date, $request->status, $request->method), 'payments-report.xlsx');
    }
}
