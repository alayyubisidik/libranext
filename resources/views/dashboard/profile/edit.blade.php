@extends('dashboard.layouts.app')

@section('title', 'Profile')

@section('content')

<div class="space-y-6">
    <!-- Profile Update -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Profile Information</h3>
            <p class="mt-1 text-sm text-gray-500">Update your account's profile information and email address.</p>
        </div>
        
        <form action="{{ route('dashboard.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6" novalidate>
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Avatar Upload -->
                <div class="col-span-1" x-data="{ imagePreview: '{{ $user->avatar_url }}', removeAvatar: false }">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                    
                    <input type="hidden" name="remove_avatar" :value="removeAvatar ? '1' : '0'">
                    
                    <div class="flex flex-col items-center gap-4">
                        <div class="relative w-32 h-32 rounded-full overflow-hidden border-2 border-gray-200 bg-gray-50 shadow-sm">
                            <img :src="imagePreview" alt="{{ $user->name }}" class="w-full h-full object-cover">
                            
                            <button type="button" 
                                    @click="imagePreview = '{{ asset('assets/image/avatar.png') }}'; removeAvatar = true; $refs.avatarInput.value = ''" 
                                    class="absolute top-2 right-2 bg-red-100 text-red-600 rounded-full p-1.5 hover:bg-red-200 focus:outline-none"
                                    x-show="imagePreview !== '{{ asset('assets/image/avatar.png') }}'"
                                    title="Remove photo">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        
                        <div>
                            <label for="avatar" class="cursor-pointer inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                Select New Photo
                            </label>
                            <input id="avatar" name="avatar" type="file" class="sr-only" accept="image/*" x-ref="avatarInput" @change="
                                const file = $event.target.files[0];
                                if (file) {
                                    removeAvatar = false;
                                    const reader = new FileReader();
                                    reader.onload = (e) => { imagePreview = e.target.result; };
                                    reader.readAsDataURL(file);
                                }
                            ">
                        </div>
                        <p class="text-xs text-gray-500">JPG, PNG max 2MB</p>
                        <x-input-error :messages="$errors->get('avatar')" class="mt-1" />
                    </div>
                </div>

                <!-- Basic Info -->
                <div class="col-span-1 md:col-span-2 space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Update Password -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Update Password</h3>
            <p class="mt-1 text-sm text-gray-500">Ensure your account is using a long, random password to stay secure.</p>
        </div>
        
        <form action="{{ route('password.update') }}" method="POST" class="p-6" novalidate>
            @csrf
            @method('PUT')

            <div class="max-w-xl space-y-6">
                <div>
                    <label for="update_password_current_password" class="block text-sm font-medium text-gray-700">Current Password <span class="text-red-500">*</span></label>
                    <input type="password" name="current_password" id="update_password_current_password" autocomplete="current-password"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                </div>

                <div>
                    <label for="update_password_password" class="block text-sm font-medium text-gray-700">New Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" id="update_password_password" autocomplete="new-password"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                </div>
                
                <div>
                    <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" id="update_password_password_confirmation" autocomplete="new-password"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                </div>
            </div>

            <div class="mt-6 flex items-center gap-4">
                <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
