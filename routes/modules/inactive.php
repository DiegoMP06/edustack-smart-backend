<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware([
    'auth',
    'inactive',
])->group(function () {
    Route::get('inactive', fn () => Inertia::render('auth/inactive-user'))->name('inactive');
});
