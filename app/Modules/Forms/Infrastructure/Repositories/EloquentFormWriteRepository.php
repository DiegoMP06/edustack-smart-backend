<?php

namespace App\Modules\Forms\Infrastructure\Repositories;

use App\Models\Forms\Form;
use App\Models\Forms\FormLogicCondition;
use App\Models\Forms\FormLogicRule;
use App\Models\Forms\FormQuestion;
use App\Models\Forms\FormQuestionOption;
use App\Models\Forms\FormResponse;
use App\Models\Forms\FormResponseAnswer;
use App\Models\Forms\FormSection;
use App\Models\User;
use App\Modules\Forms\Application\DTOs\DraftFormFormData;
use App\Modules\Forms\Application\DTOs\FormLogicConditionFormData;
use App\Modules\Forms\Application\DTOs\FormLogicRuleFormData;
use App\Modules\Forms\Application\DTOs\FormQuestionFormData;
use App\Modules\Forms\Application\DTOs\FormQuestionOptionFormData;
use App\Modules\Forms\Application\DTOs\FormResponseFormData;
use App\Modules\Forms\Application\DTOs\FormSectionFormData;
use App\Modules\Forms\Application\DTOs\GradeAnswerFormData;
use App\Modules\Forms\Domain\Contracts\FormWriteRepository;
use Illuminate\Support\Facades\DB;

class EloquentFormWriteRepository implements FormWriteRepository
{
    public function createForUser(User $user, DraftFormFormData $data): Form
    {
        return $user->forms()->create([
            ...$data->toArray(),
            'is_published' => false,
            'is_active' => true,
        ]);
    }

    public function update(Form $form, DraftFormFormData $data): Form
    {
        $form->update($data->toArray());

        return $form;
    }

    public function delete(Form $form): void
    {
        $form->delete();
    }

    public function toggleStatus(Form $form): Form
    {
        $form->update(['is_published' => ! $form->is_published]);

        return $form;
    }

    public function createSection(Form $form, FormSectionFormData $data): FormSection
    {
        return $form->sections()->create($data->toArray());
    }

    public function updateSection(FormSection $section, FormSectionFormData $data): FormSection
    {
        $section->update($data->toArray());

        return $section;
    }

    public function deleteSection(FormSection $section): void
    {
        $section->delete();
    }

    public function createQuestion(Form $form, FormQuestionFormData $data): FormQuestion
    {
        return $form->questions()->create($data->toArray());
    }

    public function updateQuestion(FormQuestion $question, FormQuestionFormData $data): FormQuestion
    {
        $question->update($data->toArray());

        return $question;
    }

    public function deleteQuestion(FormQuestion $question): void
    {
        $question->delete();
    }

    public function createQuestionOption(FormQuestion $question, FormQuestionOptionFormData $data): FormQuestionOption
    {
        return $question->options()->create($data->toArray());
    }

    public function updateQuestionOption(FormQuestionOption $option, FormQuestionOptionFormData $data): FormQuestionOption
    {
        $option->update($data->toArray());

        return $option;
    }

    public function deleteQuestionOption(FormQuestionOption $option): void
    {
        $option->delete();
    }

    public function createLogicRule(Form $form, FormLogicRuleFormData $data): FormLogicRule
    {
        return $form->logicRules()->create($data->toArray());
    }

    public function updateLogicRule(FormLogicRule $rule, FormLogicRuleFormData $data): FormLogicRule
    {
        $rule->update($data->toArray());

        return $rule;
    }

    public function deleteLogicRule(FormLogicRule $rule): void
    {
        $rule->delete();
    }

    public function createLogicCondition(FormLogicRule $rule, FormLogicConditionFormData $data): FormLogicCondition
    {
        return $rule->conditions()->create($data->toArray());
    }

    public function updateLogicCondition(FormLogicCondition $condition, FormLogicConditionFormData $data): FormLogicCondition
    {
        $condition->update($data->toArray());

        return $condition;
    }

    public function deleteLogicCondition(FormLogicCondition $condition): void
    {
        $condition->delete();
    }

    public function createResponse(Form $form, FormResponseFormData $data, ?User $user): FormResponse
    {
        return DB::transaction(function () use ($form, $data, $user) {
            $response = $form->responses()->create([
                'respondent_email' => $data->respondent_email,
                'user_id' => $user?->id,
                'attempt_number' => $data->attempt_number,
                'ip_address' => $data->ip_address,
                'user_agent' => $data->user_agent,
                'started_at' => now(),
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);

            foreach ($data->answers as $answerData) {
                $response->answers()->create($answerData->toArray());
            }

            return $response;
        });
    }

    public function updateResponseAnswer(FormResponseAnswer $answer, GradeAnswerFormData $data): FormResponseAnswer
    {
        $answer->update($data->toArray());

        return $answer;
    }
}
