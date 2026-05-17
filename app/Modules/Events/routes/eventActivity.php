<?php

use App\Modules\Events\Http\Controllers\EventActivities\EventActivityController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'web',
    'auth',
    'verified',
    'active',
    'role:teacher|admin|member',
])->prefix('events/{event}/activities')->name('events.activities.')->group(function () {
    Route::get('/', [EventActivityController::class, 'index'])->name('index');
    Route::get('/create', [EventActivityController::class, 'create'])->name('create');
    Route::post('/', [EventActivityController::class, 'store'])->name('store');
    Route::get('/{event_activity}', [EventActivityController::class, 'show'])->name('show');
    Route::get('/{event_activity}/edit', [EventActivityController::class, 'edit'])->name('edit');
    Route::put('/{event_activity}', [EventActivityController::class, 'update'])->name('update');
    Route::delete('/{event_activity}', [EventActivityController::class, 'destroy'])->name('destroy');
});
