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
        Schema::dropIfExists('associated_costs');
        
        Schema::create('associated_costs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('filename');
            $table->string('description');
            $table->string('location_cell');
            $table->string('location_sheet');
            $table->string('value');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('associated_costs');
    }
};
