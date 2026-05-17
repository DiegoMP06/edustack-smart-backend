<?php

namespace App\Modules\Blog\Http\Controllers;

use App\Models\Blog\Post;
use App\Modules\Blog\Application\UseCases\Command\TogglePostStatusAction;
use Illuminate\Http\RedirectResponse;

class PostStatusController extends Controller
{
    /**
     * Toggle the model status flag.
     */
    public function __invoke(
        Post $post,
        TogglePostStatusAction $action
    ): RedirectResponse {
        $this->authorize('update', $post);

        $action->execute($post);

        return back()->with('message', 'Post actualizado correctamente.');
    }
}
