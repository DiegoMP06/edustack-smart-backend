<?php

namespace App\Modules\Classroom\Application\UseCases\Command;

use App\Models\Classroom\CourseAnnouncement;
use App\Modules\Classroom\Application\DTOs\CourseAnnouncementFormData;
use App\Modules\Classroom\Domain\Contracts\CourseWriteRepository;

class UpdateAnnouncementAction
{
    public function __construct(
        private CourseWriteRepository $courseWriteRepository,
    ) {}

    public function execute(CourseAnnouncement $announcement, CourseAnnouncementFormData $data): CourseAnnouncement
    {
        return $this->courseWriteRepository->updateAnnouncement($announcement, $data);
    }
}
