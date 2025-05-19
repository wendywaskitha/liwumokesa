<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'destination_id',
        'visit_date',
        'duration',
        'ip_address',
        'user_agent',
        'referrer',
        'page_visited',
        'visit_type',
    ];

    protected $casts = [
        'visit_date' => 'datetime',
    ];

    /**
     * Get the user that made the visit (if authenticated).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the destination that was visited.
     */
    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    /**
     * Scope a query to only include visits within a date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Record a new visit to a destination.
     */
    public static function recordDestinationVisit($destinationId, $userId = null)
    {
        return self::create([
            'destination_id' => $destinationId,
            'user_id' => $userId ?? auth()->id(),
            'visit_date' => now(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'referrer' => request()->header('referer'),
            'visit_type' => 'destination',
        ]);
    }

    /**
     * Record a new page visit.
     */
    public static function recordPageVisit($page, $userId = null)
    {
        return self::create([
            'page_visited' => $page,
            'user_id' => $userId ?? auth()->id(),
            'visit_date' => now(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'referrer' => request()->header('referer'),
            'visit_type' => 'page',
        ]);
    }
}
