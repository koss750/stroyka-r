<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use App\Models\Design;
use App\Models\InvoiceType;
use Illuminate\Support\Facades\DB;

class GeneratePurchaseStats extends Command
{
    protected $signature = 'stats:purchases';
    protected $description = 'Generate purchase statistics for designs and invoice types';

    public function handle()
    {
        $this->info('Generating purchase statistics...');

        // Design statistics
        $designStats = Design::select('designs.id', 'designs.title', 'designs.view_count')
            ->selectRaw('COUNT(projects.id) as purchases')
            ->selectRaw('SUM(projects.payment_amount) as revenue')
            ->leftJoin('projects', 'designs.id', '=', 'projects.design_id')
            ->groupBy('designs.id', 'designs.title', 'designs.view_count')
            ->orderByDesc('purchases')
            ->get()
            ->map(function ($design) {
                return [
                    'id' => $design->id,
                    'name' => $design->title,
                    'purchases' => $design->purchases,
                    'views' => $design->view_count,
                    'revenue' => $design->revenue,
                ];
            });

        $this->table(
            ['ID', 'Name', 'Purchases', 'Views', 'Revenue'],
            $designStats->toArray()
        );

        // Invoice Type statistics
        $invoiceTypeStats = InvoiceType::all()->map(function ($invoiceType) {
            $usageCount = Project::where('selected_configuration', 'like', '%"' . $invoiceType->ref . '"%')->count();
            return [
                'id' => $invoiceType->id,
                'name' => $invoiceType->label,
                'ref' => $invoiceType->ref,
                'usage_count' => $usageCount,
            ];
        })->sortByDesc('usage_count')->values();

        $this->table(
            ['ID', 'Name', 'Ref', 'Usage Count'],
            $invoiceTypeStats->toArray()
        );
    }
}