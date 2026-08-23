<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index()
    {
        return view('attendance.index');
    }

    public function search(Request $request)
    {
        $request->validate([
            'search' => ['required', 'string', 'min:1', 'max:100'],
        ]);

        $search = $request->string('search');

        $members = User::role('member')
            ->where('member_status', 'active')
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('member_code', 'like', "%{$search}%");
            })
            ->select('id', 'name', 'member_code', 'member_status')
            ->limit(10)
            ->get()
            ->map(function ($member) {
                $member->avatar_url = $member->getFirstMediaUrl('avatar');
                return $member;
            });

        return response()->json([
            'members' => $members,
        ]);
    }

    public function show(User $user)
    {
        abort_unless($user->hasRole('member') && $user->member_status === 'active', 404);

        $avatarUrl = $user->getFirstMediaUrl('avatar');

        return view('attendance.show', compact('user', 'avatarUrl'));
    }

    public function store(User $user)
    {
        abort_unless($user->hasRole('member') && $user->member_status === 'active', 404);

        Attendance::create([
            'user_id' => $user->id,
            'check_in_at' => now(),
        ]);

        activity()
            ->causedByAnonymous()
            ->performedOn($user)
            ->withProperties(['member_code' => $user->member_code])
            ->log("Member {$user->name} check-in attendance");

        return redirect()->route('attendance.success', $user);
    }

    public function success(User $user)
    {
        abort_unless($user->hasRole('member'), 404);

        return view('attendance.success', compact('user'));
    }
}
