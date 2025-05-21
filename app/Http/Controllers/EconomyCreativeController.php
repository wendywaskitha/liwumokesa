<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CreativeEconomy; // Ganti dari Product ke CreativeEconomy
use Illuminate\Http\Request;

class EconomyCreativeController extends Controller
{
    public function index(Request $request)
    {
        $query = CreativeEconomy::with(['category', 'reviews'])
            ->where('status', true) // Hanya tampilkan yang aktif
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

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('type', 'economy_creative')->get();

        return view('landing.economy-creative.index', compact('products', 'categories'));
    }

    public function show(CreativeEconomy $product)
    {
        $product->load(['category', 'galleries', 'reviews.user']);

        // Get similar products
        $similarProducts = CreativeEconomy::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', true)
            ->take(4)
            ->get();

        return view('landing.economy-creative.show', compact('product', 'similarProducts'));
    }
}
