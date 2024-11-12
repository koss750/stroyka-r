<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExchangeRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('btc_rate_supplier')->nullable();
            $table->decimal('btc_buy_rate', 16, 2)->nullable();
            $table->decimal('btc_sell_rate', 16, 2)->nullable();
            $table->string('rub_rate_supplier')->nullable();
            $table->decimal('rub_buy_rate', 16, 2)->nullable();
            $table->decimal('rub_sell_rate', 16, 2)->nullable();
            $table->decimal('gbp_to_rub_rate', 16, 2);
            $table->decimal('rub_to_gbp_rate', 16, 2);
            $table->decimal('spread', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exchange_rates');
    }
}
