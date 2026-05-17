<?php

use App\Modules\Projects\Http\Controllers\ApiProjectController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api'])
    ->prefix('api/projects')
    ->name('api.projects.')
    ->group(function () {
        Route::get('/', [ApiProjectController::class, 'index'])->name('index');
        Route::get('/{project:slug}', [ApiProjectController::class, 'show'])->name('show');
    });
