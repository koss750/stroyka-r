<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Actions\Actionable;

class PortalLog extends Model
{
    use Actionable;

    protected $fillable = [
        'loggable_type',
        'loggable_id',
        'action',
        'action_type',
        'details',
        'user_id'
    ];

    protected $casts = [
        'details' => 'array'
    ];

    public function loggable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
}