<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CreativeEconomy;
use Illuminate\Http\Request;

class CreativeEconomyController extends Controller
{
    // Menampilkan semua data CreativeEconomy
    public function index(Request $request)
    {
        $query = CreativeEconomy::query();

        // Pencarian berdasarkan nama
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        $creativeEconomies = $query->get();
        return response()->json($creativeEconomies);
    }

    // Menampilkan data CreativeEconomy berdasarkan ID
    public function show($id)
    {
        $creativeEconomy = CreativeEconomy::find($id);

        if (!$creativeEconomy) {
            return response()->json(['message' => 'Creative Economy not found'], 404);
        }

        return response()->json($creativeEconomy);
    }
}
