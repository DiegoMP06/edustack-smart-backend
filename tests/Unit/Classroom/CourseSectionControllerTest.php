<?php

use App\Http\Controllers\Classroom\CourseSectionController;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseSection;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

uses(TestCase::class);

it('throws validation exception when storing a section with duplicated order', function () {
    session()->start();

    $request = Request::create('/classroom/courses/1/sections', 'POST', [
        'name' => 'Modulo 1',
        'description' => 'Descripcion',
        'order' => 1,
        'is_published' => true,
    ], [], [], ['HTTP_REFERER' => '/classroom/courses/1/edit']);
    $request->setLaravelSession(app('session')->driver());

    $sectionsRelation = new class
    {
        public function where(string $column, mixed $value): self
        {
            return $this;
        }

        public function exists(): bool
        {
            return true;
        }
    };

    $course = new class($sectionsRelation) extends Course
    {
        public function __construct(private object $sectionsRelation) {}

        public function sections(): object
        {
            return $this->sectionsRelation;
        }
    };

    try {
        (new CourseSectionController)->store($request, $course);

        throw new RuntimeException('Expected ValidationException was not thrown.');
    } catch (ValidationException $exception) {
        expect($exception->errors())
            ->toHaveKey('order')
            ->and($exception->errors()['order'][0])
            ->toBe('Ya existe una sección con ese orden en este curso.');
    }
});

it('updates section when order is available in the course', function () {
    session()->start();

    $request = Request::create('/classroom/courses/1/sections/2', 'PATCH', [
        'name' => 'Modulo actualizado',
        'description' => 'Nueva descripcion',
        'order' => 2,
        'is_published' => false,
    ], [], [], ['HTTP_REFERER' => '/classroom/courses/1/edit']);
    $request->setLaravelSession(app('session')->driver());
    app()->instance('request', $request);

    $sectionsRelation = new class
    {
        public function where(string $column, mixed $operator, mixed $value = null): self
        {
            return $this;
        }

        public function exists(): bool
        {
            return false;
        }
    };

    $course = new class($sectionsRelation) extends Course
    {
        public function __construct(private object $sectionsRelation) {}

        public function sections(): object
        {
            return $this->sectionsRelation;
        }
    };
    $course->id = 1;

    $section = new class extends CourseSection
    {
        public array $updatedData = [];

        public function update(array $attributes = [], array $options = []): bool
        {
            $this->updatedData = $attributes;

            return true;
        }
    };
    $section->id = 2;
    $section->course_id = 1;

    $response = (new CourseSectionController)->update($request, $course, $section);

    expect($section->updatedData)
        ->toMatchArray([
            'name' => 'Modulo actualizado',
            'description' => 'Nueva descripcion',
            'order' => 2,
            'is_published' => false,
        ])
        ->and($response->getTargetUrl())->toContain('/classroom/courses/1/edit')
        ->and(session('message'))->toBe('Sección actualizada correctamente.');
});

it('throws validation exception when deleting a section with published lessons', function () {
    $course = new Course;
    $course->id = 1;

    $lessonsRelation = new class
    {
        public function where(string $column, bool $value): self
        {
            return $this;
        }

        public function exists(): bool
        {
            return true;
        }
    };

    $section = new class($lessonsRelation) extends CourseSection
    {
        public function __construct(private object $lessonsRelation) {}

        public function lessons(): object
        {
            return $this->lessonsRelation;
        }
    };
    $section->course_id = 1;

    try {
        (new CourseSectionController)->destroy($course, $section);

        throw new RuntimeException('Expected ValidationException was not thrown.');
    } catch (ValidationException $exception) {
        expect($exception->errors())
            ->toHaveKey('section')
            ->and($exception->errors()['section'][0])
            ->toBe('No puedes eliminar una sección con lecciones publicadas.');
    }
});
