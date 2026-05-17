<?php

use App\Modules\Events\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'web',
    'auth',
    'verified',
    'active',
    'role:teacher|admin|member',
])->prefix('events')->name('events.')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::get('/create', [EventController::class, 'create'])->name('create');
    Route::post('/', [EventController::class, 'store'])->name('store');
    Route::get('/{event}', [EventController::class, 'show'])->name('show');
    Route::get('/{event}/edit', [EventController::class, 'edit'])->name('edit');
    Route::put('/{event}', [EventController::class, 'update'])->name('update');
    Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy');
});

if (file_exists(base_path('app/Modules/Events/routes/event.features.php'))) {
    require base_path('app/Modules/Events/routes/event.features.php');
}

if (file_exists(base_path('app/Modules/Events/routes/eventActivity.php'))) {
    require base_path('app/Modules/Events/routes/eventActivity.php');
}
