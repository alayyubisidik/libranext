@extends('dashboard.layouts.app')

@section('title', 'Overview')

@section('content')

@if(user()->hasRole('admin'))
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
        @foreach([
            ['label' => 'Total Members', 'value' => $stats['total_members'], 'color' => 'blue', 'url' => route('dashboard.members.index')],
            ['label' => 'Active Members', 'value' => $stats['active_members'], 'color' => 'green', 'url' => route('dashboard.members.index', ['status' => 'active'])],
            ['label' => 'Total Books', 'value' => $stats['total_books'], 'color' => 'indigo', 'url' => route('dashboard.books.index')],
            ['label' => 'Available Books', 'value' => $stats['available_books'], 'color' => 'purple', 'url' => route('dashboard.books.index', ['stock' => 'in_stock'])],
            ['label' => 'Active Borrowings', 'value' => $stats['active_borrowings'], 'color' => 'emerald', 'url' => route('dashboard.borrowings.index', ['status' => 'borrowed'])],
            ['label' => 'Overdue Borrowings', 'value' => $stats['overdue_borrowings'], 'color' => 'red', 'url' => route('dashboard.borrowings.index', ['status' => 'overdue'])],
        ] as $card)
            <a href="{{ $card['url'] }}" class="block bg-white rounded-xl shadow-sm border border-gray-200 p-6 transition duration-200 hover:-translate-y-1 hover:shadow-lg hover:border-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <p class="text-sm font-medium text-gray-500">{{ $card['label'] }}</p>
                <h3 class="text-3xl font-bold mt-2 {{ $card['color'] === 'red' ? 'text-red-600' : 'text-gray-900' }}">{{ number_format($card['value'] ?? 0) }}</h3>
            </a>
        @endforeach
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-red-50">
            <h2 class="text-lg font-semibold text-red-700">Needs Attention</h2>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            @if($needsAttention['pending_borrowings'] || $needsAttention['overdue_borrowings'] || $needsAttention['unpaid_fines_count'] || $needsAttention['low_stock_books'])
                <a href="{{ route('dashboard.borrowings.index', ['status' => 'pending']) }}" class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                    <p class="text-sm text-gray-500">Pending Borrowings</p>
                    <p class="text-2xl font-bold text-orange-600">{{ number_format($needsAttention['pending_borrowings']) }}</p>
                </a>
                <a href="{{ route('dashboard.borrowings.index', ['status' => 'overdue']) }}" class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                    <p class="text-sm text-gray-500">Overdue Borrowings</p>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($needsAttention['overdue_borrowings']) }}</p>
                </a>
                <a href="{{ route('dashboard.fines.index', ['status' => 'unpaid']) }}" class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                    <p class="text-sm text-gray-500">Unpaid Fines</p>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($needsAttention['unpaid_fines_count']) }}</p>
                    <p class="text-xs text-gray-500">Rp{{ number_format($needsAttention['unpaid_fines_amount'], 0, ',', '.') }}</p>
                </a>
                <a href="{{ route('dashboard.books.index', ['stock' => 'low_stock']) }}" class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                    <p class="text-sm text-gray-500">Low Stock Books</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ number_format($needsAttention['low_stock_books']) }}</p>
                </a>
            @else
                <div class="md:col-span-2 xl:col-span-4 text-center text-gray-500 py-4">Everything looks good.</div>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900">Today's Summary</h2>
        </div>
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            @foreach([
                ['label' => 'New Members', 'value' => $todaySummary['new_members'], 'url' => route('dashboard.members.index')],
                ['label' => 'New Borrowings', 'value' => $todaySummary['new_borrowings'], 'url' => route('dashboard.borrowings.index')],
                ['label' => 'Books Returned', 'value' => $todaySummary['books_returned'], 'url' => route('dashboard.borrowings.index', ['status' => 'returned'])],
                ['label' => 'New Fines', 'value' => $todaySummary['new_fines'], 'url' => route('dashboard.fines.index')],
                ['label' => 'Library Visits', 'value' => $todaySummary['library_visits'], 'url' => route('dashboard.attendances.index')],
            ] as $item)
                <a href="{{ $item['url'] }}" class="block border border-gray-200 rounded-lg p-4 transition duration-200 hover:-translate-y-1 hover:shadow-md hover:border-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <p class="text-sm text-gray-500">{{ $item['label'] }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($item['value'] ?? 0) }}</p>
                </a>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900">Quick Actions</h2>
        </div>
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @role('admin')
                <a href="{{ route('dashboard.books.create') }}" class="px-4 py-3 rounded-lg bg-blue-600 text-white text-sm font-medium text-center hover:bg-blue-700">Add Book</a>
                <a href="{{ route('dashboard.members.create') }}" class="px-4 py-3 rounded-lg bg-blue-600 text-white text-sm font-medium text-center hover:bg-blue-700">Add Member</a>
                <a href="{{ route('dashboard.borrowings.create') }}" class="px-4 py-3 rounded-lg bg-blue-600 text-white text-sm font-medium text-center hover:bg-blue-700">Process Borrowing</a>
                <a href="{{ route('dashboard.borrowings.index', ['status' => 'borrowed']) }}" class="px-4 py-3 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium text-center hover:bg-gray-200">Process Return</a>
                <a href="{{ route('dashboard.borrowings.index', ['status' => 'overdue']) }}" class="px-4 py-3 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium text-center hover:bg-gray-200">View Overdue</a>
                <a href="{{ route('dashboard.fines.index') }}" class="px-4 py-3 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium text-center hover:bg-gray-200">View Fines</a>
            @endrole
        </div>
    </div>
@else
    <!-- Member Dashboard -->
    <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center gap-4">
        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="h-16 w-16 object-cover rounded-full border border-gray-200">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Welcome, {{ $user->name }}!</h2>
            <p class="text-sm text-gray-500 font-mono mt-1">{{ $user->member_code }} • {{ ucfirst($user->member_status) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center gap-4">
            <div class="p-3 bg-green-50 text-green-600 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Paid Fines</p>
                <h3 class="text-2xl font-bold text-gray-900">Rp{{ number_format($memberStats['total_fines_paid'], 0, ',', '.') }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center gap-4">
            <div class="p-3 bg-red-50 text-red-600 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total All Fines</p>
                <h3 class="text-2xl font-bold text-gray-900">Rp{{ number_format($memberStats['total_fines'], 0, ',', '.') }}</h3>
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

        <!-- Fine History -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden lg:col-span-2">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-base font-semibold text-gray-900">Fine History</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Book</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Overdue Days</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid At</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($fineHistory as $fine)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $fine->borrowing->book->title }}</div>
                                <div class="text-xs text-gray-500 font-mono">{{ $fine->borrowing->borrow_code }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $fine->overdue_days }} days
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-red-600">
                                Rp{{ number_format($fine->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($fine->status === 'paid')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Paid</span>
                                @elseif($fine->status === 'waived')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Waived</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Unpaid</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                @if($fine->status === 'paid' && $fine->payments->isNotEmpty())
                                    {{ $fine->payments->where('status', 'paid')->sortByDesc('paid_at')->first()?->paid_at?->format('d M Y') ?? '-' }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500 text-sm">
                                No fine records found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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
