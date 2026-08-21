<?php

use App\Http\Controllers\Auth\SetupAdminController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (! User::role('admin')->exists()) {
        return redirect()->route('setup-admin.create');
    }

    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('setup-admin', [SetupAdminController::class, 'create'])
        ->middleware('admin.setup')
        ->name('setup-admin.create');

    Route::post('setup-admin', [SetupAdminController::class, 'store'])
        ->middleware('admin.setup')
        ->name('setup-admin.store');
});

Route::middleware(['auth', 'role.admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware(['auth', 'role.member'])->group(function () {
    Route::get('/member/dashboard', function () {
        return view('member.dashboard');
    })->name('member.dashboard');
});

require __DIR__.'/auth.php';
