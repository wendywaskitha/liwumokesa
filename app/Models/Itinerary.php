<?php

// app/Models/Itinerary.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'start_date', 'end_date',
        'notes', 'is_public', 'estimated_budget'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_public' => 'boolean',
        'estimated_budget' => 'float'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function itineraryItems()
    {
        return $this->hasMany(ItineraryItem::class)->orderBy('day')->orderBy('order');
    }
}
