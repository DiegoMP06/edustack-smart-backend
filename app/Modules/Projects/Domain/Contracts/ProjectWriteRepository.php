<?php

namespace App\Modules\Projects\Domain\Contracts;

use App\Models\Projects\Project;
use App\Models\User;
use App\Modules\Projects\Application\DTOs\DraftProjectFormData;
use App\Modules\Projects\Application\DTOs\ProjectCollaboratorFormData;
use App\Modules\Shared\DTOs\Content\ModelContentFormData;

interface ProjectWriteRepository
{
    public function createForUser(User $user, DraftProjectFormData $data): Project;

    public function update(Project $post, DraftProjectFormData $data): Project;

    public function delete(Project $project): void;

    public function updateContent(Project $project, ModelContentFormData $data): Project;

    public function togglePublished(Project $project): Project;

    public function addCollaborator(Project $project, ProjectCollaboratorFormData $data): void;

    public function removeCollaborator(Project $project, int $userId): void;
}
