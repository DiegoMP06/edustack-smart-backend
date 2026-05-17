<?php

namespace App\Modules\Projects\Infrastructure\Repositories;

use App\Models\Projects\ProjectCategory;
use App\Models\Projects\ProjectStatus;
use App\Modules\Projects\Application\DTOs\ProjectCategoryData;
use App\Modules\Projects\Application\DTOs\ProjectStatusData;
use App\Modules\Projects\Domain\Contracts\ProjectFormOptionsRepository;

class EloquentProjectFormOptionsRepository implements ProjectFormOptionsRepository
{
    public function getCategories(): array
    {
        return ProjectCategoryData::collect(
            ProjectCategory::orderBy('order')->get()
        )->toArray();
    }

    public function getStatuses(): array
    {
        return ProjectStatusData::collect(
            ProjectStatus::orderBy('order')->get()
        )->toArray();
    }
}
