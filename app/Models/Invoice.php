<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'foundation',
        'complect',
        'roof',
        'params',
        'pdf_link',
    ];

    public function gatherFilledSection(Section $section)
    {
        // Logic to gather sections based on invoice ID
        // Could be used to display sections for a specific invoice.
    }
}
