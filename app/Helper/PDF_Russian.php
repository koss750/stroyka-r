<?php

namespace App\Helper;

require_once base_path('vendor/setasign/fpdf/fpdf.php');

class PDF_Russian extends \FPDF
{
    function __construct($orientation='P', $unit='mm', $size='A4')
    {
        parent::__construct($orientation, $unit, $size);
        // No need to add fonts, we'll use the built-in Helvetica
    }

    function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $txt = iconv('UTF-8', 'windows-1251//TRANSLIT', $txt);
        parent::Cell($w, $h, $txt, $border, $ln, $align, $fill, $link);
    }

    function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false)
    {
        $txt = iconv('UTF-8', 'windows-1251//TRANSLIT', $txt);
        parent::MultiCell($w, $h, $txt, $border, $align, $fill);
    }
}