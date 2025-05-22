<?php

namespace App\Models;

use App\Models\Destination;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Culinary extends Model
{
    use HasFactory;

    // Nama tabel yang benar
    protected $table = 'culinaries';

    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
        'address',
        'latitude',
        'longitude',
        'district_id',
        'price_range_start',
        'price_range_end',
        'opening_hours',
        'contact_person',
        'phone_number',
        'featured_image',
        'status',
        // Tambahan kolom baru
        'social_media',
        'has_vegetarian_option',
        'halal_certified',
        'has_delivery',
        'featured_menu',
        'is_recommended',
        'category_id'
    ];

    protected $casts = [
        'status' => 'boolean',
        'has_vegetarian_option' => 'boolean',
        'halal_certified' => 'boolean',
        'has_delivery' => 'boolean',
        'is_recommended' => 'boolean',
        'featured_menu' => 'array',
        'latitude' => 'float',
        'longitude' => 'float',
        'price_range_start' => 'float',
        'price_range_end' => 'float',
    ];

    /**
     * Boot method untuk model
     * Otomatis generate slug jika slug kosong
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($culinary) {
            if (empty($culinary->slug)) {
                $culinary->slug = Str::slug($culinary->name);
            }
        });

        static::updating(function ($culinary) {
            if (empty($culinary->slug)) {
                $culinary->slug = Str::slug($culinary->name);
            }
        });
    }

    /**
     * Relasi ke District
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function destinations()
    {
        return $this->belongsToMany(Destination::class, 'destination_culinary')
            ->withPivot(['service_type', 'is_recommended', 'notes', 'sort_order'])
            ->orderBy('sort_order')
            ->withTimestamps();
    }

    /**
     * Relasi ke Gallery (polymorphic)
     */
    public function galleries()
    {
        return $this->morphMany(Gallery::class, 'imageable');
    }

    /**
     * Relasi ke Review (polymorphic)
     */
    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * Getter untuk averageRating
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->where('status', 'approved')->avg('rating') ?: 0;
    }

    /**
     * Getter untuk review count
     */
    public function getReviewsCountAttribute()
    {
        return $this->reviews()->where('status', 'approved')->count();
    }

    /**
     * Mendapatkan destinasi wisata terdekat
     */
    public function getNearbyDestinations($distance = 5) // dalam km
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

    /**
     * Scope untuk tempat kuliner yang direkomendasikan
     */
    public function scopeRecommended($query)
    {
        return $query->where('is_recommended', true);
    }

    /**
     * Scope untuk tempat kuliner dengan rating tertinggi
     */
    public function scopeTopRated($query)
    {
        return $query->withCount(['reviews as average_rating' => function ($query) {
            $query->select(\DB::raw('coalesce(avg(rating),0)'));
        }])
        ->orderByDesc('average_rating');
    }

    /**
     * Format price range menjadi teks
     */
    public function getPriceRangeTextAttribute()
    {
        return 'Rp ' . number_format($this->price_range_start, 0, ',', '.') . ' - ' .
               'Rp ' . number_format($this->price_range_end, 0, ',', '.');
    }
}
