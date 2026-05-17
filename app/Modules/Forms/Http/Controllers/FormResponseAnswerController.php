<?php

namespace App\Modules\Forms\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Forms\Form;
use App\Models\Forms\FormResponse;
use App\Models\Forms\FormResponseAnswer;
use App\Modules\Forms\Application\DTOs\GradeAnswerFormData;
use App\Modules\Forms\Application\UseCases\Command\UpdateFormResponseAnswerAction;
use App\Modules\Forms\Http\Requests\UpdateFormResponseAnswerRequest;

class FormResponseAnswerController extends Controller
{
    public function __construct(
        private UpdateFormResponseAnswerAction $updateFormResponseAnswerAction,
    ) {}

    public function update(UpdateFormResponseAnswerRequest $request, Form $form, FormResponse $response, FormResponseAnswer $answer)
    {
        if ($response->form_id !== $form->id) {
            abort(404);
        }

        if ($answer->form_response_id !== $response->id) {
            abort(404);
        }

        $this->authorize('gradeResponses', $form);

        $data = GradeAnswerFormData::from($request->validated());

        $this->updateFormResponseAnswerAction->execute($answer, $data);

        $totalScore = $response->answers()->sum('score_awarded');
        $maxScore = $response->form->questions()
            ->where('has_correct_answer', true)
            ->sum('score');
        $percentage = $maxScore > 0
            ? round(($totalScore / $maxScore) * 100, 2)
            : 0;
        $passed = $form->passing_score !== null
            ? $percentage >= $form->passing_score
            : null;

        $allGraded = $response->answers()
            ->whereHas('question', fn ($q) => $q->where('has_correct_answer', true))
            ->whereNull('score_awarded')
            ->doesntExist();

        $response->update([
            'score' => $totalScore,
            'max_score' => $maxScore,
            'percentage' => $percentage,
            'passed' => $passed,
            'status' => $allGraded ? 'graded' : $response->status,
            'graded_by' => $request->user()->id,
            'graded_at' => now(),
        ]);

        return back()->with('message', 'Respuesta calificada correctamente.');
    }
}
