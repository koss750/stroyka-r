<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('document_type', 12);
            $table->unsignedBigInteger('associated_user')->nullable();
            $table->unsignedBigInteger('associated_design')->nullable();
            $table->unsignedBigInteger('associated_order')->nullable();
            $table->unsignedBigInteger('associated_supplier')->nullable();
            $table->string('subtype', 30)->nullable();
            $table->string('classification', 30)->nullable();
            $table->date('expiry_date')->nullable();
            $table->longText('access_override')->nullable();
            $table->longText('parameters')->nullable();
            $table->string('name'); // Document name
            $table->string('path'); // File storage path
            $table->timestamps(); // Created at and updated at timestamps

            // Optional: Indexes for faster search
            $table->index(['document_type', 'name']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
}
