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
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('status')->default('pending'); // New status field
            $table->string('type_of_organisation')->nullable(); // New type_of_organisation field
            $table->string('region_code')->nullable(); // New region_code field
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn(['status', 'type_of_organisation', 'region_code']); // Drop new fields
        });
    }
};
