<?php

namespace App\Modules\Classroom\Application\UseCases\Command;

use App\Models\Classroom\Course;
use App\Models\Classroom\CourseAnnouncement;
use App\Models\User;
use App\Modules\Classroom\Application\DTOs\CourseAnnouncementFormData;
use App\Modules\Classroom\Domain\Contracts\CourseWriteRepository;

class CreateAnnouncementAction
{
    public function __construct(
        private CourseWriteRepository $courseWriteRepository,
    ) {}

    public function execute(Course $course, User $author, CourseAnnouncementFormData $data): CourseAnnouncement
    {
        return $this->courseWriteRepository->createAnnouncement($course, $author, $data);
    }
}
