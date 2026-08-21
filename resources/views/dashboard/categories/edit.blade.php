@extends('dashboard.layouts.app')

@section('title', 'Edit Category')

@section('content')

<div class="mb-6 flex items-center justify-between">
    <a href="{{ route('dashboard.categories.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Categories
    </a>
</div>

<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden max-w-3xl">
    <form action="{{ route('dashboard.categories.update', $category) }}" method="POST" class="p-6 sm:p-8 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                   class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description <span class="text-gray-400 font-normal">(Optional)</span></label>
            <textarea name="description" id="description" rows="3"
                      class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('description', $category->description) }}</textarea>
            <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>

        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" id="status" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                <option value="active" {{ old('status', $category->status) == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $category->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <p class="mt-1 text-sm text-gray-500">Active categories can be assigned to books.</p>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>

        <div class="pt-4 border-t border-gray-200 flex justify-end gap-3">
            <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Update Category
            </button>
        </div>
    </form>
</div>

@endsection
