@extends('dashboard.layouts.app')

@section('title', 'Add Member')

@section('content')

<div class="mb-6">
    <a href="{{ route('dashboard.members.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Members
    </a>
</div>

<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
    <form action="{{ route('dashboard.members.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8" novalidate>
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-6">
                <!-- Name & Email -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                </div>

                <!-- Phone & DOB -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                    </div>
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <textarea name="address" id="address" rows="3"
                              class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('address') }}</textarea>
                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                </div>
            </div>
            
            <!-- Sidebar / Metadata -->
            <div class="space-y-6">
                
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="text-sm font-medium text-gray-900 mb-4">Member Status</h3>
                    
                    <div>
                        <label for="member_status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                        <select name="member_status" id="member_status" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm bg-white">
                            <option value="active" {{ old('member_status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('member_status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Inactive members cannot borrow books.</p>
                        <x-input-error :messages="$errors->get('member_status')" class="mt-2" />
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200" x-data="{ imagePreview: null }">
                    <h3 class="text-sm font-medium text-gray-900 mb-4">Profile Photo</h3>
                    
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md relative overflow-hidden group">
                        
                        <!-- Preview -->
                        <div x-show="imagePreview" class="absolute inset-0 w-full h-full bg-white z-10 p-1 flex items-center justify-center" style="display: none;">
                            <img :src="imagePreview" class="w-24 h-24 object-cover rounded-full shadow-sm">
                            <button type="button" @click="imagePreview = null; $refs.avatarInput.value = ''" class="absolute top-2 right-2 bg-red-100 text-red-600 rounded-full p-1 hover:bg-red-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <!-- Upload UI -->
                        <div class="space-y-1 text-center" x-show="!imagePreview">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <div class="flex text-sm text-gray-600 justify-center mt-2">
                                <label for="avatar" class="relative cursor-pointer bg-transparent rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Upload a photo</span>
                                    <input id="avatar" name="avatar" type="file" class="sr-only" accept="image/*" x-ref="avatarInput" @change="
                                        const file = $event.target.files[0];
                                        if (file) {
                                            const reader = new FileReader();
                                            reader.onload = (e) => { imagePreview = e.target.result; };
                                            reader.readAsDataURL(file);
                                        }
                                    ">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">PNG, JPG up to 2MB</p>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('avatar')" class="mt-2" />
                </div>

                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                    <p class="text-sm text-blue-800 flex gap-2">
                        <svg class="w-5 h-5 flex-shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Default password for new members is <strong>password</strong>. They can change it after logging in.
                    </p>
                </div>

            </div>
        </div>

        <div class="pt-6 mt-6 border-t border-gray-200 flex justify-end">
            <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Save Member
            </button>
        </div>
    </form>
</div>

@endsection
