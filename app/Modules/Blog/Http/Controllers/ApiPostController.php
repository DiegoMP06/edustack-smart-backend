<?php

namespace App\Modules\Blog\Http\Controllers;

use App\Models\Blog\Post;
use App\Modules\Blog\Application\UseCases\Query\ListPublishedPostsAction;
use App\Modules\Blog\Application\UseCases\Query\ShowPublishedPostAction;
use App\Modules\Blog\Http\Resources\PostCollection;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiPostController extends Controller
{
    public function __construct(
        protected ListPublishedPostsAction $listPublishedPostsAction,
        protected ShowPublishedPostAction $showPublishedPostAction,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $data = ListCollectionQueryParamsData::fromRequest($request);
        $posts = $this->listPublishedPostsAction->execute($data);

        return response()->json(new PostCollection($posts));
    }

    /**
     * @fix Extrae ip y userAgent aquí (Http layer) antes de pasarlos
     * al use case, manteniendo Application layer libre de Request HTTP.
     */
    public function show(Request $request, Post $post): JsonResponse
    {
        $postData = $this->showPublishedPostAction->execute(
            post: $post,
            ip: $request->ip() ?? '',
            userAgent: $request->userAgent() ?? '',
        );

        return response()->json($postData);
    }
}
