<?php

namespace App\Modules\Blog\Infrastructure\Analytics;

use App\Models\Blog\Post;
use App\Modules\Blog\Domain\Contracts\PostViewCounter;
use Illuminate\Support\Facades\Cache;

class CachePostViewCounter implements PostViewCounter
{
    public function incrementIfUnique(Post $post, string $ip, string $userAgent): void
    {
        $isBot = preg_match('/bot|crawl|slurp|spider|mediapartners/i', $userAgent);

        if ($isBot) {
            return;
        }

        $viewerHash = md5($ip.$userAgent);
        $cacheKey = "post_{$post->id}_viewed_by_{$viewerHash}";

        if (! Cache::has($cacheKey)) {
            $post->increment('views_count');
            Cache::put($cacheKey, true, now()->addHours(2));
        }
    }
}
