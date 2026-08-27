@extends('dashboard.layouts.app')

@section('title', 'Categories')

@section('content')

<div x-data="categoriesPage()" x-init="init()">

    {{-- Toolbar --}}
    <div class="mb-4 flex flex-col gap-3">
        <form action="{{ route('dashboard.categories.index') }}" method="GET" id="filterForm">
            {{-- Row 1: Search + Add Category --}}
            <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center justify-between mb-3">
                <div class="relative flex-1 max-w-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search categories..."
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <a href="{{ route('dashboard.categories.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 whitespace-nowrap">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Add Category
                </a>
            </div>

            {{-- Row 2: Filters + Sort --}}
            <div class="flex flex-wrap gap-2 items-center">
                <select name="status" onchange="this.form.submit()" class="py-2 px-3 border border-gray-300 bg-white rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                <select name="sort" onchange="this.form.submit()" class="py-2 px-3 border border-gray-300 bg-white rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Newest Added</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest Added</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name A–Z</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z–A</option>
                    <option value="books_asc" {{ request('sort') == 'books_asc' ? 'selected' : '' }}>Books Count Lowest → Highest</option>
                    <option value="books_desc" {{ request('sort') == 'books_desc' ? 'selected' : '' }}>Books Count Highest → Lowest</option>
                </select>

                <button type="submit" class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Filter
                </button>

                @if(request('search') || request('status') || request('sort'))
                    <a href="{{ route('dashboard.categories.index') }}" class="inline-flex justify-center items-center px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                        Clear
                    </a>
                @endif
            </div>
        </form>

        {{-- Bulk Action Bar --}}
        <div x-show="selectedIds.length > 0" x-cloak class="flex items-center gap-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
            <span class="text-sm text-blue-700 font-medium" x-text="selectedIds.length + ' category(s) selected'"></span>
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
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-3 w-10">
                            <input type="checkbox" @change="toggleAll($event)" :checked="isAllSelected()"
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Books Count</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($categories as $category)
                    <tr>
                        <td class="px-4 py-4">
                            <input type="checkbox" :value="{{ $category->id }}" x-model="selectedIds"
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                            @if($category->description)
                                <div class="text-sm text-gray-500">{{ Str::limit($category->description, 50) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $category->books_count }} books
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('dashboard.categories.update', $category) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="name" value="{{ $category->name }}">
                                <input type="hidden" name="description" value="{{ $category->description }}">
                                <select name="status" onchange="this.form.submit()"
                                        class="py-1 px-2 text-xs font-medium rounded-full border focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500
                                        {{ $category->status === 'active' ? 'bg-green-100 text-green-800 border-green-300' : 'bg-red-100 text-red-800 border-red-300' }}">
                                    <option value="active" {{ $category->status === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $category->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('dashboard.categories.edit', $category) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>

                            <form action="{{ route('dashboard.categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            <p class="text-sm">No categories found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $categories->links() }}
        </div>
        @endif
    </div>

    {{-- Hidden bulk status form --}}
    <form id="bulkStatusForm" action="{{ route('dashboard.categories.bulk-status') }}" method="POST" class="hidden">
        @csrf
        @foreach(request()->except('_token') as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
        <div id="bulkIdsContainer"></div>
        <input type="hidden" name="status" id="bulkStatusInput">
    </form>

    {{-- Bulk Confirm Modal --}}
    <div x-show="bulkModal.open" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="bulkModal.open = false"></div>
            <div class="relative bg-white rounded-xl shadow-xl max-w-sm w-full p-6 z-10">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Confirm Bulk Status Change</h3>
                <p class="text-sm text-gray-600 mb-6">
                    You are about to set <strong x-text="selectedIds.length"></strong> category(s) to
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

</div>

@push('scripts')
<script>
function categoriesPage() {
    return {
        selectedIds: [],
        allIds: @json($categories->pluck('id')),
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
