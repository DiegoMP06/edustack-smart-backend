<?php

namespace App\Modules\Forms\Policies;

use App\Models\Forms\Form;
use App\Models\User;

class FormPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('form.view-any');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Form $form): bool
    {
        return $user->can('form.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('form.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Form $form): bool
    {
        return $user->can('form.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Form $form): bool
    {
        return $user->can('form.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Form $form): bool
    {
        return $user->can('form.restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Form $form): bool
    {
        return $user->can('form.force-delete');
    }
}
