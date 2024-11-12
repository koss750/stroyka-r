<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignSeo extends Model
{
    use HasFactory;

    protected $fillable = [
        'design_id',
        'title',
        'keywords',
        'description',
        'image',
        'alt_description',
        'alt_title',
        'alt_image',
        'additional_meta',
    ];

    protected $casts = [
        'additional_meta' => 'array',
    ];

    public function design()
    {
        return $this->belongsTo(Design::class);
    }
}