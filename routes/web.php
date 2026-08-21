<?php

use App\Http\Controllers\Auth\SetupAdminController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
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

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::middleware(['role.admin'])->prefix('dashboard')->name('dashboard.')->group(function () {
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('books', BookController::class)->except(['show']);
    });
});

require __DIR__.'/auth.php';
