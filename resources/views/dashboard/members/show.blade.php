@extends('dashboard.layouts.app')

@section('title', 'Member Profile')

@section('content')

<div class="mb-6">
    <a href="{{ route('dashboard.members.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Members
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Profile Card -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-blue-600 h-24"></div>
            <div class="px-6 pb-6 relative">
                <div class="-mt-12 mb-4 flex justify-center">
                    @if($member->hasMedia('avatar'))
                        <img src="{{ $member->getFirstMediaUrl('avatar') }}" alt="{{ $member->name }}" class="h-24 w-24 object-cover rounded-full border-4 border-white shadow-sm bg-white">
                    @else
                        <div class="h-24 w-24 bg-blue-100 flex items-center justify-center rounded-full border-4 border-white shadow-sm text-blue-600 font-bold text-2xl">
                            {{ substr($member->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                
                <div class="text-center">
                    <h2 class="text-xl font-bold text-gray-900">{{ $member->name }}</h2>
                    <p class="text-sm text-gray-500 font-mono mt-1">{{ $member->member_code }}</p>
                    
                    <div class="mt-3">
                        @if($member->member_status === 'active')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active Member
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Inactive Member
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="mt-6 border-t border-gray-100 pt-6 space-y-4">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</p>
                        <div class="mt-2 space-y-2 text-sm text-gray-900">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                {{ $member->email }}
                            </div>
                            @if($member->phone)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                {{ $member->phone }}
                            </div>
                            @endif
                            @if($member->date_of_birth)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ $member->date_of_birth->format('d M Y') }}
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    @if($member->address)
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Address</p>
                        <p class="mt-2 text-sm text-gray-900">{{ $member->address }}</p>
                    </div>
                    @endif
                    
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">System</p>
                        <p class="mt-2 text-sm text-gray-900">Joined on {{ $member->joined_at?->format('d M Y') }}</p>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-100 flex gap-3">
                    <a href="{{ route('dashboard.members.edit', $member) }}" class="flex-1 flex justify-center items-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Details -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center gap-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Active Borrowings</p>
                    <h3 class="text-xl font-bold text-gray-900">{{ $member->borrowings->where('status', 'borrowed')->count() }}</h3>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center gap-4">
                <div class="p-3 bg-orange-50 text-orange-600 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Unpaid Fines</p>
                    @php
                        $unpaidFines = App\Models\Fine::where('user_id', $member->id)->where('status', 'unpaid')->sum('amount');
                    @endphp
                    <h3 class="text-xl font-bold text-gray-900">Rp{{ number_format($unpaidFines, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <!-- Borrowing History -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                <h3 class="text-base font-semibold text-gray-900">Borrowing History</h3>
                <span class="text-sm text-gray-500">Total: {{ $member->borrowings->count() }}</span>
            </div>
            
            @if($member->borrowings->isEmpty())
                <div class="p-6 text-center text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <p class="text-sm">No borrowing history.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Book</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Borrow Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($member->borrowings->sortByDesc('created_at') as $borrowing)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $borrowing->book->title ?? '-' }}</div>
                                    <div class="text-xs text-gray-500 font-mono">{{ $borrowing->borrow_code }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $borrowing->borrow_date->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $borrowing->due_date->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($borrowing->status === 'borrowed')
                                        @if($borrowing->due_date->isPast())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Overdue</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Borrowed</span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Returned</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>
</div>

@endsection
