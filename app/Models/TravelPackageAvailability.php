<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelPackageAvailability extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'travel_package_id',
        'date',
        'quota',
        'is_available',
        'special_price',
    ];
    
    protected $casts = [
        'date' => 'date',
        'quota' => 'integer',
        'is_available' => 'boolean',
        'special_price' => 'decimal:2',
    ];
    
    public function travelPackage()
    {
        return $this->belongsTo(TravelPackage::class);
    }
    
    /**
     * Get the price for this date (special price or regular package price)
     */
    public function getDatePriceAttribute()
    {
        if ($this->special_price) {
            return $this->special_price;
        }
        
        return $this->travelPackage->discount_price ?? $this->travelPackage->price;
    }
    
    /**
     * Get formatted date price
     */
    public function getDatePriceFormattedAttribute()
    {
        return 'Rp ' . number_format($this->date_price, 0, ',', '.');
    }
    
    /**
     * Scope untuk upcoming dates
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now())->orderBy('date');
    }
    
    /**
     * Scope untuk available dates
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)->where('quota', '>', 0);
    }
}
