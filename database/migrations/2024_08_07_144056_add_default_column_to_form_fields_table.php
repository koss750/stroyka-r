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
        Schema::table('form_fields', function (Blueprint $table) {
            $table->string('default')->nullable()->after('excel_cell');
        });
    }

    public function down()
    {
        Schema::table('form_fields', function (Blueprint $table) {
            $table->dropColumn('default');
        });
    }
};
