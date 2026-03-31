<?php

use App\Models\Blog\Post;
use App\Models\Blog\PostCategory;
use App\Models\Blog\PostType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    config(['scout.driver' => null]);
    Storage::fake('s3');
    Role::create(['name' => 'admin']);
    Role::create(['name' => 'teacher']);
    Role::create(['name' => 'member']);
});

test('user can see list of their posts', function () {
    $this->withoutExceptionHandling();
    $user = User::factory()->admin()->create();
    $posts = Post::factory()->count(3)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get(route('posts.index'));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('blog/blog')
        ->has('posts.data', 3)
    );
});

test('user can create a post', function () {
    Storage::fake('s3');
    $user = User::factory()->admin()->create();
    $type = PostType::factory()->create();
    $categories = PostCategory::factory()->count(2)->create();

    $data = [
        'name' => 'New Post Name',
        'summary' => 'This is a summary with at least fifty characters in it to pass validation.',
        'post_type_id' => $type->id,
        'categories' => $categories->pluck('id')->toArray(),
        'images' => [
            UploadedFile::fake()->image('post1.jpg'),
        ],
    ];

    $response = $this->actingAs($user)->post(route('posts.store'), $data);

    $post = Post::where('name', 'New Post Name')->first();
    expect($post)->not->toBeNull();
    expect($post->categories)->toHaveCount(2);
    expect($post->getMedia('gallery'))->toHaveCount(1);

    $response->assertRedirect(route('posts.content.edit', ['post' => $post, 'edit' => false]));
});

test('user can update a post', function () {
    $user = User::factory()->admin()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);
    $newType = PostType::factory()->create();
    $newCategories = PostCategory::factory()->count(2)->create();

    $data = [
        'name' => 'Updated Post Name',
        'summary' => 'Updated summary with at least fifty characters in it to pass validation.',
        'post_type_id' => $newType->id,
        'categories' => $newCategories->pluck('id')->toArray(),
    ];

    $response = $this->actingAs($user)->patch(route('posts.update', $post), $data);

    $post->refresh();
    expect($post->name)->toBe('Updated Post Name');
    expect($post->post_type_id)->toBe($newType->id);
    expect($post->categories)->toHaveCount(2);

    $response->assertRedirect();
});

test('user can delete a post', function () {
    $user = User::factory()->admin()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->delete(route('posts.destroy', $post));

    expect(Post::find($post->id))->toBeNull();
    $response->assertRedirect();
});
