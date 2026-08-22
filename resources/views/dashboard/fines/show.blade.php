@extends('dashboard.layouts.app')

@section('title', 'Fine Detail')

@section('content')

<div class="mb-6">
    <a href="{{ route('dashboard.fines.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Fines
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 space-y-6">
        <!-- Fine Info Card -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900">Fine Details</h3>
                <div class="flex items-center gap-3">
                    @if($fine->status === 'paid')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Paid</span>
                    @elseif($fine->status === 'waived')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Waived</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Unpaid</span>
                        <form action="{{ route('dashboard.fines.waive', $fine) }}" method="POST" onsubmit="return confirm('Are you sure you want to waive this fine? This action cannot be undone.')" novalidate>
                            @csrf
                            <button type="submit" class="text-xs text-gray-600 bg-white border border-gray-300 hover:bg-gray-50 px-3 py-1.5 rounded-lg shadow-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Waive Fine
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Overdue Days</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ $fine->overdue_days }} days</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Rate / Day</p>
                    <p class="mt-1 text-sm text-gray-900">Rp{{ number_format($fine->rate_per_day, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</p>
                    <p class="mt-1 text-xl font-bold text-red-600">Rp{{ number_format($fine->amount, 0, ',', '.') }}</p>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <p class="text-xs text-gray-500 mb-2 uppercase tracking-wider font-medium">Borrowing Reference</p>
                <div class="flex items-center justify-between">
                    <div>
                        <a href="{{ route('dashboard.borrowings.show', $fine->borrowing) }}" class="text-sm font-mono font-medium text-blue-600 hover:underline">
                            {{ $fine->borrowing->borrow_code }}
                        </a>
                        <p class="text-sm text-gray-600 mt-1">Book: {{ $fine->borrowing->book->title }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payments Placeholder -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900">Payments</h3>
                @if($fine->status === 'unpaid')
                <div class="flex gap-2">
                    <form action="{{ route('dashboard.fines.pay-cash', $fine) }}" method="POST" onsubmit="return confirm('Confirm cash payment of Rp{{ number_format($fine->amount, 0, ',', '.') }}?')" novalidate>
                        @csrf
                        <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Record Cash
                        </button>
                    </form>
                   
                </div>
                @endif
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($fine->payments as $payment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 capitalize">
                                {{ $payment->method }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                Rp{{ number_format($payment->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($payment->status === 'paid')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Paid</span>
                                @elseif($payment->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 capitalize">{{ $payment->status }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $payment->paid_at ? $payment->paid_at->format('d M Y H:i') : '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                <p class="text-sm">No payments recorded.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sidebar: Member -->
    <div class="space-y-6">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-base font-semibold text-gray-900">Member</h3>
            </div>
            <div class="p-6 flex items-center gap-4">
                @if($fine->borrowing->user->hasMedia('avatar'))
                    <img src="{{ $fine->borrowing->user->getFirstMediaUrl('avatar') }}" alt="{{ $fine->borrowing->user->name }}" class="h-12 w-12 object-cover rounded-full border border-gray-200">
                @else
                    <div class="h-12 w-12 bg-blue-100 flex items-center justify-center rounded-full border border-blue-200 text-blue-600 font-bold">
                        {{ substr($fine->borrowing->user->name, 0, 1) }}
                    </div>
                @endif
                <div>
                    <p class="text-sm font-semibold text-gray-900">{{ $fine->borrowing->user->name }}</p>
                    <p class="text-xs text-gray-500 font-mono">{{ $fine->borrowing->user->member_code }}</p>
                </div>
            </div>
            <div class="px-6 pb-4">
                <a href="{{ route('dashboard.members.show', $fine->borrowing->user) }}" class="text-sm text-blue-600 hover:text-blue-800">View profile →</a>
            </div>
        </div>
    </div>
</div>

@endsection
