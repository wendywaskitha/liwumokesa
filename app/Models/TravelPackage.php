<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\TourGuide;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TravelPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'highlights',
        'duration',
        'duration_unit',
        'price',
        'discount_price',
        'inclusions',
        'exclusions',
        'itinerary',
        'terms_conditions',
        'meeting_point',
        'min_participants',
        'max_participants',
        'district_id',
        'featured_image',
        'difficulty',
        'type', // Tambahkan kolom type
        'start_date', // Tambahkan start_date untuk open trip
        'end_date', // Tambahkan end_date untuk open trip
        'is_private',
        'is_featured',
        'tour_guide_id',
        'status',
    ];

    protected $casts = [
        'duration' => 'integer',
        'duration_unit' => 'integer',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'inclusions' => 'array',
        'exclusions' => 'array',
        // 'itinerary' => 'array',
        'min_participants' => 'integer',
        'max_participants' => 'integer',
        'is_private' => 'boolean',
        'is_featured' => 'boolean',
        'status' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Boot method untuk model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($package) {
            if (empty($package->slug)) {
                $package->slug = Str::slug($package->name);
            }
        });

        static::updating(function ($package) {
            if (empty($package->slug)) {
                $package->slug = Str::slug($package->name);
            }
        });
    }

    /**
     * Relasi ke district
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Relasi ke destinations
     */
    public function destinations()
    {
        return $this->belongsToMany(Destination::class)
                    ->withPivot(['day', 'order', 'notes'])
                    ->withTimestamps();
    }

    /**
     * Relasi ke accommodations
     */
    public function accommodations()
    {
        return $this->belongsToMany(Accommodation::class)
                    ->withPivot(['day', 'notes'])
                    ->withTimestamps();
    }

    /**
     * Relasi ke transportations
     */
    public function transportations()
    {
        return $this->belongsToMany(Transportation::class, 'travel_package_transportation')
                    ->withPivot(['route_details', 'notes'])
                    ->withTimestamps();
    }

    /**
     * Relasi ke availabilities
     */
    public function availabilities()
    {
        return $this->hasMany(TravelPackageAvailability::class);
    }

    /**
     * Relasi ke bookings
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Relasi ke reviews (polymorphic)
     */
    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * Relasi ke gallery (polymorphic)
     */
    public function galleries()
    {
        return $this->morphMany(Gallery::class, 'imageable');
    }

    public function tourGuide()
    {
        return $this->belongsTo(TourGuide::class);
    }

    /**
     * Get duration text attribute
     */
    public function getDurationTextAttribute()
    {
        $unit = match($this->duration_unit) {
            1 => $this->duration > 1 ? ' hari' : ' hari',
            2 => $this->duration > 1 ? ' malam' : ' malam',
            3 => $this->duration > 1 ? ' jam' : ' jam',
            default => ' hari'
        };

        return $this->duration . $unit;
    }

    /**
     * Get difficulty text attribute
     */
    public function getDifficultyTextAttribute()
    {
        return match($this->difficulty) {
            'easy' => 'Mudah',
            'moderate' => 'Sedang',
            'challenging' => 'Menantang',
            default => $this->difficulty
        };
    }

    /**
     * Get price formatted attribute
     */
    public function getPriceFormattedAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Get discount price formatted attribute
     */
    public function getDiscountPriceFormattedAttribute()
    {
        if (!$this->discount_price) {
            return null;
        }

        return 'Rp ' . number_format($this->discount_price, 0, ',', '.');
    }

    /**
     * Get final price attribute
     */
    public function getFinalPriceAttribute()
    {
        return $this->discount_price ?: $this->price;
    }

    /**
     * Get discount percentage attribute
     */
    public function getDiscountPercentageAttribute()
    {
        if (!$this->discount_price || $this->discount_price >= $this->price) {
            return 0;
        }

        return round((($this->price - $this->discount_price) / $this->price) * 100);
    }

    /**
     * Get average rating
     */
    public function getAverageRatingAttribute()
    {
        if ($this->reviews->count() === 0) {
            return 0;
        }

        return number_format($this->reviews->avg('rating'), 1);
    }

    /**
     * Scope untuk featured travel packages
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope untuk active travel packages
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope untuk private travel packages
     */
    public function scopePrivate($query)
    {
        return $query->where('is_private', true);
    }

    /**
     * Scope untuk group travel packages
     */
    public function scopeGroup($query)
    {
        return $query->where('is_private', false);
    }

    /**
     * Check if package is available on a specific date
     */
    public function isAvailable($date)
    {
        if (!$this->status) {
            return false;
        }

        $availability = $this->availabilities()->whereDate('date', $date)->first();

        if (!$availability) {
            return false;
        }

        return $availability->is_available && $availability->quota > 0;
    }

    /**
     * Get upcoming available dates
     */
    public function getUpcomingDatesAttribute()
    {
        return $this->availabilities()
                    ->where('date', '>=', Carbon::now())
                    ->where('is_available', true)
                    ->where('quota', '>', 0)
                    ->orderBy('date')
                    ->limit(5)
                    ->get();
    }

    /**
     * Get tipe paket dalam format yang lebih manusiawi
     */
    public function getTypeNameAttribute()
    {
        return match($this->type) {
            'private' => 'Private Tour',
            'open' => 'Open Trip',
            'group' => 'Group Tour',
            'family' => 'Family Package',
            'custom' => 'Custom Tour',
            default => $this->type
        };
    }

    // Add accessor methods for inclusions and exclusions
    public function getInclusionsArrayAttribute()
    {
        if (empty($this->inclusions)) {
            return [];
        }
        return is_string($this->inclusions) ? json_decode($this->inclusions, true) : $this->inclusions;
    }

    public function getExclusionsArrayAttribute()
    {
        if (empty($this->exclusions)) {
            return [];
        }
        return is_string($this->exclusions) ? json_decode($this->exclusions, true) : $this->exclusions;
    }
}
