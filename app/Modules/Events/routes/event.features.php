<?php

use App\Modules\Events\Http\Controllers\EventContentController;
use App\Modules\Events\Http\Controllers\EventGalleryController;
use App\Modules\Events\Http\Controllers\EventStatusController;
use Illuminate\Support\Facades\Route;

Route::get('events/{event}/content/edit', [EventContentController::class, 'edit'])->name('events.content.edit');
Route::patch('events/{event}/content', [EventContentController::class, 'update'])->name('events.content.update');

Route::patch('events/{event}/status', EventStatusController::class)->name('events.status');

Route::post('events/{event}/medias', [EventGalleryController::class, 'store'])->name('events.medias.store');
Route::delete('events/{event}/medias/{media}', [EventGalleryController::class, 'destroy'])->name('events.medias.destroy');
