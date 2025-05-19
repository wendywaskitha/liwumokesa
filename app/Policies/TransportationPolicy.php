<?php

namespace App\Policies;

use App\Models\Transportation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TransportationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_transportations') ||
               $user->hasPermission('manage_transportations') ||
               $user->role === 'admin';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Transportation $transportation): bool
    {
        return $user->hasPermission('view_transportations') ||
               $user->hasPermission('manage_transportations') ||
               $user->role === 'admin';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('manage_transportations') ||
               $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Transportation $transportation): bool
    {
        return $user->hasPermission('manage_transportations') ||
               $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Transportation $transportation): bool
    {
        return $user->hasPermission('manage_transportations') ||
               $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Transportation $transportation): bool
    {
        return $user->hasPermission('manage_transportations') ||
               $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Transportation $transportation): bool
    {
        return $user->hasPermission('manage_transportations') ||
               $user->role === 'admin';
    }
}
