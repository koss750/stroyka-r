<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Project;
use App\Models\Design;
use App\Models\InvoiceType;
use App\Models\ProjectPrice;
use App\Models\Setting;
use Illuminate\Support\Str;
use App\Helper\PDF_Russian;


require_once base_path('vendor/setasign/fpdf/fpdf.php');

class GenerateOrderPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $project;
    protected $sheetStructures;
    protected $totals;
    protected $designTitle;
    protected $displayLabour;

    public function __construct($projectId)
    {
        $this->project = Project::findOrFail($projectId);
        $this->prepareData();
    }

    protected function prepareData()
    {
        $this->designTitle = Design::where('id', $this->project->design_id)->firstOrFail()->title;
        $selectedConfiguration = $this->project->selected_configuration;
        $priceSetting = Setting::where('key', 'display_prices')->first()->value;
        $this->displayLabour = ($priceSetting != 'material');

        $invoiceTypeIds = [
            InvoiceType::where('ref', $selectedConfiguration['foundation'])->first()->id,
            InvoiceType::where('ref', $selectedConfiguration['dd'])->first()->id,
            InvoiceType::where('ref', $selectedConfiguration['roof'])->first()->id,
        ];

        $this->sheetStructures = [];
        $totalLabour = 0;
        $totalMaterial = 0;
        $titleOrder = 0;

        foreach ($invoiceTypeIds as $invoiceTypeId) {
            $titleOrder++;
            $projectPrice = ProjectPrice::where('invoice_type_id', $invoiceTypeId)
                                        ->where('design_id', $this->project->design_id)
                                        ->firstOrFail();

            if ($projectPrice) {
                $invoiceType = InvoiceType::where('id', $invoiceTypeId)->firstOrFail();
                $data = json_decode($projectPrice->parameters, true);
                $sheetStructure = $data['sheet_structure'] ?? null;
                $smetaTitle = str_replace('{order}', $titleOrder, $invoiceType->custom_order_title);
                if ($sheetStructure) {
                    $this->sheetStructures[] = [
                        'title' => $smetaTitle,
                        'data' => $sheetStructure
                    ];

                    // Calculate totals
                    foreach ($sheetStructure['sections'] as $section) {
                        foreach ($section['labourItems'] as $item) {
                            if (isset($item['labourTotal']) && is_numeric($item['labourTotal'])) {
                                $totalLabour += $item['labourTotal'];
                            }
                        }
                        foreach ($section['materialItems'] as $item) {
                            if (isset($item['materialTotal']) && is_numeric($item['materialTotal'])) {
                                $totalMaterial += $item['materialTotal'];
                            }
                        }
                    }
                }
            }
        }

        $this->totals = [
            'labour' => $totalLabour,
            'material' => $totalMaterial,
            'total' => $totalLabour + $totalMaterial,
            'shipping' => $this->calculateShipping($totalMaterial) // Implement this method based on your business logic
        ];
    }

    public function handle()
    {
        $pdf = new PDF_Russian('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Helvetica', 'B', 16);

        $this->addTitle($pdf);
        $this->addGeneralInfo($pdf);

        foreach ($this->sheetStructures as $sheetStructure) {
            $this->addSheetStructure($pdf, $sheetStructure);
        }

        $this->addSummary($pdf);

        // Save the PDF file
        $publicPath = public_path('orders');
        $filename = $this->project->id . "_" . $this->project->design_id . "_" . time() . ".pdf";
        $outputPath = $publicPath . '/' . $filename;

        if (!file_exists($publicPath)) {
            mkdir($publicPath, 0755, true);
        }

        $pdf->Output('F', $outputPath);
        dd($outputPath);
        // Generate public URL
        $publicUrl = url('orders/' . $filename);

        // Update the project with the PDF file path
        $this->project->update(['pdf_filepath' => $publicUrl]);
    }

    protected function addTitle($pdf)
    {
        $pdf->Cell(0, 10, "Детали заказа - " . $this->project->human_ref, 0, 1, 'C');
        $pdf->Ln(10);
    }

    protected function addGeneralInfo($pdf)
    {
        $pdf->SetFont('Helvetica', '', 12);
        $pdf->Cell(0, 10, "Проект: " . $this->designTitle, 0, 1);
        $pdf->Cell(0, 10, "Дата создания: " . $this->project->created_at->format('d.m.Y H:i'), 0, 1);
        $pdf->Cell(0, 10, "Общая стоимость: " . $this->formatPrice($this->totals['total']) . " руб.", 0, 1);
        $pdf->Ln(10);
    }

    protected function addSheetStructure($pdf, $sheetStructure)
    {
        $pdf->SetFont('Helvetica', 'B', 14);
        $pdf->Cell(0, 10, $sheetStructure['title'], 0, 1);
        $pdf->Ln(5);

        foreach ($sheetStructure['data']['sections'] as $section) {
            $this->addSection($pdf, $section);
        }

        $pdf->Ln(10);
    }

    protected function addSection($pdf, $section)
    {
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->Cell(0, 10, $section['value'], 0, 1, 'L');
        $pdf->Ln(5);

        $this->addLabourItems($pdf, $section['labourItems']);
        $this->addMaterialItems($pdf, $section['materialItems']);

        $pdf->SetFont('Helvetica', 'B', 10);
        $sectionTotalLabour = $this->displayLabour ? $section['sectionTotalLabour'] : 0;
        $pdf->Cell(0, 10, "Итого по разделу: " . $this->formatPrice($sectionTotalLabour + $section['sectionTotalMaterial']), 0, 1, 'R');
        $pdf->Ln(10);
    }

    protected function addLabourItems($pdf, $items)
    {
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->Cell(10, 7, '№', 1);
        $pdf->Cell(80, 7, 'Наименование работ', 1);
        $pdf->Cell(20, 7, 'Ед. изм.', 1);
        $pdf->Cell(20, 7, 'Кол-во', 1);
        $pdf->Cell(30, 7, 'Цена', 1);
        $pdf->Cell(30, 7, 'Сумма', 1);
        $pdf->Ln();

        foreach ($items as $item) {
            if ($item['labourTitle']) {
                $pdf->Cell(10, 7, $item['labourNumber'] ?? '', 1);
                $pdf->Cell(80, 7, $item['labourTitle'], 1);
                $pdf->Cell(20, 7, $item['labourUnit'], 1);
                $pdf->Cell(20, 7, $item['labourQuantity'], 1);
                $pdf->Cell(30, 7, $this->formatPrice($item['labourPrice'] ?? 0, $this->displayLabour), 1);
                $pdf->Cell(30, 7, $this->formatPrice($item['labourTotal'] ?? 0, $this->displayLabour), 1);
                $pdf->Ln();
            }
        }
    }

    protected function addMaterialItems($pdf, $items)
    {
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->Cell(90, 7, 'Наименование материалов', 1);
        $pdf->Cell(20, 7, 'Ед. изм.', 1);
        $pdf->Cell(20, 7, 'Кол-во', 1);
        $pdf->Cell(30, 7, 'Цена', 1);
        $pdf->Cell(30, 7, 'Сумма', 1);
        $pdf->Ln();

        foreach ($items as $item) {
            if ($item['materialTitle']) {
                $pdf->Cell(90, 7, $item['materialTitle'], 1);
                $pdf->Cell(20, 7, $item['materialUnit'], 1);
                $pdf->Cell(20, 7, $item['materialQuantity'], 1);
                $pdf->Cell(30, 7, $this->formatPrice($item['materialPrice']), 1);
                $pdf->Cell(30, 7, $this->formatPrice($item['materialTotal']), 1);
                $pdf->Ln();
            }
        }
    }

    protected function addSummary($pdf)
    {
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Итоговая сумма', 0, 1, 'L');
        $pdf->Ln(5);

        $pdf->SetFont('Helvetica', '', 10);
        $total = $this->totals['total'];
        $pdf->Cell(0, 8, "Всего по смете: " . $this->formatPrice($total) . " руб.", 0, 1);
        
        // Add more summary details as needed
        $pdf->Cell(0, 8, "Стоимость Работ: " . $this->formatPrice($this->totals['labour'], $this->displayLabour) . " руб.", 0, 1);
        $pdf->Cell(0, 8, "Стоимость Материалов: " . $this->formatPrice($this->totals['material']) . " руб.", 0, 1);
        $pdf->Cell(0, 8, "Стоимость Доставки: " . $this->formatPrice($this->totals['shipping']) . " руб.", 0, 1);
    }

    protected function formatPrice($number, $displayLabour = true)
    {
        if (!$displayLabour && $number === $this->totals['labour']) {
            return '0.00';
        }
        $number = is_string($number) ? (float)$number : $number;
        return number_format($number, 2, '.', ' ');
    }

    protected function calculateShipping($totalMaterial)
    {
        // Implement shipping calculation logic here
        // This is a placeholder implementation
        return $totalMaterial * 0.05; // 5% of material cost as shipping
    }
}