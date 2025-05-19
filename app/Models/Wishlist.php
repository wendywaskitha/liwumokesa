<?php

// app/Models/Wishlist.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'wishable_id', 'wishable_type',
        'notes', 'priority'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wishable()
    {
        return $this->morphTo();
    }
}
