<?php

use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth',
    'inactive',
])->group(function () {
    Route::get('inactive', fn () => inertia('auth/inactive-user'))->name('inactive');
});
