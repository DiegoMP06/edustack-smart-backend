<?php

use App\Modules\Classroom\Http\Controllers\CourseContentController;
use App\Modules\Classroom\Http\Controllers\CourseGalleryController;
use App\Modules\Classroom\Http\Controllers\CourseStatusController;
use Illuminate\Support\Facades\Route;

Route::get('courses/{course}/content/edit', [CourseContentController::class, 'edit'])->name('courses.content.edit');
Route::patch('courses/{course}/content', [CourseContentController::class, 'update'])->name('courses.content.update');

Route::patch('courses/{course}/status', CourseStatusController::class)->name('courses.status');

Route::post('courses/{course}/medias', [CourseGalleryController::class, 'store'])->name('courses.medias.store');
Route::delete('courses/{course}/medias/{media}', [CourseGalleryController::class, 'destroy'])->name('courses.medias.destroy');
