<?php

namespace App\Modules\Projects\Application\UseCases\Query;

use App\Models\User;
use App\Modules\Projects\Application\Support\ProjectDataMapper;
use App\Modules\Projects\Domain\Contracts\ProjectReadRepository;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;
use Illuminate\Pagination\LengthAwarePaginator;

class ListUserProjectsAction
{
    public function __construct(
        private ProjectReadRepository $projectReadRepository,
        private ProjectDataMapper $projectDataMapper,
    ) {}

    public function execute(ListCollectionQueryParamsData $data, User $user): LengthAwarePaginator
    {
        $projects = $this->projectReadRepository->paginateUserProjects($user, $data);

        $projects->getCollection()->transform(
            fn ($project) => $this->projectDataMapper->forIndex($project)
        );

        return $projects;
    }
}
