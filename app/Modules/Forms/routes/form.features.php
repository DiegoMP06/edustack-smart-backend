<?php

use App\Modules\Forms\Http\Controllers\FormContentController;
use App\Modules\Forms\Http\Controllers\FormGalleryController;
use App\Modules\Forms\Http\Controllers\FormStatusController;
use Illuminate\Support\Facades\Route;

Route::get('forms/{form}/content/edit', [FormContentController::class, 'edit'])->name('forms.content.edit');
Route::patch('forms/{form}/content', [FormContentController::class, 'update'])->name('forms.content.update');

Route::patch('forms/{form}/status', FormStatusController::class)->name('forms.status');

Route::post('forms/{form}/medias', [FormGalleryController::class, 'store'])->name('forms.medias.store');
Route::delete('forms/{form}/medias/{media}', [FormGalleryController::class, 'destroy'])->name('forms.medias.destroy');
