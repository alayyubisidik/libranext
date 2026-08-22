<?php

use App\Http\Controllers\Auth\SetupAdminController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FineController;
use App\Http\Controllers\MemberController;
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
    
    // Member accessible routes for fines
    Route::post('dashboard/fines/{fine}/pay-midtrans', [FineController::class, 'payMidtrans'])->name('dashboard.fines.pay-midtrans');
    Route::post('dashboard/fines/midtrans-callback', [FineController::class, 'midtransCallback'])->name('dashboard.fines.midtrans-callback');

    // Member accessible routes for borrowing
    Route::get('dashboard/member/borrow', [BorrowingController::class, 'memberCreate'])->name('dashboard.member.borrow.create');
    Route::post('dashboard/member/borrow', [BorrowingController::class, 'memberStore'])->name('dashboard.member.borrow.store');
    Route::delete('dashboard/member/borrow/{borrowing}', [BorrowingController::class, 'memberDestroy'])->name('dashboard.member.borrow.destroy');

    Route::middleware(['role.admin'])->prefix('dashboard')->name('dashboard.')->group(function () {
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('books', BookController::class)->except(['show']);
        Route::resource('members', MemberController::class);
        Route::resource('borrowings', BorrowingController::class)->only(['index', 'create', 'store', 'show']);
        Route::post('borrowings/{borrowing}/return', [BorrowingController::class, 'returnBook'])->name('borrowings.return');
        Route::resource('fines', FineController::class)->only(['index', 'show']);
        Route::post('fines/{fine}/waive', [FineController::class, 'waive'])->name('fines.waive');
        Route::post('fines/{fine}/pay-cash', [FineController::class, 'payCash'])->name('fines.pay-cash');
    });
});

require __DIR__.'/auth.php';

Route::post('api/webhooks/midtrans', [\App\Http\Controllers\Api\WebhookController::class, 'midtrans'])->name('api.webhooks.midtrans');
