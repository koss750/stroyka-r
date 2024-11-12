<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssociatedCosts extends Model
{
    use HasFactory;
    
    // The table associated with the model.
    protected $table = 'associated_costs';

    // The attributes that are mass assignable.
    protected $fillable = ['template', 'description', 'location_cell', 'location_sheet', 'value', 'filename'];

    // Define the inverse relationship with ExcelFileType
    public function excelFileType()
    {
        return $this->belongsTo(ExcelFileType::class, 'filename', 'file');
    }
}
