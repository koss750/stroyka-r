<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('excel_sheet_costs');
        Schema::create('excel_sheet_costs', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->string('sheet_title');
            $table->string('sheet_code');
            $table->string('item_title');
            $table->decimal('current_value')->optional();
            $table->string('item_value_cell');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('excel_sheet_costs');
    }
};
