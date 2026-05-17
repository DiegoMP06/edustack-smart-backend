<?php

namespace App\Modules\Projects\Domain\Policies;

use App\Models\Projects\Project;
use App\Models\User;

class ProjectPolicy
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

    public function view(User $user, Project $project): bool
    {
        return $user->is_active && ($project->user_id === $user->id || $project->collaborators->contains($user));
    }

    public function create(User $user): bool
    {
        return $user->is_active && $user->exists;
    }

    public function update(User $user, Project $project): bool
    {
        return $user->is_active && $project->user_id === $user->id;
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->is_active && $project->user_id === $user->id;
    }
}
