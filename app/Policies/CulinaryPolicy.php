<?php

namespace App\Policies;

use App\Models\Culinary;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CulinaryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_culinaries') ||
               $user->hasPermission('manage_culinaries') ||
               $user->role === 'admin';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Culinary $culinary): bool
    {
        return $user->hasPermission('view_culinaries') ||
               $user->hasPermission('manage_culinaries') ||
               $user->role === 'admin';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('manage_culinaries') ||
               $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Culinary $culinary): bool
    {
        return $user->hasPermission('manage_culinaries') ||
               $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Culinary $culinary): bool
    {
        return $user->hasPermission('manage_culinaries') ||
               $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Culinary $culinary): bool
    {
        return $user->hasPermission('manage_culinaries') ||
               $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Culinary $culinary): bool
    {
        return $user->hasPermission('manage_culinaries') ||
               $user->role === 'admin';
    }
}
