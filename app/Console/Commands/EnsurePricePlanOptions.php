<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PricePlan;

class EnsurePricePlanOptions extends Command
{
    protected $signature = 'priceplan:ensure-options';
    protected $description = 'Ensure all options are covered for non-static price plans';

    public function handle()
    {
        $pricePlans = PricePlan::where('static_price', false)->get();

        foreach ($pricePlans as $plan) {
            if ($plan->dependent_type === 'specific') {
                $this->ensureSpecificOptions($plan);
            } elseif ($plan->dependent_type === 'range') {
                $this->ensureRangeOptions($plan);
            }
        }

        $this->info('Price plan options have been updated.');
    }

    protected function ensureSpecificOptions($plan)
    {
        $entityClass = "App\\Models\\{$plan->dependent_entity}";
        $options = $entityClass::pluck($plan->dependent_parameter)->unique();

        $currentOptions = collect($plan->parameter_option)->keyBy('value');

        foreach ($options as $option) {
            if (!$currentOptions->has($option)) {
                $currentOptions[$option] = ['value' => $option, 'price' => 0];
            }
        }

        $plan->parameter_option = $currentOptions->values()->toArray();
        $plan->save();
    }

    protected function ensureRangeOptions($plan)
    {
        // For range type, we might not need to do anything as it should cover all possible values
        // But you could add logic here if needed
    }
}