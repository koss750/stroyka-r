<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_structures', function (Blueprint $table) {
            $table->dropColumn('default_value');
            $table->integer('default_value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_structures', function (Blueprint $table) {
            $table->dropColumn('default_value');
            $table->integer('default_value')->nullable();
        });
    }
};
