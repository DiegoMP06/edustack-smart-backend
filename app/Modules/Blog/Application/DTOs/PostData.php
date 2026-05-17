<?php

namespace App\Modules\Blog\Application\DTOs;

use App\Models\Blog\Post;
use App\Modules\Admin\DTOs\UserData;
use App\Modules\Blog\Application\DTOs\PostCategoryData;
use App\Modules\Blog\Application\DTOs\PostTypeData;
use App\Modules\Media\DTOs\MediaData;
use DateTimeInterface;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;
use Spatie\TypeScriptTransformer\Attributes\TypeScriptType;

#[TypeScript]
class PostData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public string $description,
        #[LiteralTypeScriptType('Record<string, unknown>')]
        public array $content,
        public int $views_count,
        public int $reading_time_minutes,
        public bool $is_featured,
        public bool $is_published,
        #[TypeScriptType('string')]
        public ?DateTimeInterface $published_at,
        public int $post_type_id,
        public int $user_id,
        #[TypeScriptType('string')]
        public DateTimeInterface $created_at,
        #[TypeScriptType('string')]
        public ?DateTimeInterface $updated_at,
        #[TypeScriptType('UserData|null')]
        public Lazy|UserData|null $author = null,
        #[TypeScriptType('PostTypeData|null')]
        public Lazy|PostTypeData|null $type = null,
        #[DataCollectionOf(PostCategoryData::class)]
        #[TypeScriptType('Array<PostCategoryData>|null')]
        public Lazy|DataCollection|null $categories = null,
        /** @var array<MediaData> */
        #[DataCollectionOf(MediaData::class)]
        #[TypeScriptType('Array<MediaData>|null')]
        public Lazy|DataCollection|null $media = null,
    ) {}

    public static function fromModel(Post $post): self
    {
        return new self(
            id: $post->id,
            name: $post->name,
            slug: $post->slug,
            description: $post->description,
            content: $post->content,
            views_count: $post->views_count,
            reading_time_minutes: $post->reading_time_minutes,
            is_featured: $post->is_featured,
            is_published: $post->is_published,
            published_at: $post->published_at,
            post_type_id: $post->post_type_id,
            user_id: $post->user_id,
            created_at: $post->created_at,
            updated_at: $post->updated_at,
            author: Lazy::create(fn () => $post->author ? UserData::from($post->author) : null),
            type: Lazy::create(fn () => $post->type ? PostTypeData::from($post->type) : null),
            categories: Lazy::create(fn () => PostCategoryData::collect($post->categories)),
            media: Lazy::create(fn () => MediaData::collect(
                $post->getMedia('gallery')->map(fn ($m) => MediaData::fromModel(
                    $m,
                    'main',
                    [
                        'main' => ['width' => 1200, 'height' => 620],
                        'hero' => ['width' => 1920, 'height' => 1080],
                    ],
                    ['hero']
                ))
            )),
        );
    }
}
