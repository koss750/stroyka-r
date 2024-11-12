<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foundation extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'template_path', 'parameters', 'exceptions'];

    protected $casts = [
        'parameters' => 'array',
        'exceptions' => 'array',
    ];
}