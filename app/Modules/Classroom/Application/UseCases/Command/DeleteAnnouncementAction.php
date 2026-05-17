<?php

namespace App\Modules\Classroom\Application\UseCases\Command;

use App\Models\Classroom\CourseAnnouncement;
use App\Modules\Classroom\Domain\Contracts\CourseWriteRepository;

class DeleteAnnouncementAction
{
    public function __construct(
        private CourseWriteRepository $courseWriteRepository,
    ) {}

    public function execute(CourseAnnouncement $announcement): void
    {
        $this->courseWriteRepository->deleteAnnouncement($announcement);
    }
}
