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
        Schema::table('invoice_structures', function (Blueprint $table) {
            $table->string('unique_btn_group')->nullable()->default(null);
            $table->integer('unique_order')->nullable()->default(null);
            $table->boolean('unique_default')->nullable()->default(null);
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
            $table->dropColumn('unique_btn_group');
            $table->dropColumn('unique_order');
            $table->dropColumn('unique_default');
        });
    }
};
