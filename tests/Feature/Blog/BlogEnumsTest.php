<?php

use App\Models\Blog\PostType;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('post type slug is a string (enum cast removed for compatibility with sluggable)', function () {
    $postType = PostType::factory()->create([
        'slug' => 'news',
    ]);

    expect($postType->slug)->toBe('news');
});
