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
        Schema::create('foundation_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type');
            $table->string('scale');
            $table->integer('width');
            $table->json('drawing_data');
            $table->decimal('total_area', 10, 2);
            $table->decimal('perimeter', 10, 2);
            $table->integer('angles');
            $table->integer('t_junctions');
            $table->integer('x_crossings');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foundation_plans');
    }
};
