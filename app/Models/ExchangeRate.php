<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'btc_rate_supplier',
        'btc_buy_rate',
        'btc_sell_rate',
        'rub_rate_supplier',
        'rub_buy_rate',
        'rub_sell_rate',
        'gbp_to_rub_rate',
        'rub_to_gbp_rate',
        'spread'
    ];
}
