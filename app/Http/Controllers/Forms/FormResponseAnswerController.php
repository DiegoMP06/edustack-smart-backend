<?php

namespace App\Http\Controllers\Forms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Forms\UpdateFormResponseAnswerRequest;
use App\Models\Forms\Form;
use App\Models\Forms\FormResponse;
use App\Models\Forms\FormResponseAnswer;

class FormResponseAnswerController extends Controller
{
    public function update(UpdateFormResponseAnswerRequest $request, Form $form, FormResponse $response, FormResponseAnswer $answer)
    {
        if ($response->form_id !== $form->id) {
            abort(404);
        }

        if ($answer->form_response_id !== $response->id) {
            abort(404);
        }

        $this->authorize('gradeResponses', $form);

        $data = $request->validated();

        $answer->update([
            'is_correct' => $data['is_correct'] ?? null,
            'score_awarded' => $data['score_awarded'],
            'feedback' => $data['feedback'] ?? null,
        ]);

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
