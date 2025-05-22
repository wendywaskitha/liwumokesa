<?php

// app/Models/Transportation.php

namespace App\Models;

use App\Models\Destination;
use App\Models\CulturalHeritage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transportation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'type', 'subtype', 'description', 'capacity',
        'price_scheme', 'base_price', 'contact_person',
        'phone_number', 'email', 'district_id', 'routes',
        'featured_image', 'status'
    ];

    protected $casts = [
        'routes' => 'array',
        'status' => 'boolean',
        'capacity' => 'integer',
        'base_price' => 'float'
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function destinations()
    {
        return $this->belongsToMany(Destination::class)
            ->withPivot(['service_type', 'notes'])
            ->withTimestamps();
    }

    public function culturalHeritages()
    {
        return $this->belongsToMany(CulturalHeritage::class, 'cultural_heritage_transportation')
            ->withPivot(['service_type', 'route_notes', 'notes'])
            ->withTimestamps();
    }

    public function galleries()
    {
        return $this->morphMany(Gallery::class, 'imageable');
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    // Method untuk mendapatkan rute berdasarkan asal dan tujuan
    public function getRouteByOriginDestination($origin, $destination)
    {
        if (!$this->routes) return null;

        foreach ($this->routes as $route) {
            if ($route['origin'] === $origin && $route['destination'] === $destination) {
                return $route;
            }
        }

        return null;
    }

    // Method untuk mendapatkan jadwal keberangkatan hari ini
    public function getTodaySchedules()
    {
        if (!$this->routes) return [];

        $todaySchedules = [];
        $today = strtolower(now()->format('l'));

        foreach ($this->routes as $route) {
            if (isset($route['schedules'])) {
                foreach ($route['schedules'] as $schedule) {
                    if ($schedule['day'] === $today || $schedule['day'] === 'daily') {
                        $todaySchedules[] = [
                            'route' => $route['origin'] . ' - ' . $route['destination'],
                            'time' => $schedule['departure_time'],
                        ];
                    }
                }
            }
        }

        return $todaySchedules;
    }

    // Method untuk menghitung rating rata-rata
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->where('status', 'approved')->avg('rating') ?: 0;
    }

    // Method untuk mendapatkan total ulasan yang disetujui
    public function getApprovedReviewsCountAttribute()
    {
        return $this->reviews()->where('status', 'approved')->count();
    }

    // Method untuk mendapatkan tipe transportasi dalam format yang lebih manusiawi
    public function getTypeNameAttribute()
    {
        return match($this->type) {
            'darat' => 'Transportasi Darat',
            'laut' => 'Transportasi Laut',
            'udara' => 'Transportasi Udara',
            default => 'Transportasi',
        };
    }

    // Method untuk mendapatkan subtype dalam format yang lebih manusiawi
    public function getSubtypeNameAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->subtype));
    }
}
