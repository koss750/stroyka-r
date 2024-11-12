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
        Schema::create('price_plans', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('currency')->default('RUB');
            $table->integer('validity_days')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('total_uses')->nullable();
            $table->integer('limit_uses')->nullable();
            $table->timestamp('valid_from')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_plans');
    }
};