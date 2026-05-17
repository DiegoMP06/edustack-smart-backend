<?php

namespace App\Modules\Blog\Application\UseCases\Query;

use App\Modules\Blog\Application\Support\PostDataMapper;
use App\Modules\Blog\Domain\Contracts\PostReadRepository;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;
use Illuminate\Pagination\LengthAwarePaginator;

class ListPublishedPostsAction
{
    public function __construct(
        private PostReadRepository $postReadRepository,
        private PostDataMapper $postDataMapper,
    ) {}

    public function execute(ListCollectionQueryParamsData $data): LengthAwarePaginator
    {
        $posts = $this->postReadRepository->paginatePublishedPosts($data);

        $posts->getCollection()->transform(
            fn ($post) => $this->postDataMapper->forApiIndex($post)
        );

        return $posts;
    }
}
