@extends('dashboard.layouts.app')

@section('title', 'Borrow a Book')

@section('content')

<div class="mb-6">
    <a href="{{ route('dashboard.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Dashboard
    </a>
</div>

@if($activeBorrowingsCount >= 3)
<div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-5 flex items-start gap-3">
    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    <div>
        <p class="text-sm font-semibold text-red-800">Borrowing limit reached</p>
        <p class="text-sm text-red-700 mt-0.5">You already have 3 active/pending borrowings. Please return a book or cancel a pending request before borrowing another one.</p>
    </div>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Catalog Side -->
    <div class="lg:col-span-2 space-y-6">
        
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4">
            <form action="{{ route('dashboard.member.borrow.create') }}" method="GET" class="flex gap-3">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search book title..." class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                <button type="submit" class="px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700">Search</button>
            </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @forelse($books as $book)
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col">
                    <div class="p-5 flex-1">
                        @if($book->hasMedia('cover'))
                            <img src="{{ $book->getFirstMediaUrl('cover') }}" alt="{{ $book->title }}" class="w-full h-40 object-cover rounded-lg mb-4">
                        @else
                            <div class="w-full h-40 bg-gray-100 rounded-lg mb-4 flex items-center justify-center text-gray-400">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                        @endif
                        <h4 class="font-bold text-gray-900 leading-tight mb-1">{{ $book->title }}</h4>
                        <p class="text-sm text-gray-500 mb-3">{{ $book->author }}</p>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">{{ $book->category->name ?? 'Uncategorized' }}</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $book->available_stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">Stock: {{ $book->available_stock }}</span>
                        </div>
                    </div>
                    <div class="p-4 border-t border-gray-100 bg-gray-50">
                        @if($book->already_borrowed)
                            <button disabled class="w-full py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-gray-400 cursor-not-allowed">
                                Already Requested
                            </button>
                        @elseif(!$book->can_borrow)
                            <button disabled class="w-full py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-gray-400 cursor-not-allowed">
                                Out of Stock
                            </button>
                        @elseif($activeBorrowingsCount >= 3)
                            <button disabled class="w-full py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-gray-400 cursor-not-allowed">
                                Limit Reached
                            </button>
                        @else
                            <form action="{{ route('dashboard.member.borrow.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="book_id" value="{{ $book->id }}">
                                <button type="submit" class="w-full py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700">
                                    Request Borrow
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-1 sm:col-span-2 text-center py-10 bg-white border border-gray-200 rounded-xl shadow-sm text-gray-500">
                    No books found.
                </div>
            @endforelse
        </div>
        
        @if($books->hasPages())
            <div class="mt-4">
                {{ $books->links() }}
            </div>
        @endif
    </div>

    <!-- Active/Pending List Side -->
    <div class="space-y-6">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden sticky top-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900">Your Books</h3>
                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none {{ $activeBorrowingsCount >= 3 ? 'text-red-100 bg-red-600' : 'text-blue-100 bg-blue-600' }} rounded-full">{{ $activeBorrowingsCount }} / 3</span>
            </div>
            
            <div class="divide-y divide-gray-200">
                @forelse($memberBorrowings as $borrowing)
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-1">
                            <h4 class="text-sm font-semibold text-gray-900 line-clamp-1 flex-1 pr-2" title="{{ $borrowing->book->title }}">{{ $borrowing->book->title }}</h4>
                            @if($borrowing->status === 'pending')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">Pending</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-blue-100 text-blue-800 border border-blue-200">Borrowed</span>
                            @endif
                        </div>
                        <p class="text-[11px] text-gray-500 font-mono mb-2">{{ $borrowing->borrow_code }}</p>
                        
                        <div class="flex justify-between items-end">
                            <div>
                                <p class="text-[10px] uppercase tracking-wide font-semibold text-gray-400">Due Date</p>
                                <p class="text-xs font-medium {{ $borrowing->due_date->isPast() ? 'text-red-600' : 'text-gray-700' }}">{{ $borrowing->due_date->format('d M Y') }}</p>
                            </div>
                            
                            @if($borrowing->status === 'pending')
                                <form action="{{ route('dashboard.member.borrow.destroy', $borrowing) }}" method="POST" onsubmit="return confirm('Cancel this borrow request?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-medium bg-red-50 hover:bg-red-100 px-2 py-1 rounded">Cancel</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500 text-sm">
                        You don't have any active or pending books.
                    </div>
                @endforelse
            </div>
            
            <div class="bg-blue-50 p-4 border-t border-gray-200">
                <p class="text-xs text-blue-800">
                    <strong class="block mb-1">Note:</strong>
                    "Pending" requests need admin confirmation. Due date is automatically set to 7 days from request date.
                </p>
            </div>
        </div>
    </div>
</div>

@endsection