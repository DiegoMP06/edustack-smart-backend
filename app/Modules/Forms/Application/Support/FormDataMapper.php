<?php

namespace App\Modules\Forms\Application\Support;

use App\Models\Forms\Form;

class FormDataMapper
{
    public function forIndex(Form $form): array
    {
        return [
            'id' => $form->id,
            'title' => $form->title,
            'description' => $form->description,
            'is_published' => $form->is_published,
            'is_active' => $form->is_active,
            'type' => $form->relationLoaded('type') ? $form->type->toArray() : null,
            'created_at' => $form->created_at,
            'updated_at' => $form->updated_at,
            'responses_count' => $form->responses_count ?? 0,
        ];
    }

    public function forShow(Form $form): array
    {
        return array_merge($this->forIndex($form), [
            'requires_login' => $form->requires_login,
            'allow_multiple_responses' => $form->allow_multiple_responses,
            'max_responses' => $form->max_responses,
            'collect_email' => $form->collect_email,
            'show_progress_bar' => $form->show_progress_bar,
            'shuffle_sections' => $form->shuffle_sections,
            'available_from' => $form->available_from,
            'available_until' => $form->available_until,
            'confirmation_message' => $form->confirmation_message,
            'redirect_url' => $form->redirect_url,
            'is_quiz_mode' => $form->is_quiz_mode,
            'time_limit_minutes' => $form->time_limit_minutes,
            'max_attempts' => $form->max_attempts,
            'passing_score' => $form->passing_score,
            'randomize_questions' => $form->randomize_questions,
            'randomize_options' => $form->randomize_options,
            'show_results_to_respondent' => $form->show_results_to_respondent,
            'show_correct_answers' => $form->show_correct_answers,
            'show_feedback_after' => $form->show_feedback_after,
            'sections' => $form->relationLoaded('sections') ? $form->sections->toArray() : [],
            'logic_rules' => $form->relationLoaded('logicRules') ? $form->logicRules->toArray() : [],
        ]);
    }

    public function forEdit(Form $form): array
    {
        return $this->forShow($form); // Edit can reuse show logic if identical
    }
}
