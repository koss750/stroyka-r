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
        Schema::table('designs', function (Blueprint $table) {
            $table->decimal('mMetrics', 4, 3)->change();
            $table->integer('numOrders')->change();
            $table->string('wallsOut')->change();
            $table->string('wallsIn')->change();
            $table->string('wallsPerOut')->change();
            $table->string('wallsPerIn')->change();
            $table->integer('vfCount')->change();
            $table->string('lfLength')->change();
            $table->string('vfBalk')->change();
            $table->string('mfSquare')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('designs', function (Blueprint $table) {
            $table->integer('mMetrics')->change();
            $table->integer('numOrders')->change();
            $table->integer('wallsOut')->change();
            $table->integer('wallsIn')->change();
            $table->integer('wallsPerOut')->change();
            $table->integer('wallsPerIn')->change();
            $table->integer('vfCount')->change();
            $table->integer('lfLength')->change();
            $table->integer('vfBalk')->change();
            $table->integer('mfSquare')->change();
        });
    }
};
