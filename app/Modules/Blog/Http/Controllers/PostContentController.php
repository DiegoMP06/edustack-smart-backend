<?php

namespace App\Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Blog\Post;
use App\Modules\Blog\DTOs\PostContentData;
use App\Modules\Blog\Http\Requests\UpdatePostContentRequest;
use App\Modules\Blog\Services\PostContentService;
use Illuminate\Http\Request;

class PostContentController extends Controller
{
    public function __construct(
        private PostContentService $contentService,
    ) {}

    /**
     * Show the content editor for the given model.
     */
    public function edit(Post $post, Request $request)
    {
        $this->authorize('update', $post);

        return inertia('blog/post-content', [
            'post' => $post,
            'edit' => $request->boolean('edit', false),
            'message' => $request->session()->get('message'),
        ]);
    }

    /**
     * Persist editor content for the given model.
     */
    public function update(Post $post, UpdatePostContentRequest $request)
    {
        $this->authorize('update', $post);

        $data = PostContentData::fromArray($request->validated());
        $this->contentService->update($post, $data);

        return back()->with('message', 'Post content saved successfully.');
    }
}
