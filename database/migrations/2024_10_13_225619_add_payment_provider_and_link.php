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
            $table->string('payment_link')->nullable()->after('ip_address');
            $table->string('payment_status')->nullable()->after('payment_reference');
            $table->string('price_type')->default('material')->after('configuration_descriptions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('payment_link');
            $table->dropColumn('payment_status');
            $table->dropColumn('price_type');
        });
    }
};
