<?php

namespace App\Modules\Forms\Domain\Contracts;

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

interface FormWriteRepository
{
    // Form
    public function createForUser(User $user, DraftFormFormData $data): Form;

    public function update(Form $form, DraftFormFormData $data): Form;

    public function delete(Form $form): void;

    public function toggleStatus(Form $form): Form;

    // Section
    public function createSection(Form $form, FormSectionFormData $data): FormSection;

    public function updateSection(FormSection $section, FormSectionFormData $data): FormSection;

    public function deleteSection(FormSection $section): void;

    // Question
    public function createQuestion(Form $form, FormQuestionFormData $data): FormQuestion;

    public function updateQuestion(FormQuestion $question, FormQuestionFormData $data): FormQuestion;

    public function deleteQuestion(FormQuestion $question): void;

    // Question Option
    public function createQuestionOption(FormQuestion $question, FormQuestionOptionFormData $data): FormQuestionOption;

    public function updateQuestionOption(FormQuestionOption $option, FormQuestionOptionFormData $data): FormQuestionOption;

    public function deleteQuestionOption(FormQuestionOption $option): void;

    // Logic Rule
    public function createLogicRule(Form $form, FormLogicRuleFormData $data): FormLogicRule;

    public function updateLogicRule(FormLogicRule $rule, FormLogicRuleFormData $data): FormLogicRule;

    public function deleteLogicRule(FormLogicRule $rule): void;

    // Logic Condition
    public function createLogicCondition(FormLogicRule $rule, FormLogicConditionFormData $data): FormLogicCondition;

    public function updateLogicCondition(FormLogicCondition $condition, FormLogicConditionFormData $data): FormLogicCondition;

    public function deleteLogicCondition(FormLogicCondition $condition): void;

    // Response
    public function createResponse(Form $form, FormResponseFormData $data, ?User $user): FormResponse;

    public function updateResponseAnswer(FormResponseAnswer $answer, GradeAnswerFormData $data): FormResponseAnswer;
}
