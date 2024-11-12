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
        $table->unsignedBigInteger('foundation_id')->nullable()->after('design_id');
        $table->foreign('foundation_id')->references('id')->on('foundations')->onDelete('set null');
        $table->unsignedBigInteger('design_id')->nullable()->change();
    });
}

public function down()
{
    Schema::table('projects', function (Blueprint $table) {
        $table->dropForeign(['foundation_id']);
        $table->dropColumn('foundation_id');
        $table->unsignedBigInteger('design_id')->nullable(false)->change();
    });
}
};
