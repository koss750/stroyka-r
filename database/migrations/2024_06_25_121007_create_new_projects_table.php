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
    Schema::dropIfExists('projects');
    
    Schema::create('projects', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id')->nullable();
        $table->string('ip_address');
        $table->string('payment_reference');
        $table->decimal('payment_amount', 10, 2);
        $table->unsignedBigInteger('design_id');
        $table->json('selected_configuration');
        $table->string('filepath')->nullable();
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        $table->foreign('design_id')->references('id')->on('designs');
    });
    }
   

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
