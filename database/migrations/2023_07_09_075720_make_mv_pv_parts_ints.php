<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('designs', function (Blueprint $table) {
            $table->integer('pvPart1')->change();
            $table->integer('pvPart2')->change();
            $table->integer('pvPart3')->change();
            $table->integer('pvPart4')->change();
            $table->integer('pvPart5')->change();
            $table->integer('pvPart6')->change();
            $table->integer('pvPart7')->change();
            $table->integer('pvPart8')->change();
            $table->integer('pvPart9')->change();
            $table->integer('pvPart10')->change();
            $table->integer('pvPart11')->change();
            $table->integer('pvPart12')->change();
            $table->integer('mvPart1')->change();
            $table->integer('mvPart2')->change();
            $table->integer('mvPart3')->change();
            $table->integer('mvPart4')->change();
            $table->integer('mvPart5')->change();
            $table->integer('mvPart6')->change();
            $table->integer('mvPart7')->change();
            $table->integer('mvPart8')->change();
            $table->integer('mvPart9')->change();
            $table->integer('mvPart10')->change();
            $table->integer('mvPart11')->change();
            $table->integer('mvPart12')->change(); 
            $table->longText('endovList')->change();
            $table->longText('floorsList')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('designs', function (Blueprint $table) {
            $table->decimal('pvPart1')->change();
            $table->decimal('pvPart2')->change();
            $table->decimal('pvPart3')->change();
            $table->decimal('pvPart4')->change();
            $table->decimal('pvPart5')->change();
            $table->decimal('pvPart6')->change();
            $table->decimal('pvPart7')->change();
            $table->decimal('pvPart8')->change();
            $table->decimal('pvPart9')->change();
            $table->decimal('pvPart10')->change();
            $table->decimal('pvPart11')->change();
            $table->decimal('pvPart12')->change();
            $table->decimal('mvPart1')->change();
            $table->decimal('mvPart2')->change();
            $table->decimal('mvPart3')->change();
            $table->decimal('mvPart4')->change();
            $table->decimal('mvPart5')->change();
            $table->decimal('mvPart6')->change();
            $table->decimal('mvPart7')->change();
            $table->decimal('mvPart8')->change();
            $table->decimal('mvPart9')->change();
            $table->decimal('mvPart10')->change();
            $table->decimal('mvPart11')->change();
            $table->decimal('mvPart12')->change();
        });     
    }
};
