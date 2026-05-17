<?php

namespace App\Modules\Blog\Application\Support;

use App\Models\Blog\Post;
use App\Modules\Blog\Application\DTOs\PostData;

/**
 * Centraliza todos los contextos de serialización de Post.
 * Cada método define exactamente qué relaciones se cargan
 * y qué Lazy properties se incluyen para ese contexto específico.
 * Evita N+1 y lazy-loading inesperado.
 */
class PostDataMapper
{
    /** Vista del index del usuario autenticado (gestión interna). */
    public function forIndex(Post $post): PostData
    {
        return PostData::fromModel(
            $post->load(['type', 'categories', 'media'])
        )->include('type', 'categories', 'media');
    }

    /** Vista de detalle interno (show del usuario autenticado). */
    public function forShow(Post $post): PostData
    {
        return PostData::fromModel(
            $post->load(['categories', 'type', 'media', 'author'])
        )->include('categories', 'type', 'media', 'author');
    }

    /** Vista del formulario de edición de metadatos. */
    public function forEdit(Post $post): PostData
    {
        return PostData::fromModel(
            $post->load(['categories', 'type', 'media'])
        )->include('categories', 'type', 'media');
    }

    /**
     * Vista del editor de contenido (TipTap / rich text).
     * Solo campos base — sin relaciones pesadas.
     */
    public function forContent(Post $post): PostData
    {
        return PostData::fromModel($post);
    }

    /** Vista del index público de la API. */
    public function forApiIndex(Post $post): PostData
    {
        return PostData::fromModel(
            $post->load(['categories', 'type', 'media', 'author'])
        )->include('categories', 'type', 'media', 'author');
    }

    /** Vista de detalle público de la API. */
    public function forApiShow(Post $post): PostData
    {
        return PostData::fromModel(
            $post->load(['categories', 'type', 'media', 'author'])
        )->include('categories', 'type', 'media', 'author');
    }
}
