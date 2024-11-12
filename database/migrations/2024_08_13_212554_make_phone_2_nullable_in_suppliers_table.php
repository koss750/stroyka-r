<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('phone_2')->nullable()->change();
            $table->integer('user_id')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // First, replace NULL values with an empty string
        DB::table('suppliers')->whereNull('phone_2')->update(['phone_2' => '']);

        // Then, drop the column
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('phone_2')->nullable(false)->change();
        });
        
        $table->dropColumn('user_id');
    }
};
