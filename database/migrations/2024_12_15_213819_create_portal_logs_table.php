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
        Schema::create('portal_logs', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('loggable'); // This allows linking to any model
            $table->string('action');
            $table->string('action_type');
            $table->json('details')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portal_logs');
    }
};
