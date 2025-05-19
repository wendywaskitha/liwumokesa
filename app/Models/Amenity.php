<?php

namespace App\Models;

use App\Models\Destination;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Amenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'district_id',
        'address',
        'latitude',
        'longitude',
        'featured_image',
        'availability',
        'opening_hours',
        'closing_hours',
        'operational_notes',
        'is_free',
        'fee',
        'is_accessible',
        'features',
        'description',
        'contact',
        'status',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'is_free' => 'boolean',
        'is_accessible' => 'boolean',
        'features' => 'array',
        'status' => 'boolean',
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    /**
     * The destinations that have this amenity.
     */
    public function destinations(): BelongsToMany
    {
        return $this->belongsToMany(Destination::class, 'destination_amenity', 'amenity_id', 'destination_id')
            ->withTimestamps();
    }
}
