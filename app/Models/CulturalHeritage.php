<?php

namespace App\Models;

use App\Models\Amenity;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CulturalHeritage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
        'historical_significance',
        'location',
        'district_id',
        'latitude',
        'longitude',
        'conservation_status',
        'recognition_status',
        'recognition_date',
        'practices_description',
        'physical_description',
        'custodian',
        'visitor_info',
        'is_endangered',
        'allows_visits',
        'is_featured',
        'featured_image',
        'status'
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'is_endangered' => 'boolean',
        'allows_visits' => 'boolean',
        'is_featured' => 'boolean',
        'status' => 'boolean',
        'recognition_date' => 'date',
    ];

    /**
     * Boot method untuk model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($heritageItem) {
            if (empty($heritageItem->slug)) {
                $heritageItem->slug = Str::slug($heritageItem->name);
            }
        });

        static::updating(function ($heritageItem) {
            if (empty($heritageItem->slug)) {
                $heritageItem->slug = Str::slug($heritageItem->name);
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

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'amenity_cultural_heritage')
            ->withPivot([
                'opening_hours',
                'closing_hours',
                'is_free',
                'fee',
                'is_accessible',
                'operational_notes',
                'sort_order'
            ])
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
     * Relasi ke Events
     */
    public function events()
    {
        return $this->belongsToMany(Event::class, 'cultural_heritage_event', 'cultural_heritage_id', 'event_id');
    }

    /**
     * Relasi ke Reviews (polymorphic)
     */
    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * Scope untuk tangible heritage
     */
    public function scopeTangible($query)
    {
        return $query->where('type', 'tangible');
    }

    /**
     * Scope untuk intangible heritage
     */
    public function scopeIntangible($query)
    {
        return $query->where('type', 'intangible');
    }

    /**
     * Scope untuk heritage yang diizinkan untuk dikunjungi
     */
    public function scopeAllowsVisits($query)
    {
        return $query->where('allows_visits', true);
    }

    /**
     * Scope untuk heritage yang direkomendasikan
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope untuk heritage yang terancam punah
     */
    public function scopeEndangered($query)
    {
        return $query->where('is_endangered', true);
    }

    /**
     * Mendapatkan acara yang akan datang terkait dengan heritage ini
     */
    public function getUpcomingEventsAttribute()
    {
        return $this->events()
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->get();
    }

    /**
     * Mendapatkan text type untuk tampilan
     */
    public function getTypeTextAttribute()
    {
        return [
            'tangible' => 'Warisan Budaya Berwujud',
            'intangible' => 'Warisan Budaya Tak Berwujud',
        ][$this->type] ?? $this->type;
    }

    /**
     * Mendapatkan text conservation status untuk tampilan
     */
    public function getConservationStatusTextAttribute()
    {
        return [
            'excellent' => 'Sangat Baik',
            'good' => 'Baik',
            'fair' => 'Cukup',
            'poor' => 'Buruk',
            'critical' => 'Kritis',
            'unknown' => 'Tidak Diketahui',
        ][$this->conservation_status] ?? $this->conservation_status;
    }

    /**
     * Mendapatkan text recognition status untuk tampilan
     */
    public function getRecognitionStatusTextAttribute()
    {
        return [
            'local' => 'Lokal',
            'regional' => 'Regional',
            'national' => 'Nasional',
            'international' => 'Internasional',
            'unesco' => 'UNESCO',
        ][$this->recognition_status] ?? $this->recognition_status;
    }
}
