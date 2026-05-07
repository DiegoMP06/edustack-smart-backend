<?php

namespace App\Modules\Classroom\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classroom\Course;
use App\Modules\Classroom\DTOs\CourseContentData;
use App\Modules\Classroom\Http\Requests\UpdateCourseContentRequest;
use App\Modules\Classroom\Services\CourseContentService;
use Illuminate\Http\Request;

class CourseContentController extends Controller
{
    public function __construct(
        private CourseContentService $contentService,
    ) {}

    /**
     * Show the content editor for the given model.
     */
    public function edit(Course $course, Request $request)
    {
        $this->authorize('update', $course);

        return inertia('classroom/course-content', [
            'course' => $course,
            'edit' => $request->boolean('edit', false),
            'message' => $request->session()->get('message'),
        ]);
    }

    /**
     * Persist editor content for the given model.
     */
    public function update(Course $course, UpdateCourseContentRequest $request)
    {
        $this->authorize('update', $course);

        $data = CourseContentData::fromArray($request->validated());
        $this->contentService->update($course, $data);

        return back()->with('message', 'Course content saved successfully.');
    }
}
