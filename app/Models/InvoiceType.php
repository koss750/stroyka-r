<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceType extends Model
{
    use HasFactory;

    protected $table = 'invoice_structures';

    protected $fillable = [
        'ref',
        'title',
        'depth',
        'parent',
        'label',
        'params',
        'sheetnames',
        'sheet_spec',
        'custom_order_title'
    ];

    protected $casts = [
        'properties' => 'array',
        'sheet_spec' => 'array'
    ];

    // Optionally add a recursive relationship to itself
    public function children()
    {
        return $this->hasMany(InvoiceType::class, 'parent', 'ref');
    }

    // Define the relationship method
    public function parent()
    {
        return $this->belongsTo(InvoiceType::class, 'parent', 'ref');
    }

    // Add oldestParent method to return the relationship instance
    public function oldestParent()
    {
        return $this->parent();
    }

    // Add oldestParent method to return the relationship instance
    public function oldestParentType()
    {
        $parent = $this->parent()->first();
        while ($parent && $parent->parent()->exists()) {
            $parent = $parent->parent()->first();
        }
        return $parent;
    }

    // Add a computed attribute to get the oldest parent model instance
    public function getOldestParentLabelAttribute()
    {
        $parent = $this->parent()->first();
        while ($parent && $parent->parent()->exists()) {
            $parent = $parent->parent()->first();
        }
        return $parent->label;
    }
}
