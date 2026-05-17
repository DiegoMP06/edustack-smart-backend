<?php

use App\Modules\Blog\Http\Controllers\ApiPostController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api'])
    ->prefix('api/posts')
    ->name('api.posts.')
    ->group(function () {
        Route::get('/', [ApiPostController::class, 'index'])->name('index');
        Route::get('/{post:slug}', [ApiPostController::class, 'show'])->name('show');
    });
