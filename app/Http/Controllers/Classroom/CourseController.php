<?php

namespace App\Http\Controllers\Classroom;

use App\Http\Controllers\Controller;
use App\Http\Resources\Classroom\CourseCollection;
use App\Models\Classroom\Course;
use App\Models\Classroom\CourseCategory;
use App\Models\Classroom\CourseStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class CourseController extends Controller
{
    private function formData(): array
    {
        return [
            'statuses' => CourseStatus::orderBy('order')->get(['id', 'name', 'slug', 'color']),
            'categories' => CourseCategory::orderBy('order')->get(['id', 'name', 'slug', 'color', 'icon']),
        ];
    }

    private function rules(bool $isStore = true): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'cover' => [$isStore ? 'required' : 'nullable', 'image', 'mimes:jpg,png,jpeg,webp'],
            'summary' => ['required', 'string', 'min:100'],
            'code' => ['nullable', 'string', 'max:20'],
            'credits' => ['required', 'integer', 'min:0', 'max:20'],
            'period' => ['nullable', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
            'is_free' => ['required', 'boolean'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'course_status_id' => ['required', 'exists:course_statuses,id'],
            'course_category_id' => ['nullable', 'exists:course_categories,id'],
            'start_date' => ['required', 'date', ...($isStore ? ['after:today'] : [])],
            'end_date' => ['required', 'date', 'after:start_date'],
            'enrollment_start_date' => ['nullable', 'date', 'before_or_equal:start_date'],
            'enrollment_end_date' => ['nullable', 'date', 'after:enrollment_start_date', 'before_or_equal:end_date'],
            'is_published' => ['required', 'boolean'],
        ];
    }

    private function validateBusinessRules(array $data): void
    {
        if (! $data['is_free'] && $data['price'] <= 0) {
            throw ValidationException::withMessages([
                'price' => 'Si el curso no es gratuito debe tener un precio mayor a 0.',
            ]);
        }
    }

    public function index(Request $request)
    {
        $courses = Course::query()
            ->where('user_id', $request->user()->id)
            ->with(['status', 'category', 'media'])
            ->when($request->search, fn ($q, $search) => $q->whereLike('name', "%{$search}%"))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('classroom/courses/courses', [
            ...$this->formData(),
            'courses' => new CourseCollection($courses),
            'search' => $request->string('search', ''),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function create()
    {
        return Inertia::render('classroom/courses/create-course', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(isStore: true));

        $this->validateBusinessRules($data);

        $course = new Course;
        $course->name = $data['name'];
        $course->summary = $data['summary'];
        $course->content = [];
        $course->code = $data['code'];
        $course->credits = $data['credits'];
        $course->period = $data['period'];
        $course->price = $data['price'];
        $course->is_free = $data['is_free'];
        $course->capacity = $data['capacity'];
        $course->course_status_id = $data['course_status_id'];
        $course->course_category_id = $data['course_category_id'];
        $course->start_date = $data['start_date'];
        $course->end_date = $data['end_date'];
        $course->enrollment_start_date = $data['enrollment_start_date'];
        $course->enrollment_end_date = $data['enrollment_end_date'];
        $course->is_published = $data['is_published'];
        $course->user_id = $request->user()->id;
        $course->save();

        $course->addMediaFromRequest('cover')->toMediaCollection('cover');

        return redirect()->intended(
            route('classroom.courses.content.edit', ['course' => $course, 'edit' => false], false)
        )->with('message', 'Curso creado correctamente.');
    }

    public function show(Course $course, Request $request)
    {
        return Inertia::render('classroom/courses/show-course', [
            'course' => (new CourseCollection([$course->load(['status', 'category', 'media'])]))->first(),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function edit(Course $course, Request $request)
    {
        return Inertia::render('classroom/courses/edit-course', [
            ...$this->formData(),
            'course' => (new CourseCollection([$course->load(['status', 'category', 'media'])]))->first(),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function update(Request $request, Course $course)
    {
        $data = $request->validate($this->rules(isStore: false));

        $this->validateBusinessRules($data);

        $course->name = $data['name'];
        $course->summary = $data['summary'];
        $course->code = $data['code'];
        $course->credits = $data['credits'];
        $course->period = $data['period'];
        $course->price = $data['price'];
        $course->is_free = $data['is_free'];
        $course->capacity = $data['capacity'];
        $course->course_status_id = $data['course_status_id'];
        $course->course_category_id = $data['course_category_id'];
        $course->start_date = $data['start_date'];
        $course->end_date = $data['end_date'];
        $course->enrollment_start_date = $data['enrollment_start_date'];
        $course->enrollment_end_date = $data['enrollment_end_date'];
        $course->is_published = $data['is_published'];
        $course->save();

        if ($request->hasFile('cover')) {
            $course->clearMediaCollection('cover');
            $course->addMediaFromRequest('cover')->toMediaCollection('cover');
        }

        return back()->with('message', 'Curso actualizado correctamente.');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return back()->with('message', 'Curso eliminado correctamente.');
    }
}
