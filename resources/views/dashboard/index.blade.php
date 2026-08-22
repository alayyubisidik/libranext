@extends('dashboard.layouts.app')

@section('title', 'Overview')

@section('content')

@if(user()->hasRole('admin'))
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- Total Books -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center gap-4">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Books</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_books']) }}</h3>
            </div>
        </div>

        <!-- Total Members -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center gap-4">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Members</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_members']) }}</h3>
            </div>
        </div>

        <!-- Active Borrowings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center gap-4">
            <div class="p-3 bg-green-50 text-green-600 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Active Borrowings</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['active_borrowings']) }}</h3>
            </div>
        </div>

        <!-- Overdue Borrowings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center gap-4">
            <div class="p-3 bg-red-50 text-red-600 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Overdue</p>
                <h3 class="text-2xl font-bold text-red-600">{{ number_format($stats['overdue_borrowings']) }}</h3>
            </div>
        </div>

        <!-- Available Stock -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center gap-4">
            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Available Stock</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['available_stock']) }}</h3>
            </div>
        </div>

        <!-- Unpaid Fines -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center gap-4">
            <div class="p-3 bg-orange-50 text-orange-600 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Unpaid Fines</p>
                <h3 class="text-2xl font-bold text-gray-900">Rp{{ number_format($stats['unpaid_fines'], 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
@else
    <!-- Member Dashboard -->
    <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center gap-4">
        @if($user->hasMedia('avatar'))
            <img src="{{ $user->getFirstMediaUrl('avatar') }}" alt="{{ $user->name }}" class="h-16 w-16 object-cover rounded-full border border-gray-200">
        @else
            <div class="h-16 w-16 bg-blue-100 flex items-center justify-center rounded-full border border-blue-200 text-blue-600 text-xl font-bold">
                {{ substr($user->name, 0, 1) }}
            </div>
        @endif
        <div>
            <h2 class="text-xl font-bold text-gray-900">Welcome, {{ $user->name }}!</h2>
            <p class="text-sm text-gray-500 font-mono mt-1">{{ $user->member_code }} • {{ ucfirst($user->member_status) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center gap-4">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Active Borrowings</p>
                <h3 class="text-2xl font-bold text-gray-900">{{ number_format($memberStats['active_borrowings']) }} / 3</h3>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center gap-4">
            <div class="p-3 bg-orange-50 text-orange-600 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Unpaid Fines</p>
                <h3 class="text-2xl font-bold text-gray-900">Rp{{ number_format($memberStats['unpaid_fines'], 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Active Borrowings -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="text-base font-semibold text-gray-900">Currently Borrowed</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($activeBorrowings as $borrowing)
                    <div class="p-6 flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900">{{ $borrowing->book->title }}</h4>
                            <p class="text-xs text-gray-500 font-mono mt-1">{{ $borrowing->borrow_code }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-medium text-gray-500">Due Date</p>
                            <p class="text-sm {{ $borrowing->due_date->isPast() ? 'text-red-600 font-semibold' : 'text-gray-900' }}">
                                {{ $borrowing->due_date->format('d M Y') }}
                                @if($borrowing->due_date->isPast())
                                    <span class="inline-flex items-center px-2 py-0.5 ml-2 rounded text-xs font-medium bg-red-100 text-red-800">Overdue</span>
                                @endif
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500 text-sm">
                        No active borrowings right now.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Unpaid Fines -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="text-base font-semibold text-gray-900">Unpaid Fines</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($unpaidFines as $fine)
                    <div class="p-6 flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900">{{ $fine->borrowing->book->title }}</h4>
                            <p class="text-xs text-gray-500 mt-1">Overdue: {{ $fine->overdue_days }} days</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <p class="text-base font-bold text-red-600">Rp{{ number_format($fine->amount, 0, ',', '.') }}</p>
                            <!-- Member can pay online via Midtrans if configured -->
                            <form action="{{ route('dashboard.fines.pay-midtrans', $fine) }}" method="POST" novalidate>
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                                    Pay Online
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500 text-sm">
                        No unpaid fines. Great job!
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Borrowing History -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden lg:col-span-2">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-base font-semibold text-gray-900">Recent Borrowing History</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Book</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Borrow Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Returned At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($borrowingHistory as $history)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $history->book->title }}</div>
                                <div class="text-xs text-gray-500 font-mono">{{ $history->borrow_code }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $history->borrow_date->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $history->returned_at ? $history->returned_at->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($history->status === 'borrowed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Borrowed</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Returned</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500 text-sm">
                                No borrowing history found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

@endsection
