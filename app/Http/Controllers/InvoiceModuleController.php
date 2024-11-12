<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Section;
use App\Models\SectionItem;
use App\Http\Controllers\SectionItemController as SIM;
use App\Models\Design;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\RuTranslationController as Translator;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InvoiceModuleController extends Controller
{
    /**
     * Download the invoice_items table as a CSV file.
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public static function downloadInvoiceItemsCsv(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="invoice_items.csv"',
        ];

        return response()->stream(function () {
            // Open output stream
            $file = fopen('php://output', 'w');

            // Set UTF-8 BOM for Excel compatibility
            fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

            // Fetch data
            $items = DB::table('invoice_items')->get();

            // Set column headers
            if (!$items->isEmpty()) {
                fputcsv($file, array_keys((array)$items->first()));
            }

            // Output data
            foreach ($items as $item) {
                fputcsv($file, (array)$item);
            }

            // Close output stream
            fclose($file);
        }, 200, $headers);
    }

    public function setSections ($dReference, $rReference, $fReference=null) {
        //tempfixformixingPrefix
        if ($dReference[0] != "d") $dReference = "d" . $dReference;
        if ($rReference[0] != "r") $rReference = "r" . $rReference;
        //if ($fReference && $fReference[0] != "f") $fReference = "f" . $fReference;
        
        $sections = [];
        $sections[] = Section::where('parent_invoice_structure', $rReference)->get();
        $sections[] = Section::where('parent_invoice_structure', $dReference)->get();
        return $sections;
    }

    public function convertCollectionOfSelections($collection) {
        $sim = new SIM;
        $converted = [];
        foreach ($collection as $sections) {
            $sectionArray = $sections->toArray();
            //dd($sectionArray);
            $sectionParent = $sectionArray['parent_invoice_structure'];
            $sectionLabel = Translator::translate($sectionParent);
            $sectionObject = Section::where('id', $sectionArray['id'])->first();
            $params = $sim->findByInvoice($sectionParent);
            //dd($params);
            foreach ($params as $object) {
                if ($object->section == $sectionObject->section) {
                    $details['id'] = $object->reference;
                    $details['title'] = $object->title;
                    $details['unit'] = $object->unit;
                    $details['cost'] = $object->default_c;
                    $details['quantity'] = $object->default_q;
                    $details['total'] = round($object->default_c*$object->default_q,2);
                    $converted[$sectionLabel][$sectionObject->visibility][$object->side][] = $details;
                }
            }
            
           }
        return ($converted);
        }
    
 

    public function prepareForDemo($sections, $design) {
        
        $designTitle = $design->title;
        $returnArray = [];
        foreach ($sections as $section) {
            $returnArray[] = $this->convertCollectionOfSelections($section);
        }
        return $returnArray;
    }
        
}
        


