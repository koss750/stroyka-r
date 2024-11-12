<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Design;
use App\Models\DesignMaterial;

class DesignPrice extends Model
{
    use HasFactory;
    
    public function design()
    {
        return $this->belongsTo(Design::class);
    }
    
    public function designMaterial()
    {
        return $this->belongsTo(DesignMaterial::class);
    }
}
