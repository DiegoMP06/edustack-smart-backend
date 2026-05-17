<?php

namespace App\Modules\Forms\Application\UseCases\Query;

use App\Models\User;
use App\Modules\Forms\Application\Support\FormDataMapper;
use App\Modules\Forms\Domain\Contracts\FormReadRepository;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;
use Illuminate\Pagination\LengthAwarePaginator;

class ListUserFormsAction
{
    public function __construct(
        private FormReadRepository $formReadRepository,
        private FormDataMapper $formDataMapper,
    ) {}

    public function execute(User $user, ListCollectionQueryParamsData $params): LengthAwarePaginator
    {
        $forms = $this->formReadRepository->paginateUserForms($user, $params);

        $forms->getCollection()->transform(
            fn ($form) => $this->formDataMapper->forIndex($form)
        );

        return $forms;
    }
}
