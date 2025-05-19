<?php

// app/Models/District.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'featured_image'
    ];

    public function destinations()
    {
        return $this->hasMany(Destination::class);
    }

    public function accommodations()
    {
        return $this->hasMany(Accommodation::class);
    }

    public function transportations()
    {
        return $this->hasMany(Transportation::class);
    }

    public function culinaries()
    {
        return $this->hasMany(Culinary::class);
    }

    public function creativeEconomies()
    {
        return $this->hasMany(CreativeEconomy::class);
    }

    public function culturalHeritages()
    {
        return $this->hasMany(CulturalHeritage::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function amenities()
    {
        return $this->hasMany(Amenity::class);
    }
}
