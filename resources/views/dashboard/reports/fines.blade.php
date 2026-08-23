@extends('dashboard.layouts.app')

@section('title', 'Fines Report')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Fines Report</h1>
    <p class="text-sm text-gray-500 mt-1">View and export fine data.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-6 border-b border-gray-200">
        <form action="{{ route('dashboard.reports.fines') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="block rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="block rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                <select name="status" id="status" class="block rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    <option value="">All Statuses</option>
                    <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">Filter</button>
                <a href="{{ route('dashboard.reports.fines') }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">Clear</a>
            </div>
        </form>
    </div>

    <div class="p-4 border-b border-gray-200 bg-gray-50 flex gap-2">
        <form action="{{ route('dashboard.reports.fines.export') }}" method="GET" class="flex gap-2">
            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <button type="submit" name="type" value="excel" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">Export Excel</button>
            <button type="submit" name="type" value="pdf" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">Export PDF</button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Borrow Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Book</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Late Days</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($fines as $fine)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $fine->borrowing->borrow_code }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $fine->borrowing->user->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $fine->borrowing->book->title }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $fine->late_days }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">Rp {{ number_format($fine->amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-sm">
                            @if($fine->payment_status === 'paid')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Unpaid</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $fine->created_at->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-sm text-gray-400">No fines found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4">
        {{ $fines->links() }}
    </div>
</div>
@endsection
