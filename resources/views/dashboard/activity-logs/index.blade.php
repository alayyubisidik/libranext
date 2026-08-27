@extends('dashboard.layouts.app')

@section('title', 'Activity Logs')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Activity Logs</h1>
    <p class="text-sm text-gray-500 mt-1">Monitor important actions performed by users.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-200 bg-gray-50">
        <form action="{{ route('dashboard.activity-logs.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4 items-end">
            <div class="flex-1 w-full lg:w-auto">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search description, user or type..." class="block w-full pl-10 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>
            </div>

            <div class="w-full lg:w-40">
                 <label for="event" class="block text-sm font-medium text-gray-700 mb-1">Event</label>
                 <select name="event" id="event" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-white py-2">
                     <option value="">All Events</option>
                     <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Created</option>
                     <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Updated</option>
                     <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                 </select>
            </div>

            <div class="w-full lg:w-48">
                 <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">User</label>
                 <select name="user_id" id="user_id" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-white py-2">
                     <option value="">All Users</option>
                     @foreach($users as $user)
                         <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                     @endforeach
                 </select>
            </div>

            <div class="w-full lg:w-48">
                 <label for="model_type" class="block text-sm font-medium text-gray-700 mb-1">Model Type</label>
                 <select name="model_type" id="model_type" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-white py-2">
                     <option value="">All Types</option>
                     @foreach($subjectTypes as $type)
                         <option value="{{ $type }}" {{ request('model_type') == $type ? 'selected' : '' }}>{{ class_basename($type) }}</option>
                     @endforeach
                 </select>
            </div>
            
            <div class="w-full lg:w-48">
                 <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                 <select name="sort" id="sort" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-white py-2">
                     <option value="desc" {{ request('sort', 'desc') == 'desc' ? 'selected' : '' }}>Newest → Oldest</option>
                     <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Oldest → Newest</option>
                 </select>
            </div>

            <div class="flex gap-2 w-full lg:w-auto">
                <button type="submit" class="flex-1 lg:flex-none inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'event', 'user_id', 'model_type']) || request('sort') === 'asc')
                    <a href="{{ route('dashboard.activity-logs.index') }}" class="flex-1 lg:flex-none inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Changes</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-700">
                @forelse ($activities as $activity)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $activity->created_at->format('Y-m-d H:i:s') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($activity->causer)
                                <span class="font-medium text-gray-900">{{ $activity->causer->name }}</span>
                                <br><span class="text-xs text-gray-500">{{ $activity->causer->email }}</span>
                            @else
                                <span class="text-gray-400 italic">System / Unknown</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                {{ ucfirst($activity->event) }}
                            </span>
                            {{ $activity->description }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                            {{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}
                        </td>
                        <td class="px-6 py-4">
                            @if($activity->properties->has('attributes'))
                                <div x-data="{ show: false }">
                                    <button @click="show = !show" class="text-blue-600 hover:text-blue-800 text-xs focus:outline-none flex items-center gap-1 font-medium">
                                        <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        <svg x-show="show" style="display: none;" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                        <span x-show="!show">View changes</span>
                                        <span x-show="show" style="display: none;">Hide changes</span>
                                    </button>
                                    <div x-show="show" x-transition style="display: none;" class="mt-2 bg-gray-50 rounded-lg border border-gray-200 overflow-hidden w-full max-w-lg">
                                        <table class="min-w-full divide-y divide-gray-200 text-xs">
                                            <thead class="bg-gray-100">
                                                <tr>
                                                    <th class="px-3 py-2 text-left font-medium text-gray-500">Attribute</th>
                                                    @if($activity->properties->has('old'))
                                                        <th class="px-3 py-2 text-left font-medium text-red-500">Before</th>
                                                    @endif
                                                    <th class="px-3 py-2 text-left font-medium text-green-500">After</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 bg-white">
                                                @foreach($activity->properties['attributes'] as $key => $value)
                                                    @php
                                                        $oldValue = $activity->properties->has('old') && array_key_exists($key, $activity->properties['old']) ? $activity->properties['old'][$key] : null;
                                                        $hasChanged = $activity->properties->has('old') && $oldValue !== $value;
                                                    @endphp
                                                    <tr class="{{ $hasChanged ? 'bg-yellow-50' : '' }}">
                                                        <td class="px-3 py-2 font-mono text-gray-700">{{ $key }}</td>
                                                        @if($activity->properties->has('old'))
                                                            <td class="px-3 py-2 text-red-600 break-all">
                                                                {{ is_array($oldValue) || is_object($oldValue) ? json_encode($oldValue) : $oldValue }}
                                                            </td>
                                                        @endif
                                                        <td class="px-3 py-2 text-green-600 break-all">
                                                            {{ is_array($value) || is_object($value) ? json_encode($value) : $value }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <span class="inline-flex items-center gap-1 text-gray-400 text-xs italic">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                    No changes
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-400">No activity logs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4">
        {{ $activities->links() }}
    </div>
</div>
@endsection
