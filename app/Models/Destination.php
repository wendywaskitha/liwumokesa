<?php
// app/Models/Destination.php

namespace App\Models;

use App\Models\Amenity;
use App\Models\Culinary;
use App\Models\TourGuide;
use Illuminate\Support\Str;
use App\Models\Transportation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'slug',
        'description',
        'type',
        'location',
        'district_id',
        'latitude',
        'longitude',
        'visiting_hours',
        'entrance_fee',
        'facilities',
        'website',
        'contact',
        'best_time_to_visit',
        'tips',
        'featured_image',
        'is_featured',
        'status'
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'entrance_fee' => 'float',
        'is_featured' => 'boolean',
        'status' => 'boolean',
        'facilities' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function transportations()
    {
        return $this->belongsToMany(Transportation::class)
            ->withPivot(['service_type', 'notes'])
            ->withTimestamps();
    }

    public function culinaries()
    {
        return $this->belongsToMany(Culinary::class, 'destination_culinary')
            ->withPivot(['service_type', 'is_recommended', 'notes', 'sort_order'])
            ->orderBy('sort_order')
            ->withTimestamps();
    }

    public function tourGuides()
    {
        return $this->belongsToMany(TourGuide::class, 'destination_tour_guide')
                    ->withPivot(['specialization', 'price', 'notes'])
                    ->withTimestamps();
    }

    /**
     * Get the amenities for the destination.
     */
    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'destination_amenity', 'destination_id', 'amenity_id')
            ->withTimestamps();
    }

    public function galleries()
    {
        return $this->morphMany(Gallery::class, 'imageable');
    }


    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    // public function nearbyAccommodations()
    // {
    //     // Logic to find nearby accommodations based on coordinates
    //     return Accommodation::select()
    //         ->whereRaw("ST_Distance_Sphere(
    //             point(longitude, latitude),
    //             point(?, ?)
    //         ) <= ?", [$this->longitude, $this->latitude, 5000]) // 5km radius
    //         ->get();
    // }

    public function nearbyAccommodations()
    {
        return $this->belongsToMany(Accommodation::class, 'destination_accommodation')
            ->withPivot(['distance', 'is_recommended', 'notes'])
            ->withTimestamps();
    }

    public function getFacilitiesAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?: [];
        }

        return $value ?: [];
    }

    public function getFeaturedImageAttribute($value)
    {
        if (!$value) {
            return null;
        }

        // If the value is already a full path, return it
        if (Str::startsWith($value, 'destinations/')) {
            return $value;
        }

        // Otherwise, prepend the destinations/ directory
        return 'destinations/' . $value;
    }
}
