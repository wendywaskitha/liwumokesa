<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CreativeEconomy;
use Illuminate\Http\Request;

class EconomyCreativeController extends Controller
{
    public function index(Request $request)
    {
        $query = CreativeEconomy::with(['category', 'reviews', 'products'])
            ->where('status', true)
            ->when($request->category, function($q, $category) {
                return $q->whereHas('category', function($q) use ($category) {
                    $q->where('slug', $category);
                });
            })
            ->when($request->search, function($q, $search) {
                return $q->where('name', 'like', "%{$search}%");
            });

        // Sort options
        if ($request->sort) {
            switch ($request->sort) {
                case 'price_low':
                    $query->orderBy('price_range_start');
                    break;
                case 'price_high':
                    $query->orderByDesc('price_range_end');
                    break;
                case 'rating':
                    $query->withAvg('reviews', 'rating')
                          ->orderByDesc('reviews_avg_rating');
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $creativeEconomies = $query->paginate(12)->withQueryString();
        $categories = Category::where('type', 'economy_creative')->get();

        return view('landing.economy-creative.index', compact('creativeEconomies', 'categories'));
    }

    public function show(CreativeEconomy $creativeEconomy) // Ubah parameter name
    {
        $creativeEconomy->load([
            'category',
            'galleries',
            'reviews.user',
            'products' => function($query) {
                $query->where('status', true)
                    ->orderBy('is_featured', 'desc')
                    ->orderBy('created_at', 'desc');
            }
        ]);

        // Get similar creative economies
        $similarCreativeEconomies = CreativeEconomy::where('category_id', $creativeEconomy->category_id)
            ->where('id', '!=', $creativeEconomy->id)
            ->where('status', true)
            ->take(4)
            ->get();

        return view('landing.economy-creative.show', compact(
            'creativeEconomy',
            'similarCreativeEconomies'
        ));
    }
}
