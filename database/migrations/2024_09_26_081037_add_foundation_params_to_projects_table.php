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
        Schema::table('projects', function (Blueprint $table) {
            $table->string('order_type')->default('smeta')->after('human_ref');
            $table->string('is_example')->default(false)->after('order_type');
            $table->longText('foundation_params')->nullable()->after('foundation_id');
        });
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('foundation_params');
            $table->dropColumn('order_type');
            $table->dropColumn('is_example');
        });
    }
};
