<?php

// app/Models/Gallery.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'imageable_id', 'imageable_type', 'file_path',
        'caption', 'is_featured', 'order'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'order' => 'integer'
    ];

    public function imageable()
    {
        return $this->morphTo();
    }

    public function getFilePathAttribute($value)
    {
        return str_replace('public/', '', $value);
    }
}
