@extends('dashboard.layouts.app')

@section('title', 'Books')

@section('content')

<div x-data="booksPage()" x-init="init()">

    {{-- Toolbar --}}
    <div class="mb-4 flex flex-col gap-3">
        <form action="{{ route('dashboard.books.index') }}" method="GET" id="filterForm">
            {{-- Row 1: Search + Add Book --}}
            <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center justify-between mb-3">
                <div class="relative flex-1 max-w-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title, author, ISBN..."
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <a href="{{ route('dashboard.books.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 whitespace-nowrap">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Add Book
                </a>
            </div>

            {{-- Row 2: Filters + Sort --}}
            <div class="flex flex-wrap gap-2 items-center">
                <select name="category_id" class="py-2 px-3 border border-gray-300 bg-white rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>

                <select name="status" onchange="this.form.submit()" class="py-2 px-3 border border-gray-300 bg-white rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                <select name="stock" class="py-2 px-3 border border-gray-300 bg-white rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">All Stock</option>
                    <option value="in_stock" {{ request('stock') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                    <option value="out_of_stock" {{ request('stock') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                </select>

                <select name="sort" class="py-2 px-3 border border-gray-300 bg-white rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Newest Added</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest Added</option>
                    <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Title A–Z</option>
                    <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Title Z–A</option>
                    <option value="stock_asc" {{ request('sort') == 'stock_asc' ? 'selected' : '' }}>Stock Lowest → Highest</option>
                    <option value="stock_desc" {{ request('sort') == 'stock_desc' ? 'selected' : '' }}>Stock Highest → Lowest</option>
                    <option value="year_desc" {{ request('sort') == 'year_desc' ? 'selected' : '' }}>Publication Year Newest → Oldest</option>
                    <option value="year_asc" {{ request('sort') == 'year_asc' ? 'selected' : '' }}>Publication Year Oldest → Newest</option>
                    <option value="status_asc" {{ request('sort') == 'status_asc' ? 'selected' : '' }}>Status Active First</option>
                    <option value="status_desc" {{ request('sort') == 'status_desc' ? 'selected' : '' }}>Status Inactive First</option>
                </select>

                <button type="submit" class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Filter
                </button>

                @if(request('search') || request('category_id') || request('status') || request('stock') || request('sort'))
                    <a href="{{ route('dashboard.books.index') }}" class="inline-flex justify-center items-center px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                        Clear
                    </a>
                @endif
            </div>
        </form>

        {{-- Bulk Action Bar --}}
        <div x-show="selectedIds.length > 0" x-cloak class="flex items-center gap-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
            <span class="text-sm text-blue-700 font-medium" x-text="selectedIds.length + ' book(s) selected'"></span>
            <div class="flex items-center gap-2 ml-auto">
                <span class="text-sm text-gray-600">Change Status:</span>
                <button @click="openBulkConfirm('active')" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg bg-green-100 text-green-800 hover:bg-green-200 border border-green-300">
                    Active
                </button>
                <button @click="openBulkConfirm('inactive')" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg bg-red-100 text-red-800 hover:bg-red-200 border border-red-300">
                    Inactive
                </button>
                <button @click="clearSelection()" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-gray-600 hover:text-gray-900">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-3 w-10">
                            <input type="checkbox" @change="toggleAll($event)" :checked="isAllSelected()"
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
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
                    @php
                        $borrowed = $book->borrowed_count ?? 0;
                        $available = $book->stock - $borrowed;
                    @endphp
                    <tr>
                        <td class="px-4 py-4">
                            <input type="checkbox" :value="{{ $book->id }}" x-model="selectedIds"
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($book->hasMedia('cover'))
                                <img src="{{ $book->getFirstMediaUrl('cover') }}" alt="{{ $book->title }}" class="h-16 w-12 object-cover rounded shadow-sm border border-gray-200">
                            @else
                                <div class="h-16 w-12 bg-gray-100 flex items-center justify-center rounded border border-gray-200 text-gray-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
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
                            <div class="text-sm font-semibold text-gray-900">{{ $available }} available</div>
                            <div class="text-xs text-gray-500">{{ $book->stock }} total · {{ $borrowed }} borrowed</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('dashboard.books.update', $book) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="category_id" value="{{ $book->category_id }}">
                                <input type="hidden" name="isbn" value="{{ $book->isbn }}">
                                <input type="hidden" name="title" value="{{ $book->title }}">
                                <input type="hidden" name="author" value="{{ $book->author }}">
                                <input type="hidden" name="publisher" value="{{ $book->publisher }}">
                                <input type="hidden" name="publication_year" value="{{ $book->publication_year }}">
                                <input type="hidden" name="stock" value="{{ $book->stock }}">
                                <input type="hidden" name="description" value="{{ $book->description }}">
                                <select name="status" onchange="this.form.submit()"
                                        class="py-1 px-2 text-xs font-medium rounded-full border focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500
                                        {{ $book->status === 'active' ? 'bg-green-100 text-green-800 border-green-300' : 'bg-red-100 text-red-800 border-red-300' }}">
                                    <option value="active" {{ $book->status === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $book->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button @click="openStockModal({{ $book->id }}, '{{ addslashes($book->title) }}', {{ $book->stock }}, {{ $available }})"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3">Stock</button>
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
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
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

    {{-- Stock Modal --}}
    <div x-show="stockModal.open" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="closeStockModal()"></div>
            <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6 z-10">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Stock Management</h3>
                    <button @click="closeStockModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm font-medium text-gray-900" x-text="stockModal.title"></p>
                    <div class="mt-2 flex gap-4 text-sm text-gray-600">
                        <span>Total Stock: <strong x-text="stockModal.stock"></strong></span>
                        <span>Available: <strong x-text="stockModal.available"></strong></span>
                    </div>
                </div>

                <form :action="'/dashboard/books/' + stockModal.bookId + '/stock'" method="POST" @submit.prevent="submitStockForm($el)">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Action</label>
                        <div class="flex gap-3">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="action" value="add" x-model="stockModal.action" class="text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-gray-700">Add Stock</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="action" value="remove" x-model="stockModal.action" class="text-red-600 focus:ring-red-500">
                                <span class="text-sm text-gray-700">Remove Stock</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                        <input type="number" name="amount" x-model="stockModal.amount" min="1" required
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <div class="flex gap-3 justify-end">
                        <button type="button" @click="closeStockModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700">
                            Update Stock
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Bulk Confirm Modal --}}
    <div x-show="bulkModal.open" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="bulkModal.open = false"></div>
            <div class="relative bg-white rounded-xl shadow-xl max-w-sm w-full p-6 z-10">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Confirm Bulk Status Change</h3>
                <p class="text-sm text-gray-600 mb-6">
                    You are about to set <strong x-text="selectedIds.length"></strong> book(s) to
                    <strong x-text="bulkModal.status"></strong>. Are you sure?
                </p>
                <div class="flex gap-3 justify-end">
                    <button @click="bulkModal.open = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button @click="submitBulkStatus()" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden bulk status form --}}
    <form id="bulkStatusForm" action="{{ route('dashboard.books.bulk-status') }}" method="POST" class="hidden">
        @csrf
        <div id="bulkIdsContainer"></div>
        <input type="hidden" name="status" id="bulkStatusInput">
    </form>

</div>

@push('scripts')
<script>
function booksPage() {
    return {
        selectedIds: [],
        allIds: @json($books->pluck('id')),
        stockModal: {
            open: false,
            bookId: null,
            title: '',
            stock: 0,
            available: 0,
            action: 'add',
            amount: 1,
        },
        bulkModal: {
            open: false,
            status: '',
        },

        init() {},

        toggleAll(event) {
            if (event.target.checked) {
                this.selectedIds = [...this.allIds];
            } else {
                this.selectedIds = [];
            }
        },

        isAllSelected() {
            return this.allIds.length > 0 && this.allIds.every(id => this.selectedIds.includes(id));
        },

        clearSelection() {
            this.selectedIds = [];
        },

        openStockModal(bookId, title, stock, available) {
            this.stockModal.bookId = bookId;
            this.stockModal.title = title;
            this.stockModal.stock = stock;
            this.stockModal.available = available;
            this.stockModal.action = 'add';
            this.stockModal.amount = 1;
            this.stockModal.open = true;
        },

        closeStockModal() {
            this.stockModal.open = false;
        },

        submitStockForm(form) {
            const realForm = document.createElement('form');
            realForm.method = 'POST';
            realForm.action = '/dashboard/books/' + this.stockModal.bookId + '/stock';

            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = csrfMeta ? csrfMeta.getAttribute('content') : '';
            realForm.appendChild(csrf);

            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = this.stockModal.action;
            realForm.appendChild(actionInput);

            const amountInput = document.createElement('input');
            amountInput.type = 'hidden';
            amountInput.name = 'amount';
            amountInput.value = this.stockModal.amount;
            realForm.appendChild(amountInput);

            document.body.appendChild(realForm);
            realForm.submit();
        },

        openBulkConfirm(status) {
            this.bulkModal.status = status;
            this.bulkModal.open = true;
        },

        submitBulkStatus() {
            const container = document.getElementById('bulkIdsContainer');
            container.innerHTML = '';
            this.selectedIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                container.appendChild(input);
            });
            document.getElementById('bulkStatusInput').value = this.bulkModal.status;
            document.getElementById('bulkStatusForm').submit();
        },
    };
}
</script>
@endpush

@endsection
