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
        Schema::create('dynamic_page_cards', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // "home" or "foundation"
            $table->string('title');
            $table->string('link');
            $table->string('image_url');
            $table->string('dimensions');
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dynamic_page_cards');
    }
};
