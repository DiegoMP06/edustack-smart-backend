<?php

use App\Http\Controllers\Events\Activity\EventActivityContentController;
use App\Http\Controllers\Events\Activity\EventActivityController;
use App\Http\Controllers\Events\Activity\EventActivityGalleryController;
use App\Http\Controllers\Events\Activity\EventActivityStatusController;
use App\Http\Controllers\Events\Collaborator\EventCollaboratorsController;
use App\Http\Controllers\Events\Competition\CompetitionRoundContentController;
use App\Http\Controllers\Events\Competition\CompetitionRoundController;
use App\Http\Controllers\Events\EventActivityRegistrationController;
use App\Http\Controllers\Events\EventContentController;
use App\Http\Controllers\Events\EventController;
use App\Http\Controllers\Events\EventRegistrationController;
use App\Http\Controllers\Events\EventStatusController;
use App\Http\Controllers\Events\EventTeamController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth',
    'verified',
    'active',
    'role:member|admin',
])->group(function () {
    Route::resource('events', EventController::class);

    Route::resource('events.activities', EventActivityController::class)
        ->parameters(['activities' => 'activity']);

    Route::resource('events.activities.rounds', CompetitionRoundController::class)
        ->parameters(['activities' => 'activity', 'rounds' => 'round']);

    Route::resource('events.activities.teams', EventTeamController::class)
        ->parameters(['activities' => 'activity', 'teams' => 'team'])
        ->only(['store', 'update']);
    Route::post('events/{event}/activities/{activity}/teams/{team}/join', [EventTeamController::class, 'join'])
        ->name('events.activities.teams.join');
    Route::post('events/{event}/activities/{activity}/teams/{team}/leave', [EventTeamController::class, 'leave'])
        ->name('events.activities.teams.leave');
    Route::post('events/{event}/registrations', [EventRegistrationController::class, 'store'])
        ->name('events.registrations.store');
    Route::delete('events/{event}/registrations/{registration}', [EventRegistrationController::class, 'destroy'])
        ->name('events.registrations.destroy');
    Route::post('events/{event}/activities/{activity}/registrations', [EventActivityRegistrationController::class, 'store'])
        ->name('events.activities.registrations.store');
    Route::delete('events/{event}/activities/{activity}/registrations/{registration}', [EventActivityRegistrationController::class, 'destroy'])
        ->name('events.activities.registrations.destroy');

    Route::get('events/{event}/activities/{activity}/rounds/{round}/content/edit', [CompetitionRoundContentController::class, 'edit'])
        ->name('events.activities.rounds.content.edit');
    Route::patch('events/{event}/activities/{activity}/rounds/{round}/content', [CompetitionRoundContentController::class, 'update'])
        ->name('events.activities.rounds.content.update');

    Route::get('events/{event}/activities/{activity}/content/edit', [EventActivityContentController::class, 'edit'])
        ->name('events.activities.content.edit');
    Route::patch('events/{event}/activities/{activity}/content', [EventActivityContentController::class, 'update'])
        ->name('events.activities.content.update');

    Route::patch('events/{event}/activities/{activity}/status', EventActivityStatusController::class)
        ->name('events.activities.status');

    Route::post('events/{event}/activities/{activity}/medias', [EventActivityGalleryController::class, 'store'])->name('events.activities.medias.store');
    Route::delete('events/{event}/activities/{activity}/medias/{media}', [EventActivityGalleryController::class, 'destroy'])->name('events.activities.medias.destroy');

    Route::get('events/{event}/content/edit', [EventContentController::class, 'edit'])->name('events.content.edit');
    Route::patch('events/{event}/content', [EventContentController::class, 'update'])->name('events.content.update');

    Route::resource('events/{event}/event-collaborators', EventCollaboratorsController::class)->only(['index', 'store', 'destroy']);

    Route::patch('events/{event}/status', EventStatusController::class)->name('events.status');
});
