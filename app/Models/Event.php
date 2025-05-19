<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'start_date',
        'end_date',
        'location',
        'district_id',
        'organizer',
        'contact_person',
        'contact_phone',
        'is_free',
        'ticket_price',
        'capacity',
        'schedule_info',
        'facilities',
        'is_recurring',
        'recurring_type',
        'featured_image',
        'is_featured',
        'status',
        'category_id'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_free' => 'boolean',
        'is_recurring' => 'boolean',
        'is_featured' => 'boolean',
        'status' => 'boolean',
    ];

    /**
     * Boot method untuk model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->name);
            }
        });

        static::updating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->name);
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
     * Relasi ke Gallery (polymorphic)
     */
    public function galleries()
    {
        return $this->morphMany(Gallery::class, 'imageable');
    }

    /**
     * Relasi ke CulturalHeritage
     */
    public function culturalHeritages()
    {
        return $this->belongsToMany(CulturalHeritage::class);
    }

    /**
     * Relasi ke Registrations
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }


    /**
     * Relasi ke Reviews (polymorphic)
     */
    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * Scope untuk event yang akan datang
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now())->orderBy('start_date', 'asc');
    }

    /**
     * Scope untuk event yang sedang berlangsung
     */
    public function scopeOngoing($query)
    {
        return $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->orderBy('end_date', 'asc');
    }

    /**
     * Scope untuk event yang sudah selesai
     */
    public function scopePast($query)
    {
        return $query->where('end_date', '<', now())->orderBy('end_date', 'desc');
    }

    /**
     * Scope untuk event unggulan
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get the recurring type text attribute
     */
    public function getRecurringTypeTextAttribute()
    {
        return [
            'daily' => 'Harian',
            'weekly' => 'Mingguan',
            'monthly' => 'Bulanan',
            'yearly' => 'Tahunan',
        ][$this->recurring_type] ?? $this->recurring_type;
    }

    /**
     * Check if event is free
     */
    public function getIsFreeTextAttribute()
    {
        return $this->is_free ? 'Gratis' : 'Berbayar';
    }
}
