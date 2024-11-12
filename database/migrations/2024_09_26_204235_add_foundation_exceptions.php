<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Foundation; // Add this line to import the Foundation model

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('foundations', function (Blueprint $table) {
            $table->longText('exceptions')->nullable();
        });

        // Seed the exceptions data
        $this->seedExceptions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('foundations', function (Blueprint $table) {
            $table->dropColumn('exceptions');
        });
    }

    /**
     * Seed the exceptions data.
     */
    private function seedExceptions(): void
    {
        $exceptionsData = [
            'lenta' => [
                'additionalLines' => 'ROW_PAIR_A'
            ],
            'pLenta' => [
                'additionalLines' => 'ROW_PAIR_A',
                'dynamicSwaps' => [
                    '{A}' => 'J4',
                ],
                'sectionTitle' => [
                    [
                        "compare_against" => "Плита перекрытия монолитная", 
                        "change_to" => "3. Плита перекрытия монолитная  - {A} мм"
                    ]
                ]
            ],
            'plita' => [
                'dynamicSwaps' => [
                    '{A}' => 'I5',
                    '{B}' => 'L5'
                ],
                'title' => '{original} {A} мм. Отмосток {B}'
            ],
            'sv' => [
                'additionalLines' => 'ROW_PAIR_B'
            ],
            'svs' => [
                'additionalLines' => 'ROW_PAIR_A'
            ],
        ];

        foreach ($exceptionsData as $title => $exceptions) {
            $foundation = Foundation::where('title', $title)->first();
            if ($foundation) {
                $foundation->exceptions = $exceptions;
                $foundation->save();
            }
        }
    }
};