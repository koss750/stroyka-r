<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameParamsToPropertyAndAddDetailsToInvoicetypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_structures', function (Blueprint $table) {
            
            $table->text('sheetname')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_structures', function (Blueprint $table) {
            
            // Drop the details column
            $table->dropColumn('sheetname');
        });
    }
}
