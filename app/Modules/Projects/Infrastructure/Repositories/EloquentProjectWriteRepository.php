<?php

namespace App\Modules\Projects\Infrastructure\Repositories;

use App\Models\Projects\Project;
use App\Models\User;
use App\Modules\Projects\Application\DTOs\DraftProjectFormData;
use App\Modules\Projects\Application\DTOs\ProjectCollaboratorFormData;
use App\Modules\Projects\Domain\Contracts\ProjectWriteRepository;
use App\Modules\Shared\DTOs\Content\ModelContentFormData;

class EloquentProjectWriteRepository implements ProjectWriteRepository
{
    public function createForUser(User $user, DraftProjectFormData $data): Project
    {
        $project = $user->projects()->create([
            'name' => $data->name,
            'description' => $data->description,
            'repository_url' => $data->repository_url,
            'demo_url' => $data->demo_url,
            'tech_stack' => $data->tech_stack,
            'version' => $data->version,
            'license' => $data->license,
            'project_status_id' => $data->project_status_id,
            'content' => [],
        ]);

        $project->categories()->sync($data->categories);

        return $project;
    }

    public function update(Project $project, DraftProjectFormData $data): Project
    {
        $project->update([
            'name' => $data->name,
            'description' => $data->description,
            'repository_url' => $data->repository_url,
            'demo_url' => $data->demo_url,
            'tech_stack' => $data->tech_stack,
            'version' => $data->version,
            'license' => $data->license,
            'project_status_id' => $data->project_status_id,
        ]);

        $project->categories()->sync($data->categories);

        return $project;
    }

    public function delete(Project $project): void
    {
        $project->deleteOrFail();
    }

    public function updateContent(Project $project, ModelContentFormData $data): Project
    {
        $project->content = $data->content;
        $project->save();

        return $project;
    }

    public function togglePublished(Project $project): Project
    {
        $project->is_published = ! $project->is_published;
        $project->published_at = $project->is_published ? now() : null;
        $project->save();

        return $project;
    }

    public function addCollaborator(Project $project, ProjectCollaboratorFormData $data): void
    {
        $project->collaborators()->syncWithoutDetaching([
            $data->user_id => ['role' => $data->role],
        ]);
    }

    public function removeCollaborator(Project $project, int $userId): void
    {
        $project->collaborators()->detach($userId);
    }
}
