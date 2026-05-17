<?php

namespace App\Modules\Classroom\Application\UseCases\Command;

use App\Models\Classroom\Course;
use App\Modules\Classroom\Application\DTOs\CourseFormData;
use App\Modules\Classroom\Domain\Contracts\CourseWriteRepository;
use Illuminate\Support\Facades\DB;

class UpdateCourseAction
{
    public function __construct(
        private CourseWriteRepository $courseWriteRepository,
    ) {}

    public function execute(Course $course, CourseFormData $data, mixed $coverFile = null): Course
    {
        return DB::transaction(function () use ($course, $data, $coverFile) {
            $updatedCourse = $this->courseWriteRepository->update($course, $data);

            if ($coverFile) {
                $updatedCourse->clearMediaCollection('cover');
                $updatedCourse->addMedia($coverFile)->toMediaCollection('cover');
            }

            return $updatedCourse;
        });
    }
}
