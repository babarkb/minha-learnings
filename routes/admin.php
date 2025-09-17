<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PageTypeController;
use App\Http\Controllers\Admin\WorksheetController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:admin')->group(function (): void {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])
        ->name('login.store');
});

Route::middleware('auth:admin')->group(function (): void {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('page-types', PageTypeController::class)->except('show');
    Route::resource('worksheets', WorksheetController::class);
});
