<?php

use App\Modules\Events\Http\Controllers\Collaborators\EventCollaboratorsController;
use App\Modules\Events\Http\Controllers\EventContentController;
use App\Modules\Events\Http\Controllers\EventStatusController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'web',
    'auth',
    'verified',
    'active',
    'role:teacher|admin|member',
])->group(function () {
    Route::get('events/{event}/content/edit', [EventContentController::class, 'edit'])->name('events.content.edit');
    Route::patch('events/{event}/content', [EventContentController::class, 'update'])->name('events.content.update');

    Route::patch('events/{event}/status', EventStatusController::class)->name('events.status');

    Route::get('events/{event}/event-collaborators', [EventCollaboratorsController::class, 'index'])->name('events.collaborators.index');
    Route::post('events/{event}/event-collaborators', [EventCollaboratorsController::class, 'store'])->name('events.collaborators.store');
    Route::delete('events/{event}/event-collaborators/{event_collaborator}', [EventCollaboratorsController::class, 'destroy'])->name('events.collaborators.destroy');
});
