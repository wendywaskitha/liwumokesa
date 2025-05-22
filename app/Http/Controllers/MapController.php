<?php

namespace App\Http\Controllers;

use App\Models\Culinary;
use App\Models\District;
use App\Models\Destination;
use Illuminate\Http\Request;
use App\Models\CreativeEconomy;
use App\Models\CulturalHeritage;

class MapController extends Controller
{
    public function index()
    {
        // Destinations with category
        $destinations = Destination::with('category')
            ->where('status', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->select([
                'id',
                'name',
                'slug',
                'category_id',
                'latitude',
                'longitude',
                'featured_image'
            ])
            ->get();

        // Cultural Heritage
        $culturalHeritages = CulturalHeritage::where('status', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->select([
                'id',
                'name',
                'slug',
                'type',
                'latitude',
                'longitude',
                'featured_image'
            ])
            ->get();

        // Creative Economy with category
        $creatives = CreativeEconomy::with('category')
            ->where('status', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->select([
                'id',
                'name',
                'slug',
                'category_id',
                'latitude',
                'longitude',
                'featured_image'
            ])
            ->get();

        $culinaries = Culinary::where('status', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->select([
                'id',
                'name',
                'slug',
                'type',
                'latitude',
                'longitude',
                'featured_image'
            ])
            ->get();

        $districts = District::orderBy('name')->get();

        return view('landing.map', compact(
            'destinations',
            'culturalHeritages',
            'creatives',
            'districts',
            'culinaries'
        ));
    }
}
