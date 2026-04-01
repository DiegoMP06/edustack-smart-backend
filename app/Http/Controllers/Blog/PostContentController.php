<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\UpdatePostContentRequest;
use App\Models\Blog\Post;
use Illuminate\Http\Request;

class PostContentController extends Controller
{
    public function edit(Post $post, Request $request)
    {
        $this->authorize('update', $post);

        $edit = $request->boolean('edit', false);

        return inertia('blog/post-content', [
            'post' => $post,
            'edit' => $edit,
            'message' => request()->session()->get('message'),
        ]);
    }

    public function update(Post $post, UpdatePostContentRequest $request)
    {
        $this->authorize('update', $post);

        $edit = $request->boolean('edit', false);
        $data = $request->validated();

        $post->content = $data['content'];
        $post->save();

        $route = $edit ?
            back() :
            redirect()->intended(route('posts.index', absolute: false));

        return $route->with('message', 'Contenido guardado correctamente.');
    }
}
