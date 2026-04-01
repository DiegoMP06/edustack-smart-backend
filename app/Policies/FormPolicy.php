<?php

namespace App\Policies;

use App\Models\Forms\Form;
use App\Models\User;

class FormPolicy
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
        return $user->exists;
    }

    public function view(User $user, Form $form): bool
    {
        return $form->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->exists;
    }

    public function update(User $user, Form $form): bool
    {
        return $form->user_id === $user->id;
    }

    public function delete(User $user, Form $form): bool
    {
        return $form->user_id === $user->id;
    }

    public function viewResponses(User $user, Form $form): bool
    {
        return $form->user_id === $user->id;
    }

    public function gradeResponses(User $user, Form $form): bool
    {
        return $form->user_id === $user->id;
    }
}
