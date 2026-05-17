<?php

namespace App\Modules\Blog\Http\Controllers;

use App\Models\Blog\Post;
use App\Modules\Blog\Application\Support\PostDataMapper;
use App\Modules\Blog\Application\UseCases\Command\UpdatePostContentAction;
use App\Modules\Shared\DTOs\Content\ModelContentFormData;
use App\Modules\Shared\Http\Requests\UpdateModelContentRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class PostContentController extends Controller
{
    public function __construct(
        protected PostDataMapper $postDataMapper,
    ) {}

    /** @fix Usa PostDataMapper::forContent() en lugar de PostData::from() directo. */
    public function edit(Post $post, Request $request): Response
    {
        $this->authorize('update', $post);

        return inertia('blog/post-content', [
            'post' => $this->postDataMapper->forContent($post),
            'edit' => $request->boolean('edit', false),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function update(
        Post $post,
        UpdateModelContentRequest $request,
        UpdatePostContentAction $action,
    ): RedirectResponse {
        $this->authorize('update', $post);

        $edit = $request->boolean('edit', false);
        $data = ModelContentFormData::from($request->validated());
        $action->execute($post, $data);

        $route = $edit
            ? back()
            : redirect()->intended(route('posts.index', absolute: false));

        return $route->with('message', 'Contenido guardado correctamente.');
    }
}
