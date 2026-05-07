<?php

use App\Modules\Forms\Http\Controllers\FormController;
use Illuminate\Support\Facades\Route;

// Authenticated routes.
Route::middleware(['web', 'auth'])->prefix('forms')->name('forms.')->group(function () {

    Route::get('/', [FormController::class, 'index'])->name('index');
    Route::get('/create', [FormController::class, 'create'])->name('create');
    Route::post('/', [FormController::class, 'store'])->name('store');
    Route::get('/{form}', [FormController::class, 'show'])->name('show');
    Route::get('/{form}/edit', [FormController::class, 'edit'])->name('edit');
    Route::put('/{form}', [FormController::class, 'update'])->name('update');
    Route::delete('/{form}', [FormController::class, 'destroy'])->name('destroy');

});

// Admin routes.
// Route::middleware(['web', 'auth', 'role:admin'])->prefix('admin/forms')->name('admin.forms.')->group(function () {
//     //
// });

if (file_exists(base_path('app/Modules/Forms/routes/form.features.php'))) {
    require base_path('app/Modules/Forms/routes/form.features.php');
}
