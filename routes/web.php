<?php

use App\Http\Controllers\Admin\ServiceCategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', fn (Request $request) => redirect()->route('dashboard.'.$request->user()->role->value))->name('dashboard');

    Route::get('dashboard/admin', fn () => Inertia::render('dashboard/Admin'))
        ->middleware('role:admin')
        ->name('dashboard.admin');

    Route::get('dashboard/operator', fn () => Inertia::render('dashboard/Operator'))
        ->middleware('role:operator')
        ->name('dashboard.operator');

    Route::get('dashboard/penjaga', fn () => Inertia::render('dashboard/Penjaga'))
        ->middleware('role:penjaga')
        ->name('dashboard.penjaga');

    Route::get('dashboard/monitor', fn () => Inertia::render('dashboard/Monitor'))
        ->middleware('role:monitor')
        ->name('dashboard.monitor');

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('service-categories', ServiceCategoryController::class)->except(['show']);
    });
});

require __DIR__.'/settings.php';
