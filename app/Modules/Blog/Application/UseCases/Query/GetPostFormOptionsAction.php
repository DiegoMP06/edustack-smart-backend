<?php

namespace App\Modules\Blog\Application\UseCases\Query;

use App\Modules\Blog\Application\DTOs\PostCategoryData;
use App\Modules\Blog\Application\DTOs\PostTypeData;
use App\Modules\Blog\Domain\Contracts\PostFormOptionsRepository;

class GetPostFormOptionsAction
{
    public function __construct(
        private PostFormOptionsRepository $postFormOptionsRepository,
    ) {}

    public function execute(): array
    {
        return [
            'types' => PostTypeData::collect(
                $this->postFormOptionsRepository->getTypes()
            ),
            'categories' => PostCategoryData::collect(
                $this->postFormOptionsRepository->getCategories()
            ),
        ];
    }
}
