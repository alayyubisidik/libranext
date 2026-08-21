@extends('dashboard.layouts.app')

@section('title', 'Members')

@section('content')

<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div class="flex-1 w-full md:w-auto">
        <form action="{{ route('dashboard.members.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3" novalidate>
            <div class="relative flex-1 max-w-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, code..." 
                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            
            <select name="status" class="block w-full sm:w-36 py-2 px-3 border border-gray-300 bg-white rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>

            <button type="submit" class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Filter
            </button>
            
            @if(request('search') || request('status'))
                <a href="{{ route('dashboard.members.index') }}" class="inline-flex justify-center items-center px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                    Clear
                </a>
            @endif
        </form>
    </div>
    
    <div class="flex-shrink-0">
        <a href="{{ route('dashboard.members.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-full sm:w-auto">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Add Member
        </a>
    </div>
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
                        @if($member->hasMedia('avatar'))
                            <img src="{{ $member->getFirstMediaUrl('avatar') }}" alt="{{ $member->name }}" class="h-10 w-10 object-cover rounded-full shadow-sm border border-gray-200">
                        @else
                            <div class="h-10 w-10 bg-blue-100 flex items-center justify-center rounded-full border border-blue-200 text-blue-600 font-bold">
                                {{ substr($member->name, 0, 1) }}
                            </div>
                        @endif
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
                        @if($member->member_status === 'active')
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
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
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
