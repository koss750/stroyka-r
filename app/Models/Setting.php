<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'name',
        'description',
        'type',
        'options',
        'value',
        'enabled',
        'affected_users',
        'affected_areas',
    ];

    protected $casts = [
        'options' => 'json',
        'value' => 'json',
        'enabled' => 'boolean',
    ];

    public function getValueAttribute($value)
    {
        $decodedValue = json_decode($value, true);
        return $decodedValue === null ? $value : $decodedValue;
    }

    public function setValueAttribute($value)
    {
        $this->attributes['value'] = is_array($value) || is_object($value) ? json_encode($value) : $value;
    }

    public function getCustomerPricesAttribute($size)
    {
        return 200;
    }
}
