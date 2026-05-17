<?php

namespace App\Modules\Blog\Domain\Contracts;

use App\Models\Blog\Post;

/**
 * Contrato para contar vistas únicas de un Post.
 *
 * Recibe primitivos en lugar de Request para mantener
 * el Domain libre de dependencias HTTP del framework.
 * El Controller extrae ip y userAgent antes de llamar al use case.
 */
interface PostViewCounter
{
    public function incrementIfUnique(Post $post, string $ip, string $userAgent): void;
}
