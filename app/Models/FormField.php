<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_type',
        'section',
        'name',
        'label',
        'type',
        'excel_cell',
        'tooltip',
        'validation',
        'order',
        'placeholder',
    ];
}
