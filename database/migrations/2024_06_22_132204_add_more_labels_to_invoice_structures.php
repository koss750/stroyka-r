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
            $table->text('nova_tab_label')->nullable();
            $table->text('nova_label')->nullable();
            $table->text('site_tab_label')->nullable();
            $table->text('site_label')->nullable();
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
            $table->dropColumn('nova_tab_label');
            $table->dropColumn('nova_label');
            $table->dropColumn('site_tab_label');
            $table->dropColumn('site_label');
        });
    }
};
