<?php

use App\Http\Controllers\API\ApiBlogController;
use App\Http\Controllers\API\ApiUsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::get('/posts', [ApiBlogController::class, 'index'])->name('api.posts');
Route::get('/posts/{post:slug}', [ApiBlogController::class, 'show'])->name('api.posts.show');

Route::get('/users', ApiUsersController::class)->name('api.users');

