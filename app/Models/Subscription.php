<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'price_plan_id', 'entity_type', 'entity_id', 'starts_at', 'ends_at',
        'amount_paid', 'payment_reference', 'parameters', 'last_payment_at',
        'next_payment_due', 'is_cancelled', 'is_paused', 'cancellation_reason'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'last_payment_at' => 'datetime',
        'next_payment_due' => 'datetime',
        'amount_paid' => 'decimal:2',
        'parameters' => 'array',
        'is_cancelled' => 'boolean',
        'is_paused' => 'boolean',
    ];

    public function pricePlan()
    {
        return $this->belongsTo(PricePlan::class);
    }

    public function entity()
    {
        return $this->morphTo();
    }

    public function scopeActive($query)
    {
        return $query->where('is_cancelled', false)
                     ->where('is_paused', false)
                     ->where(function ($q) {
                         $q->whereNull('ends_at')
                           ->orWhere('ends_at', '>', now());
                     });
    }

    public function scopeExpired($query)
    {
        return $query->where('ends_at', '<=', now());
    }

    public function isActive()
    {
        return !$this->is_cancelled && !$this->is_paused && 
               ($this->ends_at === null || $this->ends_at > now());
    }

    public function cancel($reason = null)
    {
        $this->update([
            'is_cancelled' => true,
            'cancellation_reason' => $reason,
        ]);
    }

    public function pause()
    {
        $this->update(['is_paused' => true]);
    }

    public function resume()
    {
        $this->update(['is_paused' => false]);
    }

    public function renew($days)
    {
        $this->ends_at = $this->ends_at ? $this->ends_at->addDays($days) : now()->addDays($days);
        $this->save();
    }

    public function getDaysLeftAttribute()
    {
        return $this->ends_at ? now()->diffInDays($this->ends_at, false) : null;
    }

    public function getStatusAttribute()
    {
        if ($this->is_cancelled) return 'Cancelled';
        if ($this->is_paused) return 'Paused';
        if ($this->ends_at && $this->ends_at <= now()) return 'Expired';
        return 'Active';
    }
}