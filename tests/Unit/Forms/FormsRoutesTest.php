<?php

use Tests\TestCase;

uses(TestCase::class);

it('registers forms module routes', function (string $name, array $parameters, string $expectedPath) {
    expect(route($name, $parameters, false))->toBe($expectedPath);
})->with([
    ['forms.index', [], '/forms'],
    ['forms.create', [], '/forms/create'],
    ['forms.store', [], '/forms'],
    ['forms.show', ['form' => 1], '/forms/1'],
    ['forms.edit', ['form' => 1], '/forms/1/edit'],
    ['forms.update', ['form' => 1], '/forms/1'],
    ['forms.destroy', ['form' => 1], '/forms/1'],
    ['forms.status', ['form' => 1], '/forms/1/status'],

    ['forms.sections.index', ['form' => 1], '/forms/1/sections'],
    ['forms.sections.store', ['form' => 1], '/forms/1/sections'],
    ['forms.sections.update', ['form' => 1, 'section' => 2], '/forms/1/sections/2'],
    ['forms.sections.destroy', ['form' => 1, 'section' => 2], '/forms/1/sections/2'],

    ['forms.questions.index', ['form' => 1], '/forms/1/questions'],
    ['forms.questions.store', ['form' => 1], '/forms/1/questions'],
    ['forms.questions.update', ['form' => 1, 'question' => 2], '/forms/1/questions/2'],
    ['forms.questions.destroy', ['form' => 1, 'question' => 2], '/forms/1/questions/2'],

    ['forms.questions.options.store', ['form' => 1, 'question' => 2], '/forms/1/questions/2/options'],
    ['forms.questions.options.update', ['form' => 1, 'question' => 2, 'option' => 3], '/forms/1/questions/2/options/3'],
    ['forms.questions.options.destroy', ['form' => 1, 'question' => 2, 'option' => 3], '/forms/1/questions/2/options/3'],

    ['forms.logic-rules.index', ['form' => 1], '/forms/1/logic-rules'],
    ['forms.logic-rules.store', ['form' => 1], '/forms/1/logic-rules'],
    ['forms.logic-rules.update', ['form' => 1, 'rule' => 2], '/forms/1/logic-rules/2'],
    ['forms.logic-rules.destroy', ['form' => 1, 'rule' => 2], '/forms/1/logic-rules/2'],

    ['forms.logic-rules.conditions.store', ['form' => 1, 'rule' => 2], '/forms/1/logic-rules/2/conditions'],
    ['forms.logic-rules.conditions.update', ['form' => 1, 'rule' => 2, 'condition' => 3], '/forms/1/logic-rules/2/conditions/3'],
    ['forms.logic-rules.conditions.destroy', ['form' => 1, 'rule' => 2, 'condition' => 3], '/forms/1/logic-rules/2/conditions/3'],

    ['forms.responses.store', ['form' => 1], '/forms/1/responses'],
    ['forms.responses.index', ['form' => 1], '/forms/1/responses'],
    ['forms.responses.show', ['form' => 1, 'response' => 2], '/forms/1/responses/2'],
    ['forms.responses.answers.update', ['form' => 1, 'response' => 2, 'answer' => 3], '/forms/1/responses/2/answers/3'],
]);
