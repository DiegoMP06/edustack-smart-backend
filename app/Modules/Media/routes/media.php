<?php

use App\Modules\Media\Http\Controllers\GeneratePresignedURLController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'auth:sanctum'])
    ->prefix('api/media')
    ->name('api.media.')
    ->group(function () {
        Route::post('/presigned-url', GeneratePresignedURLController::class)->name('presigned-url');
    });
