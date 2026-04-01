<?php

use App\Http\Controllers\Classroom\CourseAnnouncementController;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseAnnouncement;
use Illuminate\Http\Request;
use Tests\TestCase;

uses(TestCase::class);

it('stores an announcement with the authenticated user as author', function () {
    session()->start();

    $request = Request::create('/classroom/courses/1/announcements', 'POST', [
        'title' => 'Recordatorio de clase',
        'content' => ['type' => 'doc', 'content' => 'Traer laptop.'],
        'is_pinned' => true,
        'notify_students' => true,
        'published_at' => '2026-03-31 10:30:00',
    ], [], [], ['HTTP_REFERER' => '/classroom/courses/1/edit']);
    $request->setLaravelSession(app('session')->driver());
    app()->instance('request', $request);

    $request->setUserResolver(fn () => (object) ['id' => 17]);

    $announcementsRelation = new class
    {
        public array $createdData = [];

        public function create(array $attributes): object
        {
            $this->createdData = $attributes;

            return (object) $attributes;
        }
    };

    $course = new class($announcementsRelation) extends Course
    {
        public function __construct(private object $announcementsRelation) {}

        public function announcements(): object
        {
            return $this->announcementsRelation;
        }
    };

    $response = (new CourseAnnouncementController)->store($request, $course);

    expect($announcementsRelation->createdData)
        ->toMatchArray([
            'title' => 'Recordatorio de clase',
            'content' => ['type' => 'doc', 'content' => 'Traer laptop.'],
            'is_pinned' => true,
            'notify_students' => true,
            'published_at' => '2026-03-31 10:30:00',
            'user_id' => 17,
        ])
        ->and($response->getTargetUrl())->toContain('/classroom/courses/1/edit')
        ->and(session('message'))->toBe('Anuncio publicado.');
});

it('updates an announcement with valid payload', function () {
    session()->start();

    $request = Request::create('/classroom/courses/1/announcements/2', 'PATCH', [
        'title' => 'Horario actualizado',
        'content' => ['type' => 'doc', 'content' => 'Clase movida a las 9AM.'],
        'is_pinned' => false,
        'notify_students' => true,
        'published_at' => '2026-03-31 12:00:00',
    ], [], [], ['HTTP_REFERER' => '/classroom/courses/1/edit']);
    $request->setLaravelSession(app('session')->driver());
    app()->instance('request', $request);

    $course = new Course;
    $course->id = 1;

    $announcement = new class extends CourseAnnouncement
    {
        public array $updatedData = [];

        public function update(array $attributes = [], array $options = []): bool
        {
            $this->updatedData = $attributes;

            return true;
        }
    };
    $announcement->course_id = 1;

    $response = (new CourseAnnouncementController)->update($request, $course, $announcement);

    expect($announcement->updatedData)
        ->toMatchArray([
            'title' => 'Horario actualizado',
            'content' => ['type' => 'doc', 'content' => 'Clase movida a las 9AM.'],
            'is_pinned' => false,
            'notify_students' => true,
            'published_at' => '2026-03-31 12:00:00',
        ])
        ->and($response->getTargetUrl())->toContain('/classroom/courses/1/edit')
        ->and(session('message'))->toBe('Anuncio actualizado.');
});

it('deletes an announcement and flashes confirmation message', function () {
    session()->start();

    $request = Request::create('/classroom/courses/1/announcements/2', 'DELETE', [], [], [], [
        'HTTP_REFERER' => '/classroom/courses/1/edit',
    ]);
    $request->setLaravelSession(app('session')->driver());
    app()->instance('request', $request);

    $course = new Course;
    $course->id = 1;

    $announcement = new class extends CourseAnnouncement
    {
        public bool $deleted = false;

        public function delete(): bool
        {
            $this->deleted = true;

            return true;
        }
    };
    $announcement->course_id = 1;

    $response = (new CourseAnnouncementController)->destroy($course, $announcement);

    expect($announcement->deleted)
        ->toBeTrue()
        ->and($response->getTargetUrl())->toContain('/classroom/courses/1/edit')
        ->and(session('message'))->toBe('Anuncio eliminado.');
});
