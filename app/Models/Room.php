<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    
    /**
     * The "slug" of the resource's default URI key.
     *
     * @var string
     */
    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    
}
