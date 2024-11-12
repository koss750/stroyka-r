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
        Schema::create('design_materials', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Example field for material name
            $table->string('subsection')->nullable(); // Example field for material subcategory
            $table->text('description')->nullable(); // Example field for description
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('design_materials');
    }
};
