<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
        {
            Schema::create('daily_average_rates', function (Blueprint $table) {
                $table->id();
                $table->string('datestamp')->nullable();
                $table->decimal('effective_gbp_to_rub', 10, 2)->nullable();
                $table->decimal('effective_rub_to_gbp', 10, 2)->nullable();
                $table->decimal('spread_percentage', 10, 2)->nullable();
                $table->decimal('btc_buy_rate', 10, 2)->nullable();
                $table->decimal('btc_sell_rate', 10, 2)->nullable();
                $table->decimal('rub_buy_rate', 10, 2)->nullable();
                $table->decimal('rub_sell_rate', 10, 2)->nullable();
                $table->timestamps();
            });
        }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_average_rates');
        Schema::dropIfExists('daily_rates');
    }
};
