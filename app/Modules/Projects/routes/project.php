<?php

use App\Modules\Projects\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

// Authenticated routes.
Route::middleware(['web', 'auth'])->prefix('projects')->name('projects.')->group(function () {

    Route::get('/', [ProjectController::class, 'index'])->name('index');
    Route::get('/create', [ProjectController::class, 'create'])->name('create');
    Route::post('/', [ProjectController::class, 'store'])->name('store');
    Route::get('/{project}', [ProjectController::class, 'show'])->name('show');
    Route::get('/{project}/edit', [ProjectController::class, 'edit'])->name('edit');
    Route::put('/{project}', [ProjectController::class, 'update'])->name('update');
    Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('destroy');

});

// Admin routes.
// Route::middleware(['web', 'auth', 'role:admin'])->prefix('admin/projects')->name('admin.projects.')->group(function () {
//     //
// });

if (file_exists(base_path('app/Modules/Projects/routes/project.features.php'))) {
    require base_path('app/Modules/Projects/routes/project.features.php');
}
