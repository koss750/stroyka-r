<?php

namespace App\Http\Controllers;

use App\Models\SectionItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;


class SectionItemController extends Controller
{
    public function findByInvoice($invoice) {
        // Correctly using the where clause
        $invoice = "'$invoice'";
        $sql = "SELECT * FROM `invoice_items` WHERE (`invoice_id` = $invoice);";
        $results = DB::select($sql); 
        return $results;
    }
}

