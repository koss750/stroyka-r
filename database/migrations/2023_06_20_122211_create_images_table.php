<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('designs', function (Blueprint $table) {
            $table->id()->startingValue(1200)->change();
        });
        
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('design_id');
            $table->string('title');
            $table->string('filename');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
            $table->foreign('design_id')->references('id')->on('designs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
        Schema::table('designs', function (Blueprint $table) {
            $table->increments('id')->change();
        });
    }
}