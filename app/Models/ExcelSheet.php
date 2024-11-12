<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcelSheet extends Model
{
    protected $table = 'excel_sheet_costs';

    protected $fillable = [
        'reference',
        'sheet_title',
        'sheet_code',
        'item_title',
        'current_value',
        'item_value_cell',
    ];
}