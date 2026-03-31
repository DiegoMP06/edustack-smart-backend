<?php

use App\Http\Controllers\API\ApiBlogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/posts', [ApiBlogController::class, 'index'])->name('api.posts');
Route::get('/posts/{post:slug}', [ApiBlogController::class, 'show'])->name('api.posts.show');
