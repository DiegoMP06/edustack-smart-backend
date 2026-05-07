<?php

namespace App\Modules\Projects\Services;

use App\Models\Projects\Project;
use App\Modules\Projects\Actions\CreateProjectAction;
use App\Modules\Projects\Actions\DeleteProjectAction;
use App\Modules\Projects\Actions\UpdateProjectAction;
use App\Modules\Projects\DTOs\ProjectData;
use Illuminate\Pagination\LengthAwarePaginator;

class ProjectService
{
    public function __construct(
        private CreateProjectAction $createAction,
        private UpdateProjectAction $updateAction,
        private DeleteProjectAction $deleteAction,
    ) {}

    public function list(array $filters = []): LengthAwarePaginator
    {
        return Project::query()
            ->with([])
            ->when($filters['search'] ?? null, fn ($query, $value) => $query->where('title', 'like', "%{$value}%"))
            ->latest()
            ->paginate(15);
    }

    public function findOrFail(int $id): Project
    {
        return Project::with([])->findOrFail($id);
    }

    public function create(ProjectData $data, int $userId): Project
    {
        return $this->createAction->execute($data, $userId);
    }

    public function update(Project $project, ProjectData $data): Project
    {
        return $this->updateAction->execute($project, $data);
    }

    public function delete(Project $project): void
    {
        $this->deleteAction->execute($project);
    }
}
