<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('invoice_items');
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->integer('reference');
            $table->string('invoice_id');
            $table->string('section');
            $table->string('side')->nullable(); // Assuming 'Side' can be nullable
            $table->string('title');
            $table->string('unit');
            $table->string('quantity')->nullable();
            $table->string('cost')->nullable();
            $table->string('default_q')->nullable();
            $table->string('default_c')->nullable();
            $table->string('params')->nullable(); // Assuming 'qModel' can be nullable
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_items');
    }
};
