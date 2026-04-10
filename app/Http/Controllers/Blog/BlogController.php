<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\StorePostRequest;
use App\Http\Requests\Blog\UpdatePostRequest;
use App\Http\Resources\Blog\PostCollection;
use App\Models\Blog\Post;
use App\Models\Blog\PostCategory;
use App\Models\Blog\PostType;
use App\Concerns\ApiQueryable;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlogController extends Controller
{
    use ApiQueryable;

    private function formData(): array
    {
        return [
            'types' => PostType::orderBy('order')->get(),
            'categories' => PostCategory::orderBy('order')->get(),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Post::class);

        $posts = $this->buildQuery(
            $request->user()->posts(),
            defaultIncludes: ['type', 'categories', 'media']
        )->paginate(20)->withQueryString();

        return Inertia::render('blog/blog', [
            'posts' => new PostCollection($posts),
            'filter' => $request->query('filter'),
            'message' => $request->session()->get('message'),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Post::class);

        return Inertia::render('blog/create-post', $this->formData());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $this->authorize('create', Post::class);

        $data = $request->validated();

        $post = $request->user()->posts()->create([
            ...$data,
            'content' => [],
        ]);

        $post->categories()->sync($data['categories']);

        foreach ($data['images'] as $key) {
            $post->addMediaFromDisk($key, 's3')
                ->toMediaCollection('gallery');
        }

        return redirect()->intended(
            route('posts.content.edit', ['post' => $post, 'edit' => false], false)
        )->with('message', 'Post creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $this->authorize('view', $post);

        return Inertia::render('blog/show-post', [
            'post' => (new PostCollection([$post->load(['categories', 'type', 'media', 'author'])]))->first(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post, Request $request)
    {
        $this->authorize('update', $post);

        return Inertia::render('blog/edit-post', [
            ...$this->formData(),
            'post' => (new PostCollection([$post->load(['categories', 'type', 'media'])]))->first(),
            'message' => $request->session()->get('message'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $this->authorize('update', $post);

        $data = $request->validated();

        $post->update($data);

        $post->categories()->sync($data['categories']);

        return back()->with('message', 'Post actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return back()->with('message', 'Post eliminado correctamente.');
    }
}
