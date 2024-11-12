<?php

namespace Stroyka\ExportTool;
// Import necessary classes at the top of the file
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;


use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class ExportTool extends Tool
{
    
    // app/Nova/ExportTool.php



public function card()
{
    return [
        // ...

        (new DownloadExcel)
            ->withName('export.xlsx') // Specify the export file name
            ->withWriterType(\Maatwebsite\Excel\Excel::XLSX) // Choose the file format (XLSX, CSV, etc.)
            ->withHeadings() // Include column headings
            ->allColumns(), // Export all columns from the resource
    ];
}

    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('ExportTool', __DIR__.'/../dist/js/tool.js');
        Nova::style('ExportTool', __DIR__.'/../dist/css/tool.css');
    }

    /**
     * Build the menu that renders the navigation links for the tool.
     *
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     */
    public function menu(Request $request)
    {
        return MenuSection::make('Exporttool')
            ->path('/ExportTool')
            ->icon('server');
    }
}
