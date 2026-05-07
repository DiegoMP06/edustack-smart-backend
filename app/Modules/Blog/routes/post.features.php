<?php

use App\Modules\Blog\Http\Controllers\PostContentController;
use App\Modules\Blog\Http\Controllers\PostGalleryController;
use App\Modules\Blog\Http\Controllers\PostStatusController;
use Illuminate\Support\Facades\Route;

Route::get('posts/{post}/content/edit', [PostContentController::class, 'edit'])->name('posts.content.edit');
Route::patch('posts/{post}/content', [PostContentController::class, 'update'])->name('posts.content.update');

Route::patch('posts/{post}/status', PostStatusController::class)->name('posts.status');

Route::post('posts/{post}/medias', [PostGalleryController::class, 'store'])->name('posts.medias.store');
Route::delete('posts/{post}/medias/{media}', [PostGalleryController::class, 'destroy'])->name('posts.medias.destroy');
