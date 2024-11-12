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
        Schema::create('project_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('design_id');
            $table->unsignedBigInteger('invoice_type_id');
            $table->string('price');
            $table->timestamps();
            $table->foreign('design_id')->references('id')->on('designs')->onDelete('cascade');
            $table->foreign('invoice_type_id')->references('id')->on('invoice_structures')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_prices');
    }
};
