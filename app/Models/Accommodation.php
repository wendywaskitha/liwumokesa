<?php

// app/Models/Accommodation.php

namespace App\Models;

use Illuminate\Support\Str;
use App\Models\CulturalHeritage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Accommodation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'type', 'description', 'address',
        'latitude', 'longitude', 'district_id', 'price_range_start',
        'price_range_end', 'facilities', 'contact_person',
        'phone_number', 'email', 'website', 'booking_link',
        'featured_image', 'status'
    ];

    protected $casts = [
        'facilities' => 'array',
        'status' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'price_range_start' => 'float',
        'price_range_end' => 'float'
    ];

    // Generate slug dari nama jika tidak ada slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($accommodation) {
            if (empty($accommodation->slug)) {
                $accommodation->slug = Str::slug($accommodation->name);
            }
        });

        static::updating(function ($accommodation) {
            if (empty($accommodation->slug)) {
                $accommodation->slug = Str::slug($accommodation->name);
            }
        });
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function galleries()
    {
        return $this->morphMany(Gallery::class, 'imageable');
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function destinations()
    {
        return $this->belongsToMany(Destination::class, 'destination_accommodation')
            ->withPivot(['distance', 'is_recommended', 'notes'])
            ->withTimestamps();
    }

    public function culturalHeritages()
    {
        return $this->belongsToMany(CulturalHeritage::class, 'accommodation_cultural_heritage')
            ->withPivot(['partnership_type', 'is_recommended', 'notes'])
            ->withTimestamps();
    }

    // Method untuk mendapatkan akomodasi di sekitar
    public function nearbyDestinations($distance = 5) // dalam km
    {
        return Destination::select()
            ->selectRaw('
                ( 6371 * acos( cos( radians(?) ) *
                cos( radians( latitude ) ) *
                cos( radians( longitude ) - radians(?) ) +
                sin( radians(?) ) *
                sin( radians( latitude ) ) ) ) AS distance',
                [$this->latitude, $this->longitude, $this->latitude]
            )
            ->having('distance', '<', $distance)
            ->orderBy('distance')
            ->get();
    }

    // Method untuk menghitung rating rata-rata
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->where('status', 'approved')->avg('rating') ?: 0;
    }

    // Method untuk mendapatkan total ulasan yang disetujui
    public function getApprovedReviewsCountAttribute()
    {
        return $this->reviews()->where('status', 'approved')->count();
    }
}
