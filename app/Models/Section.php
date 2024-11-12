<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SectionItem;

class Section extends Model
{
    use HasFactory;

    // Explicitly define the table to be used by the model
    protected $table = 'invoice_sections';

    protected $fillable = [
        'parent_invoice_structure',
        'section',
        'visibility',
        'params',
    ];
    

    public $complete = [];

    public function fillSection()
    {
        // Logic to fill a single section based on section ID
        // This could be used in an edit form for a section.
    }

    public function getFilledSections($invoiceId)
    {
        // Logic to get variables for an invoice
        // This method might be used to return all related data for an invoice.
    }
}
