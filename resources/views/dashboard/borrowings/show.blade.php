@extends('dashboard.layouts.app')

@section('title', 'Borrowing Detail')

@section('content')

<div class="mb-6">
    <a href="{{ route('dashboard.borrowings.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Borrowings
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Main Info -->
    <div class="lg:col-span-2 space-y-6">

        <!-- Borrowing Info Card -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900">Borrowing Information</h3>
                <div class="flex items-center gap-3">
                    @if($borrowing->status === 'borrowed')
                        @if($borrowing->due_date->isPast())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Overdue</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Borrowed</span>
                        @endif
                        <form action="{{ route('dashboard.borrowings.return', $borrowing) }}" method="POST" onsubmit="return confirm('Confirm return of this book?')" novalidate>
                            @csrf
                            <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-lg shadow-sm text-xs font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Mark as Returned
                            </button>
                        </form>
                    @elseif($borrowing->status === 'pending')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                        <form action="{{ route('dashboard.borrowings.confirm', $borrowing) }}" method="POST" onsubmit="return confirm('Confirm this borrowing request?')" novalidate>
                            @csrf
                            <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-lg shadow-sm text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Confirm Request
                            </button>
                        </form>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Returned</span>
                    @endif
                </div>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Borrow Code</p>
                    <p class="mt-1 text-sm font-mono font-semibold text-gray-900">{{ $borrowing->borrow_code }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Borrow Date</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $borrowing->borrow_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</p>
                    <p class="mt-1 text-sm text-gray-900 {{ $borrowing->status === 'borrowed' && $borrowing->due_date->isPast() ? 'text-red-600 font-semibold' : '' }}">
                        {{ $borrowing->due_date->format('d M Y') }}
                        @if($borrowing->status === 'borrowed' && $borrowing->due_date->isPast())
                            <span class="text-xs font-normal">({{ now()->startOfDay()->diffInDays($borrowing->due_date->startOfDay()) }} days overdue)</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Returned At</p>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ $borrowing->returned_at ? $borrowing->returned_at->format('d M Y, H:i') : '—' }}
                    </p>
                </div>
                @if($borrowing->processedBy)
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Processed By</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $borrowing->processedBy->name }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Fine Info -->
        @if($borrowing->fine)
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-base font-semibold text-gray-900">Fine Information</h3>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Overdue Days</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ $borrowing->fine->overdue_days }} days</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Rate / Day</p>
                    <p class="mt-1 text-sm text-gray-900">Rp{{ number_format($borrowing->fine->rate_per_day, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Fine</p>
                    <p class="mt-1 text-sm font-bold text-red-600">Rp{{ number_format($borrowing->fine->amount, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Fine Status</p>
                    <p class="mt-1">
                        @if($borrowing->fine->status === 'paid')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Paid</span>
                        @elseif($borrowing->fine->status === 'waived')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Waived</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Unpaid</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        @endif

    </div>

    <!-- Sidebar: Member & Book -->
    <div class="space-y-6">

        <!-- Member -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-base font-semibold text-gray-900">Member</h3>
            </div>
            <div class="p-6 flex items-center gap-4">
                @if($borrowing->user->hasMedia('avatar'))
                    <img src="{{ $borrowing->user->getFirstMediaUrl('avatar') }}" alt="{{ $borrowing->user->name }}" class="h-12 w-12 object-cover rounded-full border border-gray-200">
                @else
                    <div class="h-12 w-12 bg-blue-100 flex items-center justify-center rounded-full border border-blue-200 text-blue-600 font-bold">
                        {{ substr($borrowing->user->name, 0, 1) }}
                    </div>
                @endif
                <div>
                    <p class="text-sm font-semibold text-gray-900">{{ $borrowing->user->name }}</p>
                    <p class="text-xs text-gray-500 font-mono">{{ $borrowing->user->member_code }}</p>
                    <p class="text-xs text-gray-500">{{ $borrowing->user->email }}</p>
                </div>
            </div>
            <div class="px-6 pb-4">
                <a href="{{ route('dashboard.members.show', $borrowing->user) }}" class="text-sm text-blue-600 hover:text-blue-800">View profile →</a>
            </div>
        </div>

        <!-- Book -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-base font-semibold text-gray-900">Book</h3>
            </div>
            <div class="p-6 flex items-start gap-4">
                @if($borrowing->book->hasMedia('cover'))
                    <img src="{{ $borrowing->book->getFirstMediaUrl('cover') }}" alt="{{ $borrowing->book->title }}" class="h-20 w-14 object-cover rounded border border-gray-200 flex-shrink-0">
                @else
                    <div class="h-20 w-14 bg-gray-100 flex items-center justify-center rounded border border-gray-200 flex-shrink-0 text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                @endif
                <div>
                    <p class="text-sm font-semibold text-gray-900">{{ $borrowing->book->title }}</p>
                    <p class="text-xs text-gray-500">{{ $borrowing->book->author }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $borrowing->book->category->name ?? '—' }}</p>
                    @if($borrowing->book->isbn)
                        <p class="text-xs text-gray-400 font-mono mt-1">ISBN: {{ $borrowing->book->isbn }}</p>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
