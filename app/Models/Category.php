<?php

namespace App\Models;

use App\Models\Event;
use App\Models\Culinary;
use App\Models\Destination;
use App\Models\CreativeEconomy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'parent_id', // Tambahkan parent_id untuk hierarki kategori
        'icon',
        'color',    // Tambahkan color
        'description'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Relasi ke kategori induk (self relation)
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Relasi ke sub-kategori
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Relasi ke destinations
     */
    public function destinations()
    {
        return $this->hasMany(Destination::class);
    }

    /**
     * Relasi ke culinaries
     */
    public function culinaries()
    {
        return $this->hasMany(Culinary::class);
    }

    /**
     * Relasi ke creative economies
     */
    public function creativeEconomies()
    {
        return $this->hasMany(CreativeEconomy::class);
    }

    /**
     * Relasi ke events
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Atribut untuk mendapatkan jenis kategori dalam bentuk label
     */
    public function getTypeNameAttribute()
    {
        return match($this->type) {
            'destination' => 'Destinasi',
            'culinary' => 'Kuliner',
            'creative_economy' => 'Ekonomi Kreatif',
            'event' => 'Acara/Event',
            'other' => 'Lainnya',
            default => $this->type,
        };
    }
}
