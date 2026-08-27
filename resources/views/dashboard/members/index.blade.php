@extends('dashboard.layouts.app')

@section('title', 'Members')

@section('content')

<div class="mb-6 flex flex-col gap-3">
    <form action="{{ route('dashboard.members.index') }}" method="GET" novalidate>
        {{-- Row 1: Search + Add Member --}}
        <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center justify-between mb-3">
            <div class="relative flex-1 max-w-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, code..."
                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <a href="{{ route('dashboard.members.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 whitespace-nowrap">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Add Member
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
            </select>

            <button type="submit" class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Filter
            </button>

            @if(request('search') || request('status') || request('sort'))
                <a href="{{ route('dashboard.members.index') }}" class="inline-flex justify-center items-center px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                    Clear
                </a>
            @endif
        </div>
    </form>
</div>

<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Profile</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member Info</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($members as $member)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="h-10 w-10 object-cover rounded-full shadow-sm border border-gray-200">
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-semibold text-gray-900">{{ $member->name }}</div>
                        <div class="text-xs text-gray-500 font-mono mt-1">{{ $member->member_code }}</div>
                        <div class="text-xs text-gray-400 mt-1">Joined: {{ $member->joined_at?->format('d M Y') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $member->email }}</div>
                        @if($member->phone)
                            <div class="text-sm text-gray-500">{{ $member->phone }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <form action="{{ route('dashboard.members.update', $member) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="name" value="{{ $member->name }}">
                            <input type="hidden" name="email" value="{{ $member->email }}">
                            <input type="hidden" name="phone" value="{{ $member->phone }}">
                            <input type="hidden" name="address" value="{{ $member->address }}">
                            <input type="hidden" name="date_of_birth" value="{{ $member->date_of_birth?->format('Y-m-d') }}">
                            <select name="member_status" onchange="this.form.submit()"
                                    class="py-1 px-2 text-xs font-medium rounded-full border focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500
                                    {{ $member->member_status === 'active' ? 'bg-green-100 text-green-800 border-green-300' : 'bg-red-100 text-red-800 border-red-300' }}">
                                <option value="active" {{ $member->member_status === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $member->member_status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </form>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('dashboard.members.show', $member) }}" class="text-gray-600 hover:text-gray-900 mr-3">View</a>
                        <a href="{{ route('dashboard.members.edit', $member) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>

                        <form action="{{ route('dashboard.members.destroy', $member) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this member?');" novalidate>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <p class="text-sm">No members found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($members->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 mt-auto">
        {{ $members->links() }}
    </div>
    @endif
</div>

@endsection
