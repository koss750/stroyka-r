<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop existing table
        Schema::dropIfExists('settings');

        // Create new structure
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type');
            $table->json('options')->nullable();
            $table->json('value');
            $table->boolean('enabled')->default(true);
            $table->string('affected_users')->default('all');
            $table->string('affected_areas')->default('site');
            $table->timestamps();
        });

        // Insert data
        $this->insertData();
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }

    private function insertData(): void
    {
        $settings = [
            [
                'key' => 'display_prices',
                'name' => 'Отображать цены',
                'type' => 'select',
                'options' => json_encode(['material', 'labour', 'total']),
                'value' => json_encode('material'),
                'enabled' => true,
                'affected_users' => 'all',
                'affected_areas' => 'site',
            ],
            [
                'key' => 'tooltip_label_material',
                'name' => 'Описание цены (материалы)',
                'type' => 'text',
                'options' => null,
                'value' => json_encode('Цена включает все материалы на строительство.'),
                'enabled' => true,
                'affected_users' => 'all',
                'affected_areas' => 'site',
            ],
            [
                'key' => 'tooltip_label_total',
                'name' => 'Описание цены (материалы+работы)',
                'type' => 'text',
                'options' => null,
                'value' => json_encode('Цена включает все материалы на строительство и стоимость работ.'),
                'enabled' => true,
                'affected_users' => 'all',
                'affected_areas' => 'site',
            ],
            [
                'key' => 'tooltip_label_labour',
                'name' => 'Описание цены (работы)',
                'type' => 'text',
                'options' => null,
                'value' => json_encode([]),
                'enabled' => true,
                'affected_users' => 'all',
                'affected_areas' => 'site',
            ],
            [
                'key' => 'decimal_places',
                'name' => 'Числа после запятой',
                'type' => 'nested_select',
                'options' => json_encode(['material', 'labour']),
                'value' => json_encode([
                    'material' => [
                        '0' => ['банк', 'бух', 'ведро', 'к-т', 'лист', 'маш', 'пач.', 'пачка', 'рол', 'рул', 'уп.', 'шт', 'п.м.', 'м/см', 'смена'],
                        '2' => ['м2'],
                        '3' => ['м3', 'кг', 'тн'],
                    ],
                    'labour' => [
                        '0' => ['банк', 'бух', 'ведро', 'к-т', 'лист', 'маш', 'пач.', 'пачка', 'рол', 'рул', 'уп.', 'шт', 'м/см', 'смена'],
                        '1' => ['п.м.'],
                        '2' => ['м2'],
                        '3' => ['м3', 'кг', 'тн'],
                    ],
                ]),
                'enabled' => true,
                'affected_users' => 'all',
                'affected_areas' => 'site',
            ],
            [
                'key' => 'replacement_array',
                'name' => 'Замены единиц измерения',
                'type' => 'key_value',
                'options' => json_encode(['к-кт', 'пач.', 'уп', 'м.п.', 'м.п', 'п.м', 'упак', 'тн.', 'см']),
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
                'enabled' => true,
                'affected_users' => 'all',
                'affected_areas' => 'site',
            ],
            [
                'key' => 'format',
                'name' => 'Формат чисел',
                'type' => 'select',
                'options' => json_encode([0, 1, 2, 3]),
                'value' => json_encode(['0', '0.0', '0.00', '0.000']),
                'enabled' => true,
                'affected_users' => 'all',
                'affected_areas' => 'site',
            ],
            [
                'key' => 'format_exceptions',
                'name' => 'Исключения для форматов',
                'type' => 'key_value',
                'options' => json_encode(['Монтаж межвенцового утеплителя']),
                'value' => json_encode(['Монтаж межвенцового утеплителя' => 0]),
                'enabled' => true,
                'affected_users' => 'all',
                'affected_areas' => 'site',
            ],
            [
                'key' => 'sub_headings',
                'name' => 'Подзаголовки',
                'type' => 'nested_select',
                'options' => json_encode([1, 2]),
                'value' => json_encode([
                    '1' => ['partial' => 'Grand Line серия Classic'],
                    '2' => ['full' => 'Расходные материалы ']
                ]),
                'enabled' => true,
                'affected_users' => 'all',
                'affected_areas' => 'site',
            ],
            [
                'key' => 'sub_heading_params',
                'name' => 'Параметры подзаголовков',
                'type' => 'nested_boolean',
                'options' => json_encode([1, 2]),
                'value' => json_encode([
                    '1' => ['entireRow' => false],
                    '2' => ['entireRow' => true]
                ]),
                'enabled' => true,
                'affected_users' => 'all',
                'affected_areas' => 'site',
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->insert($setting);
        }
    }
};