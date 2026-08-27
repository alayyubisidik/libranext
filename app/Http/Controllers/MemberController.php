<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AlertService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $sort   = $request->input('sort', 'latest');

        $members = User::role('member')
            ->with('media')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('member_code', 'like', "%{$search}%");
                });
            })
            ->when($status, fn ($query) => $query->where('member_status', $status))
            ->when($sort === 'name_asc',  fn ($query) => $query->orderBy('name', 'asc'))
            ->when($sort === 'name_desc', fn ($query) => $query->orderBy('name', 'desc'))
            ->when($sort === 'oldest',    fn ($query) => $query->oldest())
            ->when($sort === 'latest' || !$sort, fn ($query) => $query->latest())
            ->paginate(10)
            ->withQueryString();

        return view('dashboard.members.index', compact('members'));
    }

    public function create()
    {
        return view('dashboard.members.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone'         => ['nullable', 'string', 'max:20'],
            'address'       => ['nullable', 'string'],
            'date_of_birth' => ['nullable', 'date'],
            'member_status' => ['required', 'in:active,inactive'],
            'avatar'        => ['nullable', 'image', 'max:2048'], // Max 2MB
        ]);

        $memberCode = $this->generateUniqueMemberCode();

        $member = User::create([
            'name'          => $validated['name'],
            'email'         => $validated['email'],
            'password'      => Hash::make('password'), // Default password
            'member_code'   => $memberCode,
            'phone'         => $validated['phone'],
            'address'       => $validated['address'],
            'date_of_birth' => $validated['date_of_birth'],
            'member_status' => $validated['member_status'],
            'joined_at'     => now(),
        ]);

        $member->assignRole('member');

        if ($request->hasFile('avatar')) {
            $member->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }

        AlertService::created('Member created successfully. Default password is "password".');

        return to_route('dashboard.members.index');
    }

    public function show(User $member)
    {
        if (!$member->hasRole('member')) {
            abort(404);
        }

        $member->loadMissing(['media', 'borrowings.book', 'borrowings.fine', 'payments']);

        $totalBorrowings    = $member->borrowings->count();
        $currentlyBorrowed  = $member->borrowings->where('status', 'borrowed')->count();
        $returned           = $member->borrowings->where('status', 'returned')->count();
        $overdue            = $member->borrowings->filter(fn ($b) => $b->status === 'borrowed' && $b->due_date->isPast())->count();
        $outstandingFines   = $member->borrowings->flatMap->fine->whereNotNull('id')->whereIn('status', ['unpaid', 'partial'])->sum('amount');

        return view('dashboard.members.show', compact('member', 'totalBorrowings', 'currentlyBorrowed', 'returned', 'overdue', 'outstandingFines'));
    }

    public function edit(User $member)
    {
        if (!$member->hasRole('member')) {
            abort(404);
        }

        return view('dashboard.members.edit', compact('member'));
    }

    public function update(Request $request, User $member)
    {
        if (!$member->hasRole('member')) {
            abort(404);
        }

        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($member->id)],
            'phone'         => ['nullable', 'string', 'max:20'],
            'address'       => ['nullable', 'string'],
            'date_of_birth' => ['nullable', 'date'],
            'member_status' => ['required', 'in:active,inactive'],
            'avatar'        => ['nullable', 'image', 'max:2048'],
            'remove_avatar' => ['nullable', 'boolean'],
        ]);

        $member->update([
            'name'          => $validated['name'],
            'email'         => $validated['email'],
            'phone'         => $validated['phone'],
            'address'       => $validated['address'],
            'date_of_birth' => $validated['date_of_birth'],
            'member_status' => $validated['member_status'],
        ]);

        if ($request->boolean('remove_avatar')) {
            $member->clearMediaCollection('avatar');
        } elseif ($request->hasFile('avatar')) {
            $member->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }

        AlertService::updated('Member updated successfully');

        return to_route('dashboard.members.index');
    }

    public function destroy(User $member)
    {
        if (!$member->hasRole('member')) {
            abort(404);
        }

        if ($member->borrowings()->where('status', 'borrowed')->exists()) {
            AlertService::error('Cannot delete member because they have active borrowings.');
            return back();
        }

        if ($member->payments()->where('status', 'pending')->exists()) {
            AlertService::error('Cannot delete member because they have pending payments.');
            return back();
        }

        $member->delete();

        AlertService::deleted('Member deleted successfully');

        return to_route('dashboard.members.index');
    }

    private function generateUniqueMemberCode(): string
    {
        do {
            $code = 'MBR-' . strtoupper(Str::random(6));
        } while (User::where('member_code', $code)->exists());

        return $code;
    }
}
