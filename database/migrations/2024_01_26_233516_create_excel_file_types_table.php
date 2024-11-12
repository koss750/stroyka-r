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
        Schema::dropIfExists('excel_file_types');
        
        Schema::create('excel_file_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('subtype');
            $table->string('file');
            $table->longText('associatedCosts')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excel_file_types');
    }
};
