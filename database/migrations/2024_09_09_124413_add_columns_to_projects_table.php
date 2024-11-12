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
        Schema::table('projects', function (Blueprint $table) {
            $table->string('order_type', 10);
            $table->string('human_ref', 10);
            $table->json('configuration_descriptions')->after('selected_configuration')->nullable();
            $table->string('payment_provider', 10)->default("test");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('order_type');
            $table->dropColumn('human_ref');
            $table->dropColumn('payment_provider');
        });
    }
};
