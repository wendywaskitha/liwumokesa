<?php

namespace App\Models;

use App\Models\Destination;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TourGuide extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'photo',
        'description',
        'languages',
        'experience_years',
        'rating',
        'is_available',
        'status'
    ];

    protected $casts = [
        'languages' => 'array',
        'rating' => 'decimal:1',
        'is_available' => 'boolean',
        'status' => 'boolean',
    ];

    public function travelPackages()
    {
        return $this->hasMany(TravelPackage::class);
    }

    /**
     * Relasi ke destinations
     */
    public function destinations()
    {
        return $this->belongsToMany(Destination::class, 'destination_tour_guide')
                    ->withPivot(['specialization', 'price', 'notes'])
                    ->withTimestamps();
    }

    /**
     * Get name with experience display
     */
    public function getNameWithExperienceAttribute()
    {
        return $this->name . ' (' . $this->experience_years . ' tahun)';
    }

    /**
     * Get languages as string
     */
    public function getLanguagesListAttribute()
    {
        if (!$this->languages) {
            return '';
        }

        return implode(', ', $this->languages);
    }

    /**
     * Scope untuk guide yang tersedia
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)->where('status', true);
    }
}
