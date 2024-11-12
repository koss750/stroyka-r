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
        Schema::table('price_plans', function (Blueprint $table) {
            $table->boolean('static_price')->default(false)->after('type');
            $table->string('dependent_entity', 15)->nullable()->after('dependent_parameter');
            $table->string('dependent_type', 15)->nullable()->after('dependent_entity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('price_plans', function (Blueprint $table) {
            $table->dropColumn('static_price');
            $table->dropColumn('dependent_entity');
            $table->dropColumn('dependent_type');
        });
    }
};
