@extends('dashboard.layouts.app')

@section('title', 'New Borrowing')

@section('content')

<div class="mb-6">
    <a href="{{ route('dashboard.borrowings.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Borrowings
    </a>
</div>

<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
    <form action="{{ route('dashboard.borrowings.store') }}" method="POST" class="p-6 sm:p-8" novalidate>
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <!-- Member -->
            <div>
                <label for="user_id" class="block text-sm font-medium text-gray-700">Member <span class="text-red-500">*</span></label>
                <select name="user_id" id="user_id" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-white">
                    <option value="">-- Select Member --</option>
                    @foreach($members as $member)
                        <option value="{{ $member->id }}" {{ old('user_id') == $member->id ? 'selected' : '' }}>
                            {{ $member->name }} ({{ $member->member_code }})
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                <p class="mt-1 text-xs text-gray-500">Only active members are shown.</p>
            </div>

            <!-- Book -->
            <div>
                <label for="book_id" class="block text-sm font-medium text-gray-700">Book <span class="text-red-500">*</span></label>
                <select name="book_id" id="book_id" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-white">
                    <option value="">-- Select Book --</option>
                    @foreach($books as $book)
                        <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                            {{ $book->title }} — {{ $book->author }} (Stock: {{ $book->available_stock }})
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('book_id')" class="mt-2" />
                <p class="mt-1 text-xs text-gray-500">Only active books with available stock are shown.</p>
            </div>

            <!-- Borrow Date -->
            <div>
                <label for="borrow_date" class="block text-sm font-medium text-gray-700">Borrow Date <span class="text-red-500">*</span></label>
                <input type="date" name="borrow_date" id="borrow_date"
                       value="{{ old('borrow_date', now()->format('Y-m-d')) }}"
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                <x-input-error :messages="$errors->get('borrow_date')" class="mt-2" />
            </div>

            <!-- Due Date -->
            <div>
                <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date <span class="text-red-500">*</span></label>
                <input type="date" name="due_date" id="due_date"
                       value="{{ old('due_date', now()->addDays(7)->format('Y-m-d')) }}"
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
            </div>

        </div>

        <div class="mt-6 bg-blue-50 border border-blue-100 rounded-lg p-4">
            <p class="text-sm text-blue-800 flex gap-2">
                <svg class="w-5 h-5 flex-shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Members may borrow a maximum of <strong>3 books</strong> at a time. A member cannot borrow the same book twice while it is still active.
            </p>
        </div>

        <div class="pt-6 mt-6 border-t border-gray-200 flex justify-end">
            <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Create Borrowing
            </button>
        </div>
    </form>
</div>

@endsection
