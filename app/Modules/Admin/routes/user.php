<?php

use App\Modules\Admin\Http\Controllers\EditUserStatusController;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Http\Controllers\EditUserRoleController;
use App\Modules\Admin\Http\Controllers\ListAllUsersController;

Route::middleware([
    'web',
    'auth',
    'verified',
    'active',
    'role:admin',
])->group(function () {
    Route::get('admin/users', ListAllUsersController::class)->name('admin.users.index');

    Route::patch('admin/users/{user}/status', EditUserStatusController::class)->name('admin.users.status');

    Route::patch('admin/users/{user}/role', EditUserRoleController::class)->name('admin.users.role');
});
