<?php

namespace App\Modules\Blog\Application\UseCases\Query;

use App\Models\Blog\Post;
use App\Modules\Blog\Application\DTOs\PostData;
use App\Modules\Blog\Application\Support\PostDataMapper;
use App\Modules\Blog\Domain\Contracts\PostViewCounter;

class ShowPublishedPostAction
{
    public function __construct(
        private PostViewCounter $postViewCounter,
        private PostDataMapper $postDataMapper,
    ) {}

    /**
     * El controller extrae ip y userAgent del Request antes de llamar
     * este use case, manteniendo Application libre de dependencias HTTP.
     */
    public function execute(Post $post, string $ip, string $userAgent): PostData
    {
        abort_if(! $post->is_published, 404, 'Publicación no encontrada.');

        $this->postViewCounter->incrementIfUnique($post, $ip, $userAgent);

        return $this->postDataMapper->forApiShow($post);
    }
}
