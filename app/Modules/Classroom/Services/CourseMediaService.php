<?php

namespace App\Modules\Classroom\Services;

use App\Models\Classroom\Course;
use App\Modules\Classroom\Actions\DeleteCourseMediaAction;
use App\Modules\Classroom\Actions\StoreCourseMediaAction;
use App\Modules\Classroom\DTOs\CourseMediaData;
use App\Modules\Classroom\DTOs\CourseMediaDeletionData;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CourseMediaService
{
    public function __construct(
        private StoreCourseMediaAction $storeMediaAction,
        private DeleteCourseMediaAction $deleteMediaAction,
    ) {}

    public function store(Course $course, CourseMediaData $data): void
    {
        $this->storeMediaAction->execute($course, $data);
    }

    public function destroy(Course $course, Media $media): void
    {
        $this->deleteMediaAction->execute(
            $course,
            new CourseMediaDeletionData($media),
        );
    }
}
