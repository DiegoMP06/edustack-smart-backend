<?php

namespace App\Modules\Projects\DTOs;

use App\Models\Projects\Project;

readonly class ProjectStatusData
{
    public function __construct(
        public bool $isActive,
    ) {}

    public static function fromModel(Project $project): self
    {
        return new self(
            isActive: ! $project->is_published,
        );
    }
}
