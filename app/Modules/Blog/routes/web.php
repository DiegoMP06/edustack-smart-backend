<?php

use App\Modules\Blog\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

/**
 * Rutas CRUD principal de Posts (gestión interna).
 * web.features.php y api.php son cargados directamente
 * por BlogProvider::registerRoutes() — no se necesitan requires aquí.
 */
Route::middleware([
    'web',
    'auth',
    'verified',
    'active',
    'role:teacher|admin|member',
])->prefix('posts')->name('posts.')->group(function () {
    Route::get('/', [PostController::class, 'index'])->name('index');
    Route::get('/create', [PostController::class, 'create'])->name('create');
    Route::post('/', [PostController::class, 'store'])->name('store');
    Route::get('/{post}', [PostController::class, 'show'])->name('show');
    Route::get('/{post}/edit', [PostController::class, 'edit'])->name('edit');
    Route::put('/{post}', [PostController::class, 'update'])->name('update');
    Route::delete('/{post}', [PostController::class, 'destroy'])->name('destroy');
});
