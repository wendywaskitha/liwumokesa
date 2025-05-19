<?php

namespace App\Models;

use App\Models\Review;
use Illuminate\Database\Eloquent\Model;

class ReviewImage extends Model
{
    protected $fillable = [
        'review_id',
        'image_path'
    ];

    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}
