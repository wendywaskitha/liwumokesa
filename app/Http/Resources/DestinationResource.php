<?php
// app/Http/Resources/DestinationResource.php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DestinationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'type' => $this->type,
            'location' => $this->location,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'visiting_hours' => $this->visiting_hours,
            'entrance_fee' => $this->entrance_fee,
            'facilities' => $this->facilities,
            'website' => $this->website,
            'contact' => $this->contact,
            'best_time_to_visit' => $this->best_time_to_visit,
            'tips' => $this->tips,
            'featured_image' => $this->featured_image,
            'is_featured' => $this->is_featured,
            'is_wished' => $this->when(isset($this->is_wished), $this->is_wished),
            'category' => $this->whenLoaded('category'),
            'district' => $this->whenLoaded('district'),
            'galleries' => $this->whenLoaded('galleries'),
            'reviews_count' => $this->whenLoaded('reviews', function () {
                return $this->reviews->count();
            }),
            'average_rating' => $this->whenLoaded('reviews', function () {
                return $this->reviews->avg('rating');
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
