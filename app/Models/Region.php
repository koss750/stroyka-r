<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

     public function getAllRegions()
    {
        $regions = Region::select('name', 'code')->get();
        return response()->json($regions);
    }
}
