<?php

// app/Models/User.php

namespace App\Models;

use Filament\Panel;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'address',
        'profile_image',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function itineraries()
    {
        return $this->hasMany(Itinerary::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isWisatawan()
    {
        return $this->role === 'wisatawan';
    }

    // Tambahkan method untuk mengelola roles dan permissions
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function hasRole($roleName)
    {
        foreach ($this->roles as $role) {
            if ($role->name === $roleName) {
                return true;
            }
        }
        return false;
    }

    public function hasPermission($permission)
    {
        foreach ($this->roles as $role) {
            // Check if permissions is already an array
            $permissions = $role->permissions;

            // If it's a string (stored JSON), decode it
            if (is_string($permissions)) {
                $permissions = json_decode($permissions, true);
            }

            // If permissions is null, convert to empty array
            if ($permissions === null) {
                $permissions = [];
            }

            // Now check if the permission exists
            if (is_array($permissions) && in_array($permission, $permissions)) {
                return true;
            }
        }
        return false;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'role', 'is_active'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Pengguna {$this->name} telah {$eventName}");
    }

    // Accessor untuk profile photo url
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_image) {
            return asset('storage/' . $this->profile_image);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // Opsi 1: Hanya admin yang bisa akses
        // return $this->isAdmin();

        // Opsi 2: Semua user bisa akses (untuk testing)
        return true;

        // Opsi 3: Berdasarkan email domain tertentu
        // return str_ends_with($this->email, '@yourdomain.com');
    }
}
