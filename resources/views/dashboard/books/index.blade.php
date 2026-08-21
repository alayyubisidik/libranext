@extends('dashboard.layouts.app')

@section('title', 'Books')

@section('content')

<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div class="flex-1 w-full md:w-auto">
        <form action="{{ route('dashboard.books.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1 max-w-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title, author, ISBN..." 
                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            
            <select name="category_id" class="block w-full sm:w-48 py-2 px-3 border border-gray-300 bg-white rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>

            <select name="status" class="block w-full sm:w-36 py-2 px-3 border border-gray-300 bg-white rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>

            <button type="submit" class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Filter
            </button>
            
            @if(request('search') || request('category_id') || request('status'))
                <a href="{{ route('dashboard.books.index') }}" class="inline-flex justify-center items-center px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                    Clear
                </a>
            @endif
        </form>
    </div>
    
    <div class="flex-shrink-0">
        <a href="{{ route('dashboard.books.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-full sm:w-auto">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Add Book
        </a>
    </div>
</div>

<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Cover</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Book Info</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($books as $book)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($book->hasMedia('cover'))
                            <img src="{{ $book->getFirstMediaUrl('cover') }}" alt="{{ $book->title }}" class="h-16 w-12 object-cover rounded shadow-sm border border-gray-200">
                        @else
                            <div class="h-16 w-12 bg-gray-100 flex items-center justify-center rounded border border-gray-200 text-gray-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-semibold text-gray-900">{{ $book->title }}</div>
                        <div class="text-sm text-gray-500">{{ $book->author }}</div>
                        @if($book->isbn)
                            <div class="text-xs text-gray-400 mt-1">ISBN: {{ $book->isbn }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ $book->category->name }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $book->available_stock }} available</div>
                        <div class="text-xs text-gray-500">of {{ $book->stock }} total</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($book->status === 'active')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Inactive
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('dashboard.books.edit', $book) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                        
                        <form action="{{ route('dashboard.books.destroy', $book) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this book?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <p class="text-sm">No books found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($books->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 mt-auto">
        {{ $books->links() }}
    </div>
    @endif
</div>

@endsection
