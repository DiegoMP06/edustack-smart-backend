<?php

namespace App\Modules\Projects\Application\UseCases\Query;

use App\Modules\Projects\Application\Support\ProjectDataMapper;
use App\Modules\Projects\Domain\Contracts\ProjectReadRepository;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;
use Illuminate\Pagination\LengthAwarePaginator;

class ListPublishedProjectsAction
{
    public function __construct(
        private ProjectReadRepository $projectReadRepository,
        private ProjectDataMapper $projectDataMapper,
    ) {}

    public function execute(ListCollectionQueryParamsData $data): LengthAwarePaginator
    {
        $projects = $this->projectReadRepository->paginatePublishedProjects($data);

        $projects->getCollection()->transform(
            fn ($project) => $this->projectDataMapper->forApiIndex($project)
        );

        return $projects;
    }
}
