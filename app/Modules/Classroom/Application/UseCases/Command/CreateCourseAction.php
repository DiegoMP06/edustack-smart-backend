<?php

namespace App\Modules\Classroom\Application\UseCases\Command;

use App\Models\Classroom\Course;
use App\Models\User;
use App\Modules\Classroom\Application\DTOs\CourseFormData;
use App\Modules\Classroom\Domain\Contracts\CourseWriteRepository;
use Illuminate\Support\Facades\DB;

class CreateCourseAction
{
    public function __construct(
        private CourseWriteRepository $courseWriteRepository,
    ) {}

    public function execute(User $user, CourseFormData $data, mixed $coverFile = null): Course
    {
        return DB::transaction(function () use ($user, $data, $coverFile) {
            $course = $this->courseWriteRepository->createForUser($user, $data);

            if ($coverFile) {
                $course->addMedia($coverFile)->toMediaCollection('cover');
            }

            return $course;
        });
    }
}
