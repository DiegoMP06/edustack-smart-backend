<?php

namespace App\Policies;

use App\Models\Events\Event;
use App\Models\Events\EventActivity;
use App\Models\User;

class EventActivityPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return null;
    }

    public function view(User $user, EventActivity $activity): bool
    {
        if ($activity->event?->user_id === $user->id) {
            return true;
        }

        return $activity->event?->collaborators()->where('users.id', $user->id)->exists() ?? false;
    }

    public function create(User $user, Event $event): bool
    {
        return $event->user_id === $user->id;
    }

    public function update(User $user, EventActivity $activity): bool
    {
        return $activity->event?->user_id === $user->id;
    }

    public function delete(User $user, EventActivity $activity): bool
    {
        return $activity->event?->user_id === $user->id;
    }
}
