@extends('dashboard.layouts.app')

@section('title', 'Activity Logs')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Activity Logs</h1>
    <p class="text-sm text-gray-500 mt-1">Monitor important actions performed by users.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-6 border-b border-gray-200">
        <form action="{{ route('dashboard.activity-logs.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search description or type..." class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">Search</button>
                <a href="{{ route('dashboard.activity-logs.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">Clear</a>
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
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
                                    <button @click="show = !show" class="text-blue-600 hover:text-blue-800 text-xs focus:outline-none">
                                        <span x-show="!show">View details</span>
                                        <span x-show="show">Hide details</span>
                                    </button>
                                    <div x-show="show" x-transition class="mt-2 text-xs bg-gray-50 p-2 rounded border overflow-auto max-w-xs max-h-32">
                                        @if($activity->properties->has('old'))
                                            <div class="mb-1 text-red-600"><strong>Old:</strong> {{ json_encode($activity->properties['old']) }}</div>
                                        @endif
                                        <div class="text-green-600"><strong>New:</strong> {{ json_encode($activity->properties['attributes']) }}</div>
                                    </div>
                                </div>
                            @else
                                <span class="text-gray-400 italic">No details</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-400">No activity logs found.</td>
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
