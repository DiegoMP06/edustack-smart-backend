<?php

use App\Modules\Blog\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

// Authenticated routes.
Route::middleware(['web', 'auth'])->prefix('posts')->name('posts.')->group(function () {

    Route::get('/', [PostController::class, 'index'])->name('index');
    Route::get('/create', [PostController::class, 'create'])->name('create');
    Route::post('/', [PostController::class, 'store'])->name('store');
    Route::get('/{post}', [PostController::class, 'show'])->name('show');
    Route::get('/{post}/edit', [PostController::class, 'edit'])->name('edit');
    Route::put('/{post}', [PostController::class, 'update'])->name('update');
    Route::delete('/{post}', [PostController::class, 'destroy'])->name('destroy');

});

// Admin routes.
// Route::middleware(['web', 'auth', 'role:admin'])->prefix('admin/posts')->name('admin.posts.')->group(function () {
//     //
// });

if (file_exists(base_path('app/Modules/Blog/routes/post.features.php'))) {
    require base_path('app/Modules/Blog/routes/post.features.php');
}
