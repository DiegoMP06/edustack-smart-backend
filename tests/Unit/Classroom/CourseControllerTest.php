<?php

use App\Http\Controllers\Classroom\CourseController;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

uses(TestCase::class);

it('defines store and update validation rules for cover and start date', function () {
    $method = new ReflectionMethod(CourseController::class, 'rules');
    $method->setAccessible(true);

    $storeRules = $method->invoke(new CourseController, true);
    $updateRules = $method->invoke(new CourseController, false);

    expect($storeRules['cover'])
        ->toContain('required')
        ->toContain('image')
        ->toContain('mimes:jpg,png,jpeg,webp')
        ->and($updateRules['cover'])
        ->toContain('nullable')
        ->toContain('image')
        ->toContain('mimes:jpg,png,jpeg,webp')
        ->and($storeRules['start_date'])
        ->toContain('after:today')
        ->and($updateRules['start_date'])
        ->not->toContain('after:today');
});

it('throws a validation exception when a paid course has zero price', function () {
    $method = new ReflectionMethod(CourseController::class, 'validateBusinessRules');
    $method->setAccessible(true);

    try {
        $method->invoke(new CourseController, [
            'is_free' => false,
            'price' => 0,
        ]);

        throw new RuntimeException('Expected ValidationException was not thrown.');
    } catch (ValidationException $exception) {
        expect($exception->errors())
            ->toHaveKey('price')
            ->and($exception->errors()['price'][0])
            ->toBe('Si el curso no es gratuito debe tener un precio mayor a 0.');
    }
});

it('does not throw a validation exception when course is free', function () {
    $method = new ReflectionMethod(CourseController::class, 'validateBusinessRules');
    $method->setAccessible(true);

    $method->invoke(new CourseController, [
        'is_free' => true,
        'price' => 0,
    ]);

    expect(true)->toBeTrue();
});
