<?php

use App\Modules\Classroom\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

// Authenticated routes.
Route::middleware(['web', 'auth'])->prefix('courses')->name('courses.')->group(function () {

    Route::get('/', [CourseController::class, 'index'])->name('index');
    Route::get('/create', [CourseController::class, 'create'])->name('create');
    Route::post('/', [CourseController::class, 'store'])->name('store');
    Route::get('/{course}', [CourseController::class, 'show'])->name('show');
    Route::get('/{course}/edit', [CourseController::class, 'edit'])->name('edit');
    Route::put('/{course}', [CourseController::class, 'update'])->name('update');
    Route::delete('/{course}', [CourseController::class, 'destroy'])->name('destroy');

});

// Admin routes.
// Route::middleware(['web', 'auth', 'role:admin'])->prefix('admin/courses')->name('admin.courses.')->group(function () {
//     //
// });

if (file_exists(base_path('app/Modules/Classroom/routes/course.features.php'))) {
    require base_path('app/Modules/Classroom/routes/course.features.php');
}
