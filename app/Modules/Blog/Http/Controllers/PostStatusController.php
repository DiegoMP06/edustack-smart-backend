<?php

namespace App\Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Blog\Post;
use App\Modules\Blog\Services\PostStatusService;

class PostStatusController extends Controller
{
    public function __construct(
        private PostStatusService $statusService,
    ) {}

    /**
     * Toggle the model status flag.
     */
    public function __invoke(Post $post)
    {
        $this->authorize('update', $post);

        $this->statusService->toggle($post);

        return back()->with('message', 'Post status updated successfully.');
    }
}
