<?php

namespace App\Models;

use App\Models\Destination;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CreativeEconomy extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'description',
        'short_description',
        'address',
        'district_id',
        'latitude',
        'longitude',
        'phone_number',
        'email',
        'website',
        'social_media',
        'business_hours',
        'owner_name',
        'establishment_year',
        'employees_count',
        'products_description',
        'price_range_start',
        'price_range_end',
        'has_workshop',
        'workshop_information',
        'has_direct_selling',
        'featured_image',
        'status',
        // Kolom tambahan
        'is_featured',
        'is_verified',
        'accepts_credit_card',
        'provides_training',
        'shipping_available',
        'category_id'
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'price_range_start' => 'float',
        'price_range_end' => 'float',
        'has_workshop' => 'boolean',
        'has_direct_selling' => 'boolean',
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'is_verified' => 'boolean',
        'accepts_credit_card' => 'boolean',
        'provides_training' => 'boolean',
        'shipping_available' => 'boolean',
        'establishment_year' => 'integer',
        'employees_count' => 'integer',
    ];

    /**
     * Boot method untuk model
     * Otomatis generate slug jika slug kosong
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($creativeEconomy) {
            if (empty($creativeEconomy->slug)) {
                $creativeEconomy->slug = Str::slug($creativeEconomy->name);
            }
        });

        static::updating(function ($creativeEconomy) {
            if (empty($creativeEconomy->slug)) {
                $creativeEconomy->slug = Str::slug($creativeEconomy->name);
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

    /**
     * Relasi ke Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function destinations()
    {
        return $this->belongsToMany(Destination::class, 'destination_creative_economy')
            ->withPivot(['partnership_type', 'is_featured', 'notes', 'workshop_schedule', 'sort_order'])
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
     * Relasi ke Product (one-to-many)
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Mendapatkan rating rata-rata
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->where('status', 'approved')->avg('rating') ?: 0;
    }

    /**
     * Mendapatkan produk unggulan
     */
    public function getFeaturedProductsAttribute()
    {
        return $this->products()->where('is_featured', true)->take(5)->get();
    }

    /**
     * Mendapatkan teks range harga
     */
    public function getPriceRangeTextAttribute()
    {
        return 'Rp ' . number_format($this->price_range_start, 0, ',', '.') . ' - ' .
               'Rp ' . number_format($this->price_range_end, 0, ',', '.');
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
     * Scope untuk ekonomi kreatif yang direkomendasikan
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope untuk ekonomi kreatif dengan workshop
     */
    public function scopeHasWorkshop($query)
    {
        return $query->where('has_workshop', true);
    }

    /**
     * Scope untuk ekonomi kreatif dengan penjualan langsung
     */
    public function scopeHasDirectSelling($query)
    {
        return $query->where('has_direct_selling', true);
    }
}
