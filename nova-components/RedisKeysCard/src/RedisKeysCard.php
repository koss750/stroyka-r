<?php

namespace App\Nova\Cards;

use Laravel\Nova\Card;
use Illuminate\Support\Facades\Redis;

class RedisKeysCard extends Card
{
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = '1/3';

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'redis-keys-card';
    }

    public function getKeys($designId)
    {
        $keys = Redis::connection('external')->keys("*{$designId}*");
        return $keys;
    }
}
