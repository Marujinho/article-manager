<?php

namespace App\Policies;

use App\Models\Preference\Preference;
use App\Models\User\User;
use Illuminate\Auth\Access\Response;

class PreferencePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Preference $preference): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Preference $preference): bool
    {
        return $user->id === $preference->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Preference $preference): bool
    {
        return $user->id === $preference->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Preference $preference): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Preference $preference): bool
    {
        return false;
    }
}
