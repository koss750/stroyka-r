<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAreaFieldsToDesignsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('designs', function (Blueprint $table) {
            $table->longText('areafl0')->nullable();
            $table->string('stolby', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('designs', function (Blueprint $table) {
            $table->dropColumn(['areafl0', 'stolby']);
        });
    }
}
