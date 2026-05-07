<?php

namespace App\Modules\Classroom\Actions;

use App\Models\Classroom\Course;
use App\Modules\Classroom\DTOs\CourseMediaDeletionData;
use Illuminate\Validation\ValidationException;

class DeleteCourseMediaAction
{
    public function execute(Course $course, CourseMediaDeletionData $data): void
    {
        $media = $data->media;

        abort_if($media->model_type !== Course::class || $media->model_id !== $course->id, 404);

        if ($course->media()->count() === 1) {
            throw ValidationException::withMessages([
                'image' => 'At least one image is required.',
            ]);
        }

        $media->delete();
    }
}
