<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            //$table->string('enabled')->default('true')->nullable();
            //$table->string('option_type')->default('select')->nullable();
            //$table->text('option_values')->nullable();
        });

        $settings = [
            [
                'title' => 'decimal_places',
                'label' => 'Числа после запятой',
                'value' => json_encode([
                    'material' => [
                        0 => ['банк', 'бух', 'ведро', 'к-т', 'лист', 'маш', 'пач.', 'пачка', 'рол', 'рул', 'уп.', 'шт', 'п.м.', 'м/см', 'смена'],
                        2 => ['м2'],
                        3 => ['м3', 'кг', 'тн'],
                    ],
                    'labour' => [
                        0 => ['банк', 'бух', 'ведро', 'к-т', 'лист', 'маш', 'пач.', 'пачка', 'рол', 'рул', 'уп.', 'шт', 'м/см', 'смена'],
                        1 => ['п.м.'],
                        2 => ['м2'],
                        3 => ['м3', 'кг', 'тн'],
                    ],
                ]),
                'enabled' => 'true',
                'option_type' => 'select',
                'option_values' => json_encode(['material', 'labour']),
            ],
            [
                'title' => 'replacement_array',
                'label' => 'Замены единиц измерения',
                'value' => json_encode([
                    'к-кт' => 'к-т',
                    'пач.' => 'пачка',
                    'уп' => 'уп.',
                    'м.п.' => 'п.м.',
                    'м.п' => 'п.м.',
                    'п.м' => 'п.м.',
                    'упак' => 'уп.',
                    'тн.' => 'тн',
                    'см' => 'смена'
                ]),
                'enabled' => 'true',
                'option_type' => 'select',
                'option_values' => json_encode(['к-кт', 'пач.', 'уп', 'м.п.', 'м.п', 'п.м', 'упак', 'тн.', 'см']),
            ],
            [
                'title' => 'format',
                'label' => 'Формат чисел',
                'value' => json_encode([
                    0 => "0",
                    1 => "0.0",
                    2 => "0.00",
                    3 => "0.000"
                ]),
                'enabled' => 'true',
                'option_type' => 'select',
                'option_values' => json_encode([0, 1, 2, 3]),
            ],
            [
                'title' => 'format_exceptions',
                'label' => 'Исключения для форматов',
                'value' => json_encode([
                    'Монтаж межвенцового утеплителя' => 0
                ]),
                'enabled' => 'true',
                'option_type' => 'select',
                'option_values' => json_encode(['Монтаж межвенцового утеплителя']),
            ],
            [
                'title' => 'sub_headings',
                'label' => 'Подзаголовки',
                'value' => json_encode([
                    1 => ["partial" => "Grand Line серия Classic"],
                    2 => ["full" => "Расходные материалы "]
                ]),
                'enabled' => 'true',
                'option_type' => 'select',
                'option_values' => json_encode([1, 2]),
            ],
            [
                'title' => 'sub_heading_params',
                'label' => 'Параметры подзаголовков',
                'value' => json_encode([
                    1 => ["entireRow" => false],
                    2 => ["entireRow" => true]
                ]),
                'enabled' => 'true',
                'option_type' => 'select',
                'option_values' => json_encode([1, 2]),
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->insert($setting);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('enabled');
            $table->dropColumn('option_type');
            $table->dropColumn('option_values');
        });
    }
};
