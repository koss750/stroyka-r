<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
 *      * Run the migrations.
 *           *
 *                * @return void
 *                     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('category_id');
            $table->decimal('total_area');
            $table->decimal('length');
            $table->decimal('width');
            $table->foreignId('material_type_id');
            $table->decimal('floor');
            $table->decimal('roof_area');
            $table->decimal('foundation');
            $table->string('OCB200_0');
            $table->string('OCB200_1');
            $table->string('OCB200_2');
            $table->string('OCB200_3');
            $table->string('OCB200_4');
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
        Schema::dropIfExists('projects');
    }
}

