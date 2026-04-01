<?php

use App\Http\Controllers\Forms\FormController;
use App\Http\Controllers\Forms\FormLogicConditionController;
use App\Http\Controllers\Forms\FormLogicRuleController;
use App\Http\Controllers\Forms\FormQuestionController;
use App\Http\Controllers\Forms\FormQuestionOptionController;
use App\Http\Controllers\Forms\FormResponseAnswerController;
use App\Http\Controllers\Forms\FormResponseController;
use App\Http\Controllers\Forms\FormSectionController;
use App\Http\Controllers\Forms\FormStatusController;
use Illuminate\Support\Facades\Route;

Route::post('forms/{form}/responses', [FormResponseController::class, 'store'])
    ->name('forms.responses.store');

Route::middleware([
    'auth',
    'verified',
    'active',
    'role:student|admin|member|teacher',
])->group(function () {
    Route::resource('forms', FormController::class);

    Route::patch('forms/{form}/status', FormStatusController::class)->name('forms.status');

    Route::resource('forms.sections', FormSectionController::class)
        ->parameters(['sections' => 'section'])
        ->only(['index', 'store', 'update', 'destroy']);

    Route::resource('forms.questions', FormQuestionController::class)
        ->parameters(['questions' => 'question'])
        ->only(['index', 'store', 'update', 'destroy']);

    Route::resource('forms.questions.options', FormQuestionOptionController::class)
        ->parameters(['questions' => 'question', 'options' => 'option'])
        ->only(['store', 'update', 'destroy']);

    Route::resource('forms.logic-rules', FormLogicRuleController::class)
        ->parameters(['logic-rules' => 'rule'])
        ->only(['index', 'store', 'update', 'destroy']);

    Route::resource('forms.logic-rules.conditions', FormLogicConditionController::class)
        ->parameters(['logic-rules' => 'rule', 'conditions' => 'condition'])
        ->only(['store', 'update', 'destroy']);

    Route::resource('forms.responses', FormResponseController::class)
        ->parameters(['responses' => 'response'])
        ->only(['index', 'show']);

    Route::patch('forms/{form}/responses/{response}/answers/{answer}', [FormResponseAnswerController::class, 'update'])
        ->name('forms.responses.answers.update');
});
