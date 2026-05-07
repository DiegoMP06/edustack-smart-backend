<?php

namespace App\Modules\Classroom\Services;

use App\Models\Classroom\Course;
use App\Modules\Classroom\Actions\CreateCourseAction;
use App\Modules\Classroom\Actions\DeleteCourseAction;
use App\Modules\Classroom\Actions\UpdateCourseAction;
use App\Modules\Classroom\DTOs\CourseData;
use Illuminate\Pagination\LengthAwarePaginator;

class CourseService
{
    public function __construct(
        private CreateCourseAction $createAction,
        private UpdateCourseAction $updateAction,
        private DeleteCourseAction $deleteAction,
    ) {}

    public function list(array $filters = []): LengthAwarePaginator
    {
        return Course::query()
            ->with([])
            ->when($filters['search'] ?? null, fn ($query, $value) => $query->where('title', 'like', "%{$value}%"))
            ->latest()
            ->paginate(15);
    }

    public function findOrFail(int $id): Course
    {
        return Course::with([])->findOrFail($id);
    }

    public function create(CourseData $data, int $userId): Course
    {
        return $this->createAction->execute($data, $userId);
    }

    public function update(Course $course, CourseData $data): Course
    {
        return $this->updateAction->execute($course, $data);
    }

    public function delete(Course $course): void
    {
        $this->deleteAction->execute($course);
    }
}
