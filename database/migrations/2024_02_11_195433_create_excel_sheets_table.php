<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExcelSheetsTable extends Migration
{
    public function up()
    {
        Schema::create('excel_sheets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();;
            $table->string('type')->nullable();;
            $table->longText('params')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('excel_sheets');
    }
}
