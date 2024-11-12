<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Subscription;

class PricePlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'code',
        'type',
        'static_price',
        'dependent_entity',
        'dependent_parameter',
        'dependent_type',
        'parameter_option',
        'description',
        'price',
        'currency',
        'validity_days',
        'is_active',
        'total_uses',
        'limit_uses',
        'valid_from'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'static_price' => 'boolean',
        'parameter_option' => 'array',
        'valid_from' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeValidNow($query)
    {
        return $query->where('valid_from', '<=', now());
    }

    public function isAvailable()
    {
        return $this->is_active && $this->valid_from <= now() && 
               (!$this->limit_uses || $this->total_uses < $this->limit_uses);
    }

    public function incrementUses()
    {
        $this->increment('total_uses');
    }

    public function getRemainingUsesAttribute()
    {
        return $this->limit_uses ? $this->limit_uses - $this->total_uses : null;
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2) . ' ' . $this->currency;
    }

    public function getPriceForEntity($entity)
    {
        if ($this->static_price) {
            return $this->price;
        }

        $value = $entity->{$this->dependent_parameter};

        if ($this->dependent_type === 'range') {
            foreach ($this->parameter_option as $option) {
                if ($value >= $option['from'] && ($option['to'] === null || $value <= $option['to'])) {
                    return $option['price'];
                }
            }
        } elseif ($this->dependent_type === 'specific') {
            return $this->parameter_option[$value] ?? null;
        }

        return null;
    }
}
