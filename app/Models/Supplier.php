<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'company_name',
        'inn',
        'address',
        'email',
        'phone_1',
        'phone_2',
        'message',
        'type',
        'status',
        'type_of_organisation',
        'profile_picture_url',
        'yandex_maps_link',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function regions()
    {
        return $this->belongsToMany(Region::class, 'supplier_regions');
    }
}