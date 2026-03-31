<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/modules/settings.php';
require __DIR__.'/modules/admin.php';
require __DIR__.'/modules/classroom.php';
require __DIR__.'/modules/events.php';
require __DIR__.'/modules/forms.php';
require __DIR__.'/modules/inactive.php';
require __DIR__.'/modules/posts.php';
require __DIR__.'/modules/projects.php';
