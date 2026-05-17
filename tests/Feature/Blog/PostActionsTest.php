<?php

use App\Models\Blog\Post;
use App\Models\Blog\PostCategory;
use App\Models\Blog\PostType;
use App\Models\User;
use App\Modules\Blog\Application\DTOs\DraftPostFormData;
use App\Modules\Blog\Application\UseCases\Command\CreatePostAction;
use App\Modules\Blog\Application\UseCases\Query\ListUserPostsAction;
use App\Modules\Shared\DTOs\Query\ListCollectionQueryParamsData;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->type = PostType::factory()->create();
    $this->category = PostCategory::factory()->create();
});

it('creates a post for a user via action', function () {
    $data = new DraftPostFormData(
        name: 'Test Post',
        description: 'Description',
        images: [],
        reading_time_minutes: 5,
        post_type_id: $this->type->id,
        categories: [$this->category->id],
    );

    $action = app(CreatePostAction::class);
    $post = $action->execute($data, $this->user);

    expect($post)->toBeInstanceOf(Post::class)
        ->and($post->name)->toBe('Test Post')
        ->and($post->user_id)->toBe($this->user->id)
        ->and($post->categories->pluck('id')->toArray())->toContain($this->category->id);
});

it('lists user posts via action', function () {
    $otherUser = User::factory()->create();

    Post::factory()->count(3)->create(['user_id' => $this->user->id]);
    Post::factory()->count(2)->create(['user_id' => $otherUser->id]);

    $action = app(ListUserPostsAction::class);
    $params = new ListCollectionQueryParamsData(filter: [], per_page: 10);

    $result = $action->execute($params, $this->user);

    expect($result)->toHaveCount(3)
        ->and($result->first()->name)->not->toBeNull();
});
