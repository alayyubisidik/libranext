@extends('dashboard.layouts.app')

@section('title', 'Add Book')

@section('content')

<div class="mb-6">
    <a href="{{ route('dashboard.books.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Books
    </a>
</div>

<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
    <form novalidate action="{{ route('dashboard.books.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-6">
                <!-- Title & Category -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
                        <select name="category_id" id="category_id" required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-white">
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                    </div>
                </div>

                <!-- Author & Publisher -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="author" class="block text-sm font-medium text-gray-700">Author <span class="text-red-500">*</span></label>
                        <input type="text" name="author" id="author" value="{{ old('author') }}" required
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <x-input-error :messages="$errors->get('author')" class="mt-2" />
                    </div>

                    <div>
                        <label for="publisher" class="block text-sm font-medium text-gray-700">Publisher</label>
                        <input type="text" name="publisher" id="publisher" value="{{ old('publisher') }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <x-input-error :messages="$errors->get('publisher')" class="mt-2" />
                    </div>
                </div>

                <!-- ISBN & Year & Stock -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="isbn" class="block text-sm font-medium text-gray-700">ISBN</label>
                        <input type="text" name="isbn" id="isbn" value="{{ old('isbn') }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <x-input-error :messages="$errors->get('isbn')" class="mt-2" />
                    </div>

                    <div>
                        <label for="publication_year" class="block text-sm font-medium text-gray-700">Publication Year</label>
                        <input type="number" name="publication_year" id="publication_year" value="{{ old('publication_year') }}" min="1900" max="{{ date('Y') + 1 }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <x-input-error :messages="$errors->get('publication_year')" class="mt-2" />
                    </div>

                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700">Total Stock <span class="text-red-500">*</span></label>
                        <input type="number" name="stock" id="stock" value="{{ old('stock', 1) }}" min="0" required
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="4"
                              class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('description') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>
            </div>
            
            <!-- Sidebar / Metadata -->
            <div class="space-y-6">
                
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="text-sm font-medium text-gray-900 mb-4">Book Status</h3>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-white">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Inactive books cannot be borrowed by members.</p>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200" x-data="{ imagePreview: null }">
                    <h3 class="text-sm font-medium text-gray-900 mb-4">Book Cover</h3>
                    
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md relative overflow-hidden group">
                        
                        <!-- Preview -->
                        <div x-show="imagePreview" class="absolute inset-0 w-full h-full bg-white z-10 p-1" style="display: none;">
                            <img :src="imagePreview" class="w-full h-full object-contain">
                            <button type="button" @click="imagePreview = null; $refs.coverInput.value = ''" class="absolute top-2 right-2 bg-red-100 text-red-600 rounded-full p-1 hover:bg-red-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <!-- Upload UI -->
                        <div class="space-y-1 text-center" x-show="!imagePreview">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="cover" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Upload a file</span>
                                    <input id="cover" name="cover" type="file" class="sr-only" accept="image/*" x-ref="coverInput" @change="
                                        const file = $event.target.files[0];
                                        if (file) {
                                            const reader = new FileReader();
                                            reader.onload = (e) => { imagePreview = e.target.result; };
                                            reader.readAsDataURL(file);
                                        }
                                    ">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 2MB</p>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('cover')" class="mt-2" />
                </div>

            </div>
        </div>

        <div class="pt-6 mt-6 border-t border-gray-200 flex justify-end">
            <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Save Book
            </button>
        </div>
    </form>
</div>

@endsection
