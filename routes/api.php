<?php

use App\Http\Controllers\API\ApiBlogController;
use App\Http\Controllers\API\ApiProjectsController;
use App\Http\Controllers\API\ApiUsersController;
use App\Http\Controllers\Media\GeneratePresignedURL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/users', ApiUsersController::class)->name('api.users');

    Route::post('media/presigned-url', GeneratePresignedURL::class)->name('api.media.presigned-url');
});

Route::get('/posts', [ApiBlogController::class, 'index'])->name('api.posts');
Route::get('/posts/{post:slug}', [ApiBlogController::class, 'show'])->name('api.posts.show');

Route::get('/projects', [ApiProjectsController::class, 'index'])->name('api.projects');
Route::get('/projects/{project:slug}', [ApiProjectsController::class, 'show'])->name('api.projects.show');


