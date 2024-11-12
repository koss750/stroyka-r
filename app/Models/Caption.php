<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caption extends Model
{
    protected $fillable = ['key', 'value', 'locale'];
}