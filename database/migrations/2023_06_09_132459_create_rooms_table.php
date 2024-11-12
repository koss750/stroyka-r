<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    /**
 *      * Run the migrations.
 *           *
 *                * @return void
 *                     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects');
            $table->string('room_title');
            $table->string('room_type');
            $table->integer('order');
            $table->decimal('width');
            $table->decimal('length');
            $table->timestamps();
        });
    }

    /**
 *      * Reverse the migrations.
 *           *
 *                * @return void
 *                     */
    public function down()
    {
        Schema::dropIfExists('rooms');
    }
}

