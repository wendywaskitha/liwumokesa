<?php

// app/Models/Booking.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'travel_package_id',
        'booking_code',
        'booking_date',
        'quantity',
        'booking_status',
        'payment_status',
        'payment_proof',
        'total_price',
        'notes'
    ];

    protected $casts = [
        'booking_date' => 'datetime',
        'total_price' => 'float'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function travelPackage()
    {
        return $this->belongsTo(TravelPackage::class);
    }
}
