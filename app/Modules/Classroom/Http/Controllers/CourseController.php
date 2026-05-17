<?php

namespace App\Modules\Classroom\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classroom\Course;
use App\Modules\Classroom\Application\DTOs\CourseFormData;
use App\Modules\Classroom\Application\UseCases\Command\CreateCourseAction;
use App\Modules\Classroom\Application\UseCases\Command\DeleteCourseAction;
use App\Modules\Classroom\Application\UseCases\Command\UpdateCourseAction;
use App\Modules\Classroom\Application\UseCases\Query\GetCourseFormOptionsAction;
use App\Modules\Classroom\Application\UseCases\Query\ListUserCoursesAction;
use App\Modules\Classroom\Http\Requests\StoreCourseRequest;
use App\Modules\Classroom\Http\Requests\UpdateCourseRequest;
use App\Modules\Classroom\Http\Resources\CourseCollection;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class CourseController extends Controller
{
    public function __construct(
        private ListUserCoursesAction $listUserCoursesAction,
        private GetCourseFormOptionsAction $getCourseFormOptionsAction,
        private CreateCourseAction $createCourseAction,
        private UpdateCourseAction $updateCourseAction,
        private DeleteCourseAction $deleteCourseAction,
    ) {}

    private function validateBusinessRules(array $data): void
    {
        if (! $data['is_free'] && $data['price'] <= 0) {
            throw ValidationException::withMessages([
                'price' => 'Si el curso no es gratuito debe tener un precio mayor a 0.',
            ]);
        }
    }

    public function index(Request $request): InertiaResponse
    {
        $params = ListCollectionQueryParamsData::from($request->all());
        $courses = $this->listUserCoursesAction->execute($params, $request->user());

        return Inertia::render('classroom/courses/courses', [
            ...$this->getCourseFormOptionsAction->execute(),
            'courses' => new CourseCollection($courses),
            'filter' => $request->query('filter'),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function create(): InertiaResponse
    {
        return Inertia::render('classroom/courses/create-course', $this->getCourseFormOptionsAction->execute());
    }

    public function store(StoreCourseRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $this->validateBusinessRules($data);

        $formData = CourseFormData::from($data);
        $course = $this->createCourseAction->execute($request->user(), $formData, $request->file('cover'));

        return redirect()->intended(
            route('classroom.courses.content.edit', ['course' => $course, 'edit' => false], false)
        )->with('message', 'Curso creado correctamente.');
    }

    public function show(Course $course, Request $request): InertiaResponse
    {
        return Inertia::render('classroom/courses/show-course', [
            'course' => (new CourseCollection([$course->load(['status', 'category', 'media'])]))->first(),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function edit(Course $course, Request $request): InertiaResponse
    {
        return Inertia::render('classroom/courses/edit-course', [
            ...$this->getCourseFormOptionsAction->execute(),
            'course' => (new CourseCollection([$course->load(['status', 'category', 'media'])]))->first(),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function update(UpdateCourseRequest $request, Course $course): RedirectResponse
    {
        $data = $request->validated();
        $this->validateBusinessRules($data);

        $formData = CourseFormData::from($data);
        $this->updateCourseAction->execute($course, $formData, $request->file('cover'));

        return back()->with('message', 'Curso actualizado correctamente.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $this->deleteCourseAction->execute($course);

        return back()->with('message', 'Curso eliminado correctamente.');
    }
}
