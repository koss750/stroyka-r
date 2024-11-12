<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FormField;
use App\Models\Template;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class IndexFoundationCommand extends Command
{
    protected $signature = 'app:index-f';
    protected $description = 'Index foundation templates and store default values';

    public function handle()
{
    $templates = Template::where('category', '!=', 'main')->get();

    foreach ($templates as $template) {
        $this->info("Processing template: {$template->name}");

        $filePath = storage_path("app/" . $template->file_path);
        
        if (!file_exists($filePath)) {
            $this->warn("Template file not found: {$filePath}. Skipping.");
            continue;
        }

        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getSheet(0); // Get the first sheet (index 0)

        $fields = FormField::where('form_type', $template->category)
            ->get();

        foreach ($fields as $field) {
            if ($field->excel_cell) {
                $cellAddress = $field->excel_cell;
                $currentValue = $worksheet->getCell($cellAddress)->getValue();
                $field->default = $currentValue;
                $field->save();

                $this->line("Updated {$field->name} with default value: {$currentValue} from cell {$cellAddress}");
            }
        }

        $this->info("Completed processing template: {$template->name}");
    }

    $this->info('All foundation templates have been indexed successfully.');
}

// ... rest of the class remains the same
    private function getNextCellAddress($cellAddress)
{
        preg_match('/([A-Z]+)(\d+)/', $cellAddress, $matches);
        $column = $matches[1];
        $row = $matches[2];
        
        $nextColumn = chr(ord($column) + 1);
        return $nextColumn . $row;
    }
}