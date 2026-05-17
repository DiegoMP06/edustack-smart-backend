<?php

use App\Modules\Projects\Http\Controllers\ProjectCollaboratorsController;
use App\Modules\Projects\Http\Controllers\ProjectContentController;
use App\Modules\Projects\Http\Controllers\ProjectGalleryController;
use App\Modules\Projects\Http\Controllers\ProjectStatusController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'web',
    'auth',
    'verified',
    'active',
    'role:teacher|admin|member|student',
])->group(function () {
    Route::get('projects/{project}/content/edit', [ProjectContentController::class, 'edit'])->name('projects.content.edit');
    Route::patch('projects/{project}/content', [ProjectContentController::class, 'update'])->name('projects.content.update');

    Route::patch('projects/{project}/status', ProjectStatusController::class)->name('projects.status');

    Route::post('projects/{project}/medias', [ProjectGalleryController::class, 'store'])->name('projects.medias.store');
    Route::delete('projects/{project}/medias/{media}', [ProjectGalleryController::class, 'destroy'])->name('projects.medias.destroy');

    Route::get('projects/{project}/project-collaborators', [ProjectCollaboratorsController::class, 'index'])->name('projects.collaborators.index');
    Route::post('projects/{project}/project-collaborators', [ProjectCollaboratorsController::class, 'store'])->name('projects.collaborators.store');
    Route::delete('projects/{project}/project-collaborators/{project_collaborator}', [ProjectCollaboratorsController::class, 'destroy'])->name('projects.collaborators.destroy');
});
