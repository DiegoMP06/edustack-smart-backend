<?php

use App\Modules\Projects\Http\Controllers\ProjectContentController;
use App\Modules\Projects\Http\Controllers\ProjectGalleryController;
use App\Modules\Projects\Http\Controllers\ProjectStatusController;
use Illuminate\Support\Facades\Route;

Route::get('projects/{project}/content/edit', [ProjectContentController::class, 'edit'])->name('projects.content.edit');
Route::patch('projects/{project}/content', [ProjectContentController::class, 'update'])->name('projects.content.update');

Route::patch('projects/{project}/status', ProjectStatusController::class)->name('projects.status');

Route::post('projects/{project}/medias', [ProjectGalleryController::class, 'store'])->name('projects.medias.store');
Route::delete('projects/{project}/medias/{media}', [ProjectGalleryController::class, 'destroy'])->name('projects.medias.destroy');
