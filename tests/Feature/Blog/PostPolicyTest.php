<?php

use App\Models\Blog\Post;
use App\Models\Blog\PostCategory;
use App\Models\Blog\PostType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config(['scout.driver' => null]);

    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'teacher']);
    Role::firstOrCreate(['name' => 'member']);
});

it('forbids non owners from viewing and updating posts', function (): void {
    $owner = User::factory()->member()->create();
    $otherUser = User::factory()->member()->create();

    $post = Post::factory()->for($owner)->create();
    $type = PostType::factory()->create();
    $categories = PostCategory::factory()->count(2)->create();

    $payload = [
        'name' => 'Post actualizado',
        'summary' => 'Resumen de prueba con suficiente longitud para cumplir validación.',
        'reading_time_minutes' => 6,
        'post_type_id' => $type->id,
        'categories' => $categories->pluck('id')->toArray(),
    ];

    $this->actingAs($otherUser)
        ->get(route('posts.show', $post))
        ->assertForbidden();

    $this->actingAs($otherUser)
        ->patch(route('posts.update', $post), $payload)
        ->assertForbidden();

    $this->actingAs($otherUser)
        ->patch(route('posts.status', $post))
        ->assertForbidden();
});

it('allows admins to manage posts they do not own', function (): void {
    $owner = User::factory()->member()->create();
    $admin = User::factory()->admin()->create();

    $post = Post::factory()->for($owner)->create([
        'is_published' => false,
    ]);

    $this->actingAs($admin)
        ->patch(route('posts.status', $post))
        ->assertRedirect();

    $post->refresh();

    expect($post->is_published)->toBeTrue();
});
