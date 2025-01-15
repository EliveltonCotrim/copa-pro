<?php

namespace App\Policies;

use App\Models\Championship;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ChampionshipPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('championships:list');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Championship $championship): bool
    {
        return $user->hasPermissionTo('championships:view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('championships:delete');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Championship $championship): bool
    {
        return $user->hasPermissionTo('championships:edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Championship $championship): bool
    {
        return $user->hasPermissionTo('championships:delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Championship $championship): bool
    {
        return $user->hasPermissionTo('championships:restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Championship $championship): bool
    {
        return $user->hasPermissionTo('championships:forceDelete');
    }
}
