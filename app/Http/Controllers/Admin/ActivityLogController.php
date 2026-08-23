<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $logName = $request->input('log_name'); // 'default', etc
        
        // Log name is usually 'default' for models unless specified differently

        $activities = Activity::with('causer')
            ->when($search, function ($query) use ($search) {
                $query->where('description', 'like', "%{$search}%")
                      ->orWhere('subject_type', 'like', "%{$search}%");
            })
            ->when($logName, fn ($query) => $query->where('log_name', $logName))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('dashboard.activity-logs.index', compact('activities'));
    }
}
