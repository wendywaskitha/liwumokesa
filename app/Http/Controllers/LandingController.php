<?php

namespace App\Http\Controllers;

use PDF;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\Review;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Culinary;
use App\Models\District;
use App\Models\Destination;
use Illuminate\Http\Request;
use App\Models\Accommodation;
use App\Models\TravelPackage;
use App\Models\CreativeEconomy;
use App\Models\CulturalHeritage;
use App\Models\EventRegistration;
use App\Models\NewsletterSubscriber;

class LandingController extends Controller
{
    /**
     * Menampilkan halaman utama (landing page)
     */
    public function home()
    {
        // Mengambil data untuk featured section
        $featuredDestinations = Destination::with(['district', 'category', 'reviews'])
            ->where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        $featuredPackages = TravelPackage::with('destinations')
            ->where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $upcomingEvents = Event::where('start_date', '>=', now())
            ->orderBy('start_date')
            ->take(4)
            ->get();

        $creativeProducts = CreativeEconomy::with('category')
            ->where('status', true)
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->name,
                    'category' => $item->category->name,
                    'price_range' => 'Rp ' . number_format($item->price_range_start, 0, ',', '.') . ' - ' .
                                'Rp ' . number_format($item->price_range_end, 0, ',', '.'),
                    'featured_image' => $item->featured_image,
                    'rating' => $item->average_rating,
                    'slug' => $item->slug,
                    'is_verified' => $item->is_verified,
                ];
            });

        // Mengambil data review terbaru
        $latestReviews = Review::with(['user', 'reviewable'])
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Mengambil data statistik
        $stats = [
            'districts_count' => District::count(),
            'destinations_count' => Destination::count(),
            'cultural_heritages_count' => CulturalHeritage::count(),
            'accommodations_count' => Accommodation::count()
        ];

        return view('landing.home', compact(
            'featuredDestinations',
            'featuredPackages',
            'upcomingEvents',
            'latestReviews',
            'stats',
            'creativeProducts'
        ));
    }

    /**
     * Menampilkan halaman daftar destinasi
     */
    public function destinations(Request $request)
    {
        $query = Destination::query()->with(['district', 'category', 'reviews']);

        // Filter berdasarkan parameter pencarian
        if ($request->has('q')) {
            $query->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('description', 'like', '%' . $request->q . '%');
        }

        // Filter berdasarkan district
        if ($request->has('district_id') && $request->district_id) {
            $query->where('district_id', $request->district_id);
        }

        // Filter berdasarkan kategori
        if ($request->has('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Pengurutan
        $sort = $request->sort ?? 'newest';
        switch ($sort) {
            case 'rating':
                $query->leftJoin('reviews', function($join) {
                    $join->on('destinations.id', '=', 'reviews.reviewable_id')
                         ->where('reviews.reviewable_type', '=', Destination::class);
                })
                ->select('destinations.*', \DB::raw('AVG(reviews.rating) as avg_rating'))
                ->groupBy('destinations.id')
                ->orderByDesc('avg_rating');
                break;
            case 'popular':
                $query->withCount('visits')->orderByDesc('visits_count');
                break;
            case 'name_asc':
                $query->orderBy('name');
                break;
            case 'name_desc':
                $query->orderByDesc('name');
                break;
            default:
                $query->orderByDesc('created_at');
                break;
        }

        $destinations = $query->paginate(12)->appends($request->query());

        // Data untuk filter di sidebar
        $districts = District::orderBy('name')->get();
        $categories = Category::where('type', 'destination')->orderBy('name')->get();

        return view('landing.destinations', compact('destinations', 'districts', 'categories'));
    }

    /**
     * Menampilkan detail destinasi wisata
     */
    public function showDestination($slug)
    {
        $destination = Destination::with([
            'district',
            'category',
            'reviews' => function($query) {
                $query->where('status', 'approved')
                    ->orderBy('created_at', 'desc');
            },
            'reviews.user',
            'galleries',
            'amenities',
        ])->where('slug', $slug)->firstOrFail();

        // Validasi koordinat
        if ($destination->latitude && $destination->longitude) {
            // Get nearby destinations within 5km radius
            $nearbyDestinations = Destination::select([
                    'id', 'name', 'slug', 'featured_image',
                    'latitude', 'longitude', 'category_id'
                ])
                ->with(['category'])
                ->where('id', '!=', $destination->id)
                ->where('status', true)
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get();
        } else {
            $nearbyDestinations = collect();
        }

        return view('landing.destination-single', compact(
            'destination',
            'nearbyDestinations'
        ));
    }

    /**
     * Menampilkan daftar paket wisata
     */
    public function packages(Request $request)
    {
        $query = TravelPackage::query()->with(['destinations']);

        // Filter pencarian
        if ($request->has('q')) {
            $query->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('description', 'like', '%' . $request->q . '%');
        }

        // Filter durasi
        if ($request->has('duration') && $request->duration) {
            if ($request->duration === '4+') {
                $query->where('duration', '>=', 4);
            } else {
                $query->where('duration', $request->duration);
            }
        }

        // Filter tipe
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filter rentang harga
        if ($request->has('price_min') && $request->price_min) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->has('price_max') && $request->price_max) {
            $query->where('price', '<=', $request->price_max);
        }

        // Pengurutan
        $sort = $request->sort ?? 'newest';
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price');
                break;
            case 'price_desc':
                $query->orderByDesc('price');
                break;
            case 'duration_asc':
                $query->orderBy('duration');
                break;
            case 'duration_desc':
                $query->orderByDesc('duration');
                break;
            case 'popular':
                $query->withCount('bookings')->orderByDesc('bookings_count');
                break;
            default:
                $query->orderByDesc('created_at');
                break;
        }

        $packages = $query->paginate(9)->appends($request->query());

        // Data untuk filter
        $types = TravelPackage::distinct('type')->pluck('type')->filter();

        return view('landing.packages', compact('packages', 'types'));
    }

    /**
     * Menampilkan detail paket wisata
     */
    public function showPackage($slug)
    {
        // Hapus inclusions dan exclusions dari with() karena bukan relationship
        $package = TravelPackage::with([
            'destinations',
            'galleries',
            'reviews' => function($query) {
                $query->where('status', 'approved')
                    ->orderBy('created_at', 'desc');
            },
            'reviews.user',
            // Hapus 'inclusions', karena ini adalah JSON field
            // Hapus 'exclusions', karena ini adalah JSON field
        ])->where('slug', $slug)->firstOrFail();

        // Mencatat kunjungan jika model Visit ada
        if (class_exists('App\Models\Visit')) {
            \App\Models\Visit::recordPageVisit('travel-packages/' . $slug, auth()->id());
        }

        // Paket wisata serupa/terkait
        $relatedPackages = TravelPackage::where('id', '!=', $package->id)
            ->where(function($query) use ($package) {
                $query->where('type', $package->type)
                    ->orWhereBetween('duration', [$package->duration-1, $package->duration+1]);
            })
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('landing.package-single', compact('package', 'relatedPackages'));
    }

    /**
     * Menampilkan daftar acara/event
     */
    public function events(Request $request)
    {
        $query = Event::query();

        // Filter pencarian
        if ($request->has('q')) {
            $query->where('name', 'like', '%' . $request->q . '%')
                ->orWhere('description', 'like', '%' . $request->q . '%')
                ->orWhere('location', 'like', '%' . $request->q . '%');
        }

        // Filter berdasarkan tanggal
        if ($request->has('date') && $request->date) {
            $date = Carbon::parse($request->date);
            $query->where(function($q) use ($date) {
                $q->whereDate('start_date', '<=', $date)
                ->whereDate('end_date', '>=', $date);
            });
        } else {
            // Default menampilkan event yang belum berakhir
            $query->where('end_date', '>=', now());
        }

        // Pengurutan
        $sort = $request->sort ?? 'upcoming';
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name');
                break;
            case 'popular':
                $query->withCount('registrations')->orderByDesc('registrations_count');
                break;
            default:
                $query->orderBy('start_date'); // Default upcoming first
                break;
        }

        $events = $query->paginate(8)->appends($request->query());

        // Data untuk upcoming dates
        $upcomingDates = Event::where('start_date', '>=', now())
            ->orderBy('start_date')
            ->take(5)
            ->get(['start_date', 'end_date'])
            ->map(function($event) {
                return [
                    'date' => $event->start_date->format('Y-m-d'),
                    'label' => $event->start_date->format('d M Y')
                ];
            })->unique('date')->values();

        return view('landing.events', compact('events', 'upcomingDates'));
    }


    /**
     * Menampilkan detail acara/event
     */
    public function showEvent($slug)
    {
        $event = Event::with([
            'galleries',
            'district', // Ganti organizer dengan district
            'registrations'
        ])->where('slug', $slug)->firstOrFail();

        // Mencatat kunjungan jika model Visit ada
        if (class_exists('App\Models\Visit')) {
            \App\Models\Visit::recordPageVisit('events/' . $slug, auth()->id());
        }

        // Acara lain dengan tema serupa
        $relatedEvents = Event::where('id', '!=', $event->id)
            ->where(function($query) use ($event) {
                $query->where('district_id', $event->district_id) // Ganti type dengan district_id
                    ->orWhere('location', 'like', '%' . $event->location . '%');
            })
            ->where('end_date', '>=', now()) // Only upcoming events
            ->orderBy('start_date')
            ->take(3)
            ->get();

        // Cek apakah user sudah mendaftar event ini
        $userRegistered = false;
        if (auth()->check()) {
            $userRegistered = $event->registrations()
                ->where('user_id', auth()->id())
                ->exists();
        }

        return view('landing.event-single', compact('event', 'relatedEvents', 'userRegistered'));
    }

    /**
     * Menampilkan daftar akomodasi
     */
    public function accommodations(Request $request)
    {
        $query = Accommodation::query()->with(['district']);

        // Filter pencarian
        if ($request->has('q')) {
            $query->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('description', 'like', '%' . $request->q . '%')
                  ->orWhere('address', 'like', '%' . $request->q . '%');
        }

        // Filter berdasarkan tipe
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filter berdasarkan district
        if ($request->has('district_id') && $request->district_id) {
            $query->where('district_id', $request->district_id);
        }

        // Filter berdasarkan range harga
        if ($request->has('price_min') && $request->price_min) {
            $query->where('price_start', '>=', $request->price_min);
        }

        if ($request->has('price_max') && $request->price_max) {
            $query->where('price_start', '<=', $request->price_max);
        }

        // Pengurutan
        $sort = $request->sort ?? 'recommended';
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price_start');
                break;
            case 'price_desc':
                $query->orderByDesc('price_start');
                break;
            case 'rating':
                $query->leftJoin('reviews', function($join) {
                    $join->on('accommodations.id', '=', 'reviews.reviewable_id')
                         ->where('reviews.reviewable_type', '=', Accommodation::class);
                })
                ->select('accommodations.*', \DB::raw('AVG(reviews.rating) as avg_rating'))
                ->groupBy('accommodations.id')
                ->orderByDesc('avg_rating');
                break;
            default:
                $query->orderByDesc('is_featured')->orderByDesc('created_at');
                break;
        }

        $accommodations = $query->paginate(9)->appends($request->query());

        // Data untuk filter
        $districts = District::orderBy('name')->get();
        $types = Accommodation::distinct('type')->pluck('type')->filter();

        return view('landing.accommodations', compact('accommodations', 'districts', 'types'));
    }

    /**
     * Menampilkan detail akomodasi
     */
    public function showAccommodation($slug)
    {
        $accommodation = Accommodation::with([
            'district',
            'galleries',
            'amenities',
            'reviews' => function($query) {
                $query->where('status', 'approved')->orderBy('created_at', 'desc');
            },
            'reviews.user',
        ])->where('slug', $slug)->firstOrFail();

        // Mencatat kunjungan jika model Visit ada
        if (class_exists('App\Models\Visit')) {
            \App\Models\Visit::recordPageVisit('accommodations/' . $slug, auth()->id());
        }

        // Akomodasi lain dengan tipe serupa
        $similarAccommodations = Accommodation::where('id', '!=', $accommodation->id)
            ->where(function($query) use ($accommodation) {
                $query->where('type', $accommodation->type)
                      ->orWhere('district_id', $accommodation->district_id);
            })
            ->inRandomOrder()
            ->take(3)
            ->get();

        // Destinasi terdekat
        $nearbyDestinations = Destination::where('district_id', $accommodation->district_id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('landing.accommodation-single', compact(
            'accommodation',
            'similarAccommodations',
            'nearbyDestinations'
        ));
    }

    /**
     * Menampilkan daftar kuliner
     */
    public function culinaries(Request $request)
    {
        $query = Culinary::query()->with(['district']);

        // Filter pencarian
        if ($request->has('q')) {
            $query->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('description', 'like', '%' . $request->q . '%');
        }

        // Filter berdasarkan district
        if ($request->has('district_id') && $request->district_id) {
            $query->where('district_id', $request->district_id);
        }

        // Filter berdasarkan tipe
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Pengurutan
        $sort = $request->sort ?? 'recommended';
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price_range_start');
                break;
            case 'price_desc':
                $query->orderByDesc('price_range_start');
                break;
            case 'rating':
                $query->leftJoin('reviews', function($join) {
                    $join->on('culinaries.id', '=', 'reviews.reviewable_id')
                         ->where('reviews.reviewable_type', '=', Culinary::class);
                })
                ->select('culinaries.*', \DB::raw('AVG(reviews.rating) as avg_rating'))
                ->groupBy('culinaries.id')
                ->orderByDesc('avg_rating');
                break;
            default:
                $query->orderByDesc('is_featured')->orderByDesc('created_at');
                break;
        }

        $culinaries = $query->paginate(12)->appends($request->query());

        // Data untuk filter
        $districts = District::orderBy('name')->get();
        $types = Culinary::distinct('type')->pluck('type')->filter();

        return view('landing.culinaries', compact('culinaries', 'districts', 'types'));
    }

    /**
     * Menampilkan detail kuliner
     */
    public function showCulinary($slug)
    {
        $culinary = Culinary::with([
            'district',
            'galleries',
            'reviews' => function($query) {
                $query->where('status', 'approved')->orderBy('created_at', 'desc');
            },
            'reviews.user',
            'menu_items'
        ])->where('slug', $slug)->firstOrFail();

        // Mencatat kunjungan jika model Visit ada
        if (class_exists('App\Models\Visit')) {
            \App\Models\Visit::recordPageVisit('culinaries/' . $slug, auth()->id());
        }

        // Kuliner lain dengan tipe serupa
        $similarCulinaries = Culinary::where('id', '!=', $culinary->id)
            ->where(function($query) use ($culinary) {
                $query->where('type', $culinary->type)
                      ->orWhere('district_id', $culinary->district_id);
            })
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('landing.culinary-single', compact('culinary', 'similarCulinaries'));
    }

    /**
     * Menampilkan daftar warisan budaya
     */
    public function culturalHeritages(Request $request)
    {
        $query = CulturalHeritage::query()->with(['district']);

        // Filter pencarian
        if ($request->has('q')) {
            $query->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('description', 'like', '%' . $request->q . '%');
        }

        // Filter berdasarkan district
        if ($request->has('district_id') && $request->district_id) {
            $query->where('district_id', $request->district_id);
        }

        // Filter berdasarkan tipe
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Pengurutan
        $sort = $request->sort ?? 'newest';
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name');
                break;
            case 'name_desc':
                $query->orderByDesc('name');
                break;
            default:
                $query->orderByDesc('created_at');
                break;
        }

        $heritages = $query->paginate(12)->appends($request->query());

        // Data untuk filter
        $districts = District::orderBy('name')->get();
        $types = CulturalHeritage::distinct('type')->pluck('type')->filter();

        return view('landing.cultural-heritages', compact('heritages', 'districts', 'types'));
    }

    /**
     * Menampilkan detail warisan budaya
     */
    public function showCulturalHeritage($slug)
    {
        $heritage = CulturalHeritage::with([
            'district',
            'galleries',
            'related_events'
        ])->where('slug', $slug)->firstOrFail();

        // Mencatat kunjungan jika model Visit ada
        if (class_exists('App\Models\Visit')) {
            \App\Models\Visit::recordPageVisit('cultural-heritages/' . $slug, auth()->id());
        }

        // Warisan budaya terkait
        $relatedHeritages = CulturalHeritage::where('id', '!=', $heritage->id)
            ->where(function($query) use ($heritage) {
                $query->where('type', $heritage->type)
                      ->orWhere('district_id', $heritage->district_id);
            })
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('landing.cultural-heritage-single', compact('heritage', 'relatedHeritages'));
    }

    /**
     * Menampilkan halaman Tentang Kami
     */
    public function about()
    {
        $aboutContent = Setting::get('website.about_section');
        $stats = [
            'districts_count' => District::count(),
            'destinations_count' => Destination::count(),
            'cultural_heritages_count' => CulturalHeritage::count() ?? 15,
            'accommodations_count' => Accommodation::count() ?? 20
        ];

        return view('landing.about', compact('aboutContent', 'stats'));
    }

    /**
     * Menampilkan halaman Kontak
     */
    public function contact()
    {
        // Mengambil informasi kontak dari settings
        $contactEmail = Setting::get('contact.contact_email');
        $contactPhone = Setting::get('contact.contact_phone');
        $contactWhatsapp = Setting::get('contact.contact_whatsapp');
        $contactAddress = Setting::get('contact.contact_address');
        $officeHours = Setting::get('contact.office_hours');
        $googleMapsEmbed = Setting::get('contact.google_maps_embed');

        return view('landing.contact', compact(
            'contactEmail',
            'contactPhone',
            'contactWhatsapp',
            'contactAddress',
            'officeHours',
            'googleMapsEmbed'
        ));
    }

    /**
     * Menampilkan halaman FAQ
     */
    public function faq()
    {
        return view('landing.faq');
    }

    /**
     * Menampilkan halaman kebijakan privasi
     */
    public function privacyPolicy()
    {
        return view('landing.privacy-policy');
    }

    /**
     * Menampilkan halaman semua kecamatan
     */
    public function districts()
    {
        $districts = District::withCount(['destinations', 'accommodations'])
            ->orderBy('name')
            ->get();

        return view('landing.districts', compact('districts'));
    }

    /**
     * Menampilkan detail kecamatan
     */
    public function showDistrict($slug)
    {
        $district = District::with([
            'destinations' => function($query) {
                $query->take(6);
            },
            'accommodations' => function($query) {
                $query->take(4);
            },
            'culinaries' => function($query) {
                $query->take(4);
            }
        ])->where('slug', $slug)->firstOrFail();

        return view('landing.district-single', compact('district'));
    }

    /**
     * Menyimpan review dari user
     */
    public function storeReview(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'reviewable_type' => 'required|string',
            'reviewable_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|min:10|max:1000',
        ]);

        // Cek apakah user sudah login
        if (!auth()->check()) {
            return redirect()->back()->with('error', 'Anda harus login untuk memberikan ulasan.');
        }

        // Cek apakah user sudah pernah memberikan ulasan untuk item yang sama
        $existingReview = Review::where('reviewable_type', $validated['reviewable_type'])
            ->where('reviewable_id', $validated['reviewable_id'])
            ->where('user_id', auth()->id())
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'Anda sudah memberikan ulasan untuk item ini.');
        }

        // Buat review baru
        $review = new Review();
        $review->reviewable_type = $validated['reviewable_type'];
        $review->reviewable_id = $validated['reviewable_id'];
        $review->user_id = auth()->id();
        $review->rating = $validated['rating'];
        $review->content = $validated['content'];
        $review->status = Setting::get('review.auto_approve', false) ? 'approved' : 'pending';
        $review->save();

        return redirect()->back()->with('success', 'Terima kasih! Ulasan Anda telah tersimpan.');
    }

    /**
     * Mendaftar ke event
     */
    public function registerEvent(Request $request, $eventId)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);

        // Cek apakah event masih ada dan belum berakhir
        $event = Event::findOrFail($eventId);

        if (Carbon::parse($event->end_date)->isPast()) {
            return redirect()->back()->with('error', 'Event ini telah berakhir.');
        }

        // Cek apakah user sudah terdaftar
        if (auth()->check()) {
            $existingRegistration = $event->registrations()
                ->where('user_id', auth()->id())
                ->first();

            if ($existingRegistration) {
                return redirect()->back()->with('error', 'Anda sudah terdaftar pada event ini.');
            }
        }

        // Buat registrasi baru
        $registration = $event->registrations()->create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Pendaftaran berhasil! Kami akan menghubungi Anda untuk konfirmasi.');
    }

    /**
     * Pencarian Global
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        if (empty($query)) {
            return redirect()->back();
        }

        // Cari di destinations
        $destinations = Destination::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->take(5)
            ->get();

        // Cari di packages
        $packages = TravelPackage::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->take(3)
            ->get();

        // Cari di events
        $events = Event::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orWhere('location', 'like', "%{$query}%")
            ->take(3)
            ->get();

        // Cari di accommodations
        $accommodations = Accommodation::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->take(3)
            ->get();

        // Cari di culinaries
        $culinaries = Culinary::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->take(3)
            ->get();

        // Hitung total hasil
        $totalResults = $destinations->count() + $packages->count() + $events->count() +
                      $accommodations->count() + $culinaries->count();

        return view('landing.search-results', compact(
            'query',
            'destinations',
            'packages',
            'events',
            'accommodations',
            'culinaries',
            'totalResults'
        ));
    }

    /**
     * Generate sitemap
     */
    public function sitemap()
    {
        // Mengambil semua URL penting untuk sitemap
        $destinations = Destination::where('is_active', true)->get();
        $events = Event::where('end_date', '>=', now())->get();
        $packages = TravelPackage::where('is_active', true)->get();
        $districts = District::all();
        $heritages = CulturalHeritage::all();
        $culinaries = Culinary::where('is_active', true)->get();
        $accommodations = Accommodation::where('is_active', true)->get();

        // Generate sitemap menggunakan paket laravel-sitemap jika ada
        if (class_exists('Spatie\Sitemap\Sitemap')) {
            $sitemap = \Spatie\Sitemap\Sitemap::create();

            // Tambahkan halaman statis
            $sitemap->add(route('home'));
            $sitemap->add(route('about'));
            $sitemap->add(route('contact'));
            $sitemap->add(route('destinations.index'));
            $sitemap->add(route('packages.index'));
            $sitemap->add(route('events.index'));

            // Tambahkan halaman destinasi
            foreach ($destinations as $destination) {
                $sitemap->add(route('destinations.show', $destination->slug))
                    ->setChangeFrequency('weekly')
                    ->setPriority(0.8);
            }

            // Tambahkan halaman event
            foreach ($events as $event) {
                $sitemap->add(route('events.show', $event->slug))
                    ->setChangeFrequency('daily')
                    ->setPriority(0.9);
            }

            // Tambahkan halaman paket wisata
            foreach ($packages as $package) {
                $sitemap->add(route('packages.show', $package->slug))
                    ->setChangeFrequency('weekly')
                    ->setPriority(0.8);
            }

            $sitemap->writeToFile(public_path('sitemap.xml'));

            return response()->file(public_path('sitemap.xml'));
        }

        // Jika tidak ada paket laravel-sitemap, buat sitemap sederhana
        $content = view('landing.sitemap', compact(
            'destinations',
            'events',
            'packages',
            'districts',
            'heritages',
            'culinaries',
            'accommodations'
        ));

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Download tour guide PDF
     */
    public function downloadTourGuide($id)
    {
        $tourGuide = TravelPackage::findOrFail($id);

        // Jika package memiliki tour guide PDF, download
        if ($tourGuide->tour_guide_pdf) {
            return response()->download(storage_path('app/public/' . $tourGuide->tour_guide_pdf),
                'Tour Guide - ' . $tourGuide->name . '.pdf');
        }

        // Jika tidak, generate PDF menggunakan package seperti dompdf
        $pdf = PDF::loadView('pdf.tour-guide', compact('tourGuide'));

        return $pdf->download('Tour Guide - ' . $tourGuide->name . '.pdf');
    }

    /**
     * Subscribe to newsletter
     */
    public function subscribeNewsletter(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:255',
        ]);

        // Check if email already exists in subscribers
        $existingSubscriber = NewsletterSubscriber::where('email', $validated['email'])->first();

        if ($existingSubscriber) {
            return redirect()->back()->with('error', 'Email ini sudah berlangganan newsletter.');
        }

        // Create new subscriber
        NewsletterSubscriber::create([
            'email' => $validated['email'],
            'name' => $validated['name'] ?? null,
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Terima kasih telah berlangganan newsletter kami!');
    }

    /**
     * Mengirim pesan kontak
     */
    public function storeContactMessage(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // Simpan pesan kontak
        $contactMessage = new \App\Models\ContactMessage();
        $contactMessage->fill($validated);
        $contactMessage->status = 'unread';
        $contactMessage->save();

        // Kirim notifikasi ke admin jika konfigurasi email tersedia

        return redirect()->back()->with('success', 'Pesan Anda berhasil dikirim. Kami akan menghubungi Anda segera.');
    }
}
