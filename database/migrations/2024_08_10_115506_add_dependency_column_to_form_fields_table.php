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
            $table->string('depends_on')->nullable();
            $table->string('depends_value')->nullable();
        });
    }

    public function down()
    {
        Schema::table('form_fields', function (Blueprint $table) {
            $table->dropColumn(['depends_on', 'depends_value']);
        });
    }
};
