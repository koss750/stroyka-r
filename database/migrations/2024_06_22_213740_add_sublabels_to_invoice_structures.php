<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoice_structures', function (Blueprint $table) {
            $table->text('site_sub_label')->nullable();
            $table->text('site_level4_label')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_structures', function (Blueprint $table) {
            $table->dropColumn('site_sub_label');
            $table->dropColumn('site_level4_label');
        });
    }
};
