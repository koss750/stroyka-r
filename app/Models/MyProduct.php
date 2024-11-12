<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vanilo\Product\Models\Product as VaniloProduct;

class MyProduct extends VaniloProduct
{
    // Other model properties and methods

    public function design()
    {
        return $this->belongsTo(Design::class);
    }
}