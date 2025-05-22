<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\District;
use Illuminate\Http\Request;
use App\Models\CulturalHeritage;
use App\Http\Controllers\Controller;

class CulturalHeritageController extends Controller
{
    public function index(Request $request)
    {
        $query = CulturalHeritage::query()
            ->with(['district', 'galleries', 'reviews'])
            ->where('status', true);

        // Filter berdasarkan kategori
        if ($request->has('category')) {
            $query->where('type', $request->category);
        }

        // Filter berdasarkan kecamatan
        if ($request->has('district')) {
            $query->where('district_id', $request->district);
        }

        // Search
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $culturalHeritages = $query->paginate(9);
        $districts = District::all();

        return view('landing.cultural-heritage.index', compact('culturalHeritages', 'districts'));
    }

    public function show($slug)
    {
        $heritage = CulturalHeritage::with([
            'district',
            'galleries',
            'reviews' => function ($query) {
                $query->where('status', 'approved')
                    ->with('user')
                    ->latest();
            },
            'amenities' => function ($query) {
                $query->where('status', true);
            },
            'accommodations' => function ($query) {
                $query->where('status', true)
                    ->withPivot(['partnership_type', 'is_recommended', 'notes']);
            },
            'transportations' => function ($query) {
                $query->where('status', true)
                    ->withPivot(['service_type', 'route_notes', 'notes']);
            },
            'culinaries' => function ($query) {
                $query->where('status', true)
                    ->withPivot(['service_type', 'is_recommended', 'notes']);
            },
            'events'
        ])->where('slug', $slug)->firstOrFail();

        // Get related events
        $relatedEvents = Event::whereHas('culturalHeritages', function($query) use ($heritage) {
                $query->where('cultural_heritages.id', $heritage->id);
            })
            ->where('status', '!=', 'finished')
            ->orderBy('start_date')
            ->take(5)
            ->get();

        // Get nearby amenities
        $nearbyAmenities = $heritage->amenities()
            ->where('status', true)
            ->orderBy('sort_order')
            ->get();

        // Get recommended accommodations
        $recommendedAccommodations = $heritage->accommodations()
            ->where('status', true)
            ->wherePivot('is_recommended', true)
            ->get();

        // Get available transportations
        $availableTransportations = $heritage->transportations()
            ->where('status', true)
            ->orderBy('type')
            ->get();

        // Get recommended culinaries
        $recommendedCulinaries = $heritage->culinaries()
            ->where('status', true)
            ->wherePivot('is_recommended', true)
            ->get();

        return view('landing.cultural-heritage.show', compact(
            'heritage',
            'nearbyAmenities',
            'recommendedAccommodations',
            'availableTransportations',
            'recommendedCulinaries',
            'relatedEvents'
        ));
    }
}
