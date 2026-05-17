<?php

namespace App\Modules\Classroom\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classroom\Course;
use App\Modules\Classroom\Application\UseCases\Command\UpdateCourseContentAction;
use App\Modules\Classroom\Http\Requests\UpdateCourseContentRequest;
use App\Modules\Shared\DTOs\Content\ModelContentFormData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class CourseContentController extends Controller
{
    public function __construct(
        private UpdateCourseContentAction $updateCourseContentAction,
    ) {}

    public function edit(Course $course, Request $request): InertiaResponse
    {
        $edit = $request->boolean('edit', false);

        return Inertia::render('classroom/course-content', [
            'course' => $course,
            'edit' => $edit,
            'message' => session('message'),
        ]);
    }

    public function update(UpdateCourseContentRequest $request, Course $course): RedirectResponse
    {
        $edit = $request->boolean('edit', false);
        $data = $request->validated();

        $formData = ModelContentFormData::from($data);
        $this->updateCourseContentAction->execute($course, $formData);

        $route = $edit ?
            back() :
            redirect()->intended(route(
                'classroom.courses.show',
                ['course' => $course],
                absolute: false
            ));

        return $route->with('message', 'Contenido guardado correctamente.');
    }
}
