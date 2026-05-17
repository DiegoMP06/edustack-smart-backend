<?php

namespace App\Modules\Blog\Http\Controllers;

use App\Models\Blog\Post;
use App\Modules\Blog\Application\DTOs\DraftPostFormData;
use App\Modules\Blog\Application\Support\PostDataMapper;
use App\Modules\Blog\Application\UseCases\Command\CreatePostAction;
use App\Modules\Blog\Application\UseCases\Command\DeletePostAction;
use App\Modules\Blog\Application\UseCases\Command\UpdatePostAction;
use App\Modules\Blog\Application\UseCases\Query\GetPostFormOptionsAction;
use App\Modules\Blog\Application\UseCases\Query\ListUserPostsAction;
use App\Modules\Blog\Http\Requests\StorePostRequest;
use App\Modules\Blog\Http\Requests\UpdatePostRequest;
use App\Modules\Blog\Http\Resources\PostCollection;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class PostController extends Controller
{
    public function __construct(
        protected ListUserPostsAction $listUserPostsAction,
        protected GetPostFormOptionsAction $getPostFormOptionsAction,
        protected PostDataMapper $postDataMapper,
    ) {}

    private function forCreateForm(): array
    {
        return $this->getPostFormOptionsAction->execute();
    }

    private function forEditForm(): array
    {
        return $this->forCreateForm();
    }

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Post::class);

        $data = ListCollectionQueryParamsData::fromRequest($request);
        $posts = $this->listUserPostsAction->execute($data, $request->user());

        return inertia('blog/blog', [
            'posts' => new PostCollection($posts),
            'filter' => $request->query('filter'),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Post::class);

        return inertia('blog/create-post', $this->forCreateForm());
    }

    public function store(
        StorePostRequest $request,
        CreatePostAction $action,
    ): RedirectResponse {
        $this->authorize('create', Post::class);

        $data = DraftPostFormData::from($request->validated());
        $post = $action->execute($data, $request->user());

        return redirect()->intended(
            route('posts.content.edit', ['post' => $post, 'edit' => false], false)
        )->with('message', 'Post creado correctamente.');
    }

    public function show(Post $post): Response
    {
        $this->authorize('view', $post);

        return inertia('blog/show-post', [
            'post' => $this->postDataMapper->forShow($post),
        ]);
    }

    /** @fix Usa el $request inyectado en lugar del helper global request(). */
    public function edit(Post $post, Request $request): Response
    {
        $this->authorize('update', $post);

        return inertia('blog/edit-post', [
            ...$this->forEditForm(),
            'post' => $this->postDataMapper->forEdit($post),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function update(
        UpdatePostRequest $request,
        Post $post,
        UpdatePostAction $action,
    ): RedirectResponse {
        $this->authorize('update', $post);

        $data = DraftPostFormData::from($request->validated());
        $action->execute($post, $data);

        return back()->with('message', 'Post actualizado correctamente.');
    }

    public function destroy(
        Post $post,
        DeletePostAction $action,
    ): RedirectResponse {
        $this->authorize('delete', $post);

        $action->execute($post);

        return back()->with('message', 'Post eliminado correctamente.');
    }
}
