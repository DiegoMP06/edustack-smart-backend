<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Blog\PostCollection;
use App\Models\Blog\Post;
use App\Concerns\ApiQueryable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ApiBlogController extends Controller
{
    use ApiQueryable;

    /**
     * Handle the incoming request.
     */
    public function index(Request $request)
    {
        $posts = $this->buildQuery(
            Post::where('is_published', true),
            defaultIncludes: [
                'categories',
                'type',
                'media',
                'author',
            ]
        )->paginate(20)->withQueryString();

        return new PostCollection($posts);
    }

    public function show(Request $request, Post $post)
    {
        if (!$post->is_published) {
            return response()->json(['message' => 'Post not found.'], 404);
        }

        $ipAddress = $request->ip();
        $userAgent = $request->header('User-Agent');

        $isBot = preg_match('/bot|crawl|slurp|spider|mediapartners/i', $userAgent);

        if (!$isBot) {
            $viewerHash = md5($ipAddress . $userAgent);
            $cacheKey = "post_{$post->id}_viewed_by_{$viewerHash}";

            if (!Cache::has($cacheKey)) {
                $post->increment('views_count');
                Cache::put($cacheKey, true, now()->addHours(2));
            }
        }

        return response()->json(
            (new PostCollection([
                $post->load([
                    'categories',
                    'type',
                    'media',
                    'author',
                ]),
            ]))->first()
        );
    }
}
