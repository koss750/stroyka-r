<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'description',
        'address',
        'phone',
        'website',
        'operating_regions',
        'profile_picture',
        'services_offered',
        'products_offered',
    ];

    protected $casts = [
        'operating_regions' => 'array',
        'services_offered' => 'array',
        'products_offered' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}