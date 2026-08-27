<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        // Validate date range
        if ($dateFrom && $dateTo && $dateFrom > $dateTo) {
            return redirect()->route('dashboard.attendances.index')->with('error', 'Date From cannot be greater than Date To');
        }

        // Summary Data
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $todaysVisits = Attendance::whereDate('check_in_at', $today)->count();
        $thisMonthVisits = Attendance::whereBetween('check_in_at', [$startOfMonth, $endOfMonth])->count();
        $uniqueMembersToday = Attendance::whereDate('check_in_at', $today)->distinct('user_id')->count('user_id');

        $attendances = Attendance::with('user')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('member_code', 'like', "%{$search}%");
                });
            })
            ->when($dateFrom, function ($query) use ($dateFrom) {
                $query->where('check_in_at', '>=', Carbon::parse($dateFrom)->startOfDay());
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                $query->where('check_in_at', '<=', Carbon::parse($dateTo)->endOfDay());
            })
            ->latest('check_in_at')
            ->paginate(15)
            ->withQueryString();

        return view('dashboard.attendances.index', compact(
            'attendances', 'todaysVisits', 'thisMonthVisits', 'uniqueMembersToday'
        ));
    }
}
