<?php

namespace App\Traits;

use App\Models\Wishlist;

trait Wishable
{
    public function wishlists()
    {
        return $this->morphMany(Wishlist::class, 'wishable');
    }

    public function isWishedBy($user)
    {
        if (!$user) return false;

        return $this->wishlists()
            ->where('user_id', $user->id)
            ->exists();
    }
}
