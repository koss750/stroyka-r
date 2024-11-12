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
            Schema::create('design_prices', function (Blueprint $table) {
                $table->id();
                $table->foreignId('design_id')->constrained(); // Foreign key to designs table
                $table->foreignId('design_material_id')->constrained(); // Foreign key to design_materials table
                $table->decimal('amount', 11, 2); // Field for price amount
                $table->timestamps();
            });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('design_prices');
    }
};
