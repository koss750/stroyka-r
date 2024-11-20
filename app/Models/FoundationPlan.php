<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoundationPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'scale',
        'width',
        'drawing_data',
        'total_area',
        'perimeter',
        'angles',
        't_junctions',
        'x_crossings'
    ];

    protected $casts = [
        'drawing_data' => 'array',
        'total_area' => 'decimal:2',
        'perimeter' => 'decimal:2',
        'angles' => 'integer',
        't_junctions' => 'integer',
        'x_crossings' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}