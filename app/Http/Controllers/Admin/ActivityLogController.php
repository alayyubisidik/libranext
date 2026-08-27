<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $event = $request->input('event');
        $userId = $request->input('user_id');
        $modelType = $request->input('model_type');
        $sort = $request->input('sort', 'desc');
        
        // Get unique users who have activity logs
        $causerTypes = Activity::select('causer_type')->whereNotNull('causer_type')->distinct()->pluck('causer_type');
        
        $users = User::role(['admin', 'member'])->whereIn('id', function($query) {
             $query->select('causer_id')->from('activity_log')->whereNotNull('causer_id');
        })->get();
        
        // Get unique subject types for filter
        $subjectTypes = Activity::select('subject_type')
            ->whereNotNull('subject_type')
            ->distinct()
            ->pluck('subject_type');

        $activities = Activity::with('causer')
            ->when($search, function ($query) use ($search) {
                $query->where('description', 'like', "%{$search}%")
                      ->orWhere('subject_type', 'like', "%{$search}%")
                      ->orWhereHas('causer', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
            })
            ->when($event, fn ($query) => $query->where('event', $event))
            ->when($userId, fn ($query) => $query->where('causer_id', $userId))
            ->when($modelType, fn ($query) => $query->where('subject_type', $modelType))
            ->orderBy('created_at', $sort === 'asc' ? 'asc' : 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('dashboard.activity-logs.index', compact('activities', 'users', 'subjectTypes'));
    }
}
