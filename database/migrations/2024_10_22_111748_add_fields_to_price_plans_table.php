<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\PricePlan;

return new class extends Migration
{
    public function up()
    {
        // First, ensure the new columns are added
        Schema::table('price_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('price_plans', 'type')) {
                $table->string('type')->after('code')->nullable();
            }
            if (!Schema::hasColumn('price_plans', 'dependent_parameter')) {
                $table->string('dependent_parameter', 30)->nullable()->after('type');
            }
            if (!Schema::hasColumn('price_plans', 'parameter_option')) {
                $table->longText('parameter_option')->nullable()->after('dependent_parameter');
            }
        });

        // Now, seed the initial data
        $plans = [
            [
                'title' => 'Смета Проект',
                'code' => 'smeta_project',
                'type' => 'document',
                'dependent_parameter' => 'size',
                'parameter_option' => json_encode([
                    '0-50' => 200,
                    '51-100' => 450,
                    '101-150' => 700,
                    '151-200' => 950,
                    '201-250' => 1200,
                    '251-300' => 1450,
                    '300+' => 1701
                ]),
                'description' => 'Ценовой план для сметы проекта',
                'currency' => 'RUB',
                'validity_days' => 365,
                'is_active' => true,
                'valid_from' => now(),
            ],
            [
                'title' => 'Смета фундамент',
                'code' => 'smeta_foundation',
                'type' => 'document',
                'dependent_parameter' => 'size',
                'parameter_option' => json_encode([
                    '0-50' => 200,
                    '51-100' => 450,
                    '101-150' => 700,
                    '151-200' => 950,
                    '201-250' => 1200,
                    '251-300' => 1450,
                    '300+' => 1700
                ]),
                'description' => 'Ценовой план для сметы фундамента',
                'currency' => 'RUB',
                'validity_days' => 365,
                'is_active' => true,
                'valid_from' => now(),
            ],
            [
                'title' => 'Корпоративная подписка (Стартовое предложение)',
                'code' => 'corporate_subscription_startup',
                'type' => 'subscription',
                'dependent_parameter' => null,
                'parameter_option' => null,
                'description' => 'Стартовое предложение для корпоративных пользователей (видимость на сайте)',
                'price' => 1,
                'currency' => 'RUB',
                'validity_days' => 30,
                'is_active' => true,
                'valid_from' => now(),
                'limit_uses' => null, // You might want to set a limit for this offer
            ],
        ];

        foreach ($plans as $plan) {
            PricePlan::create($plan);
        }
    }

    public function down()
    {
        // Remove the seeded data
        PricePlan::whereIn('type', ['document', 'subscription'])->delete();

        // Remove the added columns if necessary
        Schema::table('price_plans', function (Blueprint $table) {
            $table->dropColumn(['type', 'dependent_parameter', 'parameter_option']);
        });
    }
};