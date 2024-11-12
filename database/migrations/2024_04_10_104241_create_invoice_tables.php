<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('invoice_sections');
        Schema::dropIfExists('invoice_types');
        Schema::dropIfExists('invoice_structures');
        Schema::dropIfExists('invoices');
        
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('set');
            $table->longtext('params')->nullable();
            $table->string('pdf_link')->nullable();
            $table->timestamps();
        });

        Schema::create('invoice_structures', function (Blueprint $table) {
            $table->id();
            $table->string('ref');
            $table->string('title');
            $table->string('depth');
            $table->string('parent');
            $table->string('label');
            $table->string('end');
            $table->string('params')->nullable();
            $table->timestamps();
        });
        Schema::create('invoice_sections', function (Blueprint $table) {
            $table->id();
            $table->string('parent_invoice_structure');
            $table->string('section');
            $table->string('visibility');
            $table->string('params')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_sections');
        Schema::dropIfExists('invoice_structures');
        Schema::dropIfExists('invoice_types');
        Schema::dropIfExists('invoices');
    }
};
