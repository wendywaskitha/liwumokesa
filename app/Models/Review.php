<?php

// app/Models/Review.php

namespace App\Models;

use App\Models\ReviewImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'reviewable_id', 'reviewable_type', 'user_id',
        'rating', 'comment', 'status'
    ];

    public function reviewable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function responses()
    {
        return $this->hasMany(ReviewResponse::class);
    }

    // Relasi ke ReviewImage
    public function images()
    {
        return $this->hasMany(ReviewImage::class);
    }
}
