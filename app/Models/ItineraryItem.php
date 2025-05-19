<?php

// app/Models/ItineraryItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItineraryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'itinerary_id', 'itemable_id', 'itemable_type',
        'day', 'order', 'start_time', 'end_time',
        'notes', 'estimated_cost'
    ];

    protected $casts = [
        'day' => 'integer',
        'order' => 'integer',
        'estimated_cost' => 'float'
    ];

    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }

    public function itemable()
    {
        return $this->morphTo();
    }
}
