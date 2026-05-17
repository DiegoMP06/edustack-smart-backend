<?php

use App\Modules\Admin\Http\Controllers\ListAllUsersController;
use App\Modules\Admin\Http\Controllers\UpdateUserRoleController;
use App\Modules\Admin\Http\Controllers\UpdateUserStatusController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'web',
    'auth',
    'verified',
    'active',
    'role:admin',
])->group(function () {
    Route::get('admin/users', ListAllUsersController::class)->name('admin.users.index');

    Route::patch('admin/users/{user}/status', UpdateUserStatusController::class)->name('admin.users.status');

    Route::patch('admin/users/{user}/role', UpdateUserRoleController::class)->name('admin.users.role');
});
