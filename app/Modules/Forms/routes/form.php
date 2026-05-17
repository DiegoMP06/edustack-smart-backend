<?php

use App\Modules\Forms\Http\Controllers\FormController;
use App\Modules\Forms\Http\Controllers\FormLogicConditionController;
use App\Modules\Forms\Http\Controllers\FormLogicRuleController;
use App\Modules\Forms\Http\Controllers\FormQuestionController;
use App\Modules\Forms\Http\Controllers\FormQuestionOptionController;
use App\Modules\Forms\Http\Controllers\FormResponseAnswerController;
use App\Modules\Forms\Http\Controllers\FormResponseController;
use App\Modules\Forms\Http\Controllers\FormSectionController;
use App\Modules\Forms\Http\Controllers\FormStatusController;
use Illuminate\Support\Facades\Route;

Route::post('forms/{form}/responses', [FormResponseController::class, 'store'])
    ->name('forms.responses.store');

Route::middleware([
    'auth',
    'verified',
    'active',
    'role:student|admin|member|teacher',
])->group(function () {
    Route::resource('forms', FormController::class)->except(['show']);

    // Public form view could be authenticated or public depending on policy, but we have show route here:
    Route::get('forms/{form}', [FormController::class, 'show'])->name('forms.show');

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
