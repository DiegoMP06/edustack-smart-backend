<?php

namespace App\Modules\Blog\Domain\Policies;

use App\Models\Blog\Post;
use App\Models\User;

class PostPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->exists && $user->is_active;
    }

    public function view(User $user, Post $post): bool
    {
        return $user->is_active && $post->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->is_active && $user->exists;
    }

    public function update(User $user, Post $post): bool
    {
        return $user->is_active && $post->user_id === $user->id;
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->is_active && $post->user_id === $user->id;
    }
}
