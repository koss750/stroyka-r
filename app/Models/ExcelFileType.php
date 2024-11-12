<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AssociatedCosts;

class ExcelFileType extends Model
{
    use HasFactory;
    
    // The table associated with the model.
    protected $table = 'excel_file_types';

    // The attributes that are mass assignable.
    protected $fillable = ['code', 'type', 'subtype', 'file', 'associatedCosts'];
    
    protected $casts = [
	    'associatedCosts' => 'json',
	    ];
}
