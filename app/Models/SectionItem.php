<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionItem extends Model
{
    use HasFactory;

    // Explicitly define the table to be used by the model
    protected $table = 'invoice_items';

    // Fillable attributes for mass assignment
    protected $fillable = [
        'reference',
        'invoice_id',
        'section',
        'side',
        'title',
        'unit',
        'quantity',
        'cost',
        'default_q',
        'default_c',
        'params'
    ];

    
}
