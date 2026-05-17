<?php

namespace App\Modules\Blog\Application\UseCases\Query;

use App\Models\User;
use App\Modules\Blog\Application\Support\PostDataMapper;
use App\Modules\Blog\Domain\Contracts\PostReadRepository;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;
use Illuminate\Pagination\LengthAwarePaginator;

class ListUserPostsAction
{
    public function __construct(
        private PostReadRepository $postReadRepository,
        private PostDataMapper $postDataMapper,
    ) {}

    public function execute(ListCollectionQueryParamsData $data, User $user): LengthAwarePaginator
    {
        $posts = $this->postReadRepository->paginateUserPosts($user, $data);

        $posts->getCollection()->transform(
            fn ($post) => $this->postDataMapper->forIndex($post)
        );

        return $posts;
    }
}
