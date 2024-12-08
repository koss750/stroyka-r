<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;

class RegionController extends Controller
{
    public function searchRegions(Request $request)
    {
        $search = $request->input('q');
        $page = $request->input('page', 1);
        $perPage = 30;

        $regions = Region::where('name', 'like', "%{$search}%")
            ->orWhere('code', 'like', "%{$search}%")
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get()
            ->map(function ($region) {
                return [
                    'id' => $region->code,
                    'text' => $region->name,
                    'name' => $region->name,
                    'code' => $region->code
                ];
            });

        $total = Region::where('name', 'like', "%{$search}%")
            ->orWhere('code', 'like', "%{$search}%")
            ->count();

        return response()->json([
            'items' => $regions,
            'total_count' => $total
        ]);
    }

    // Add this new method
    public function getAllRegions()
    {
        $regions = Region::select('name', 'id', 'code')->get();
        foreach ($regions as $region) {
            $region->code = $region->id;
        }
        return response()->json($regions);
    }

    // Add this new method
    public function index()
    {
        $regions = Region::select('name', 'id', 'code')->get();
        foreach ($regions as $region) {
            $region->code = $region->id;
        }
        return response()->json($regions);
    }
}