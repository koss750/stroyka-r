<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSuppliersTable extends Migration
{
    public function up()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('inn')->after('company_name'); // Add ИНН field
            $table->string('phone_1')->after('email'); // Add phone 1 field
            $table->string('phone_2')->after('phone_1'); // Add phone 2 field
            $table->text('message')->nullable()->after('phone_2'); // Add message field

            // Remove fields no longer needed
            $table->dropColumn(['trading_name', 'contact_telephone', 'latitude', 'longitude']);
        });
    }

    public function down()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            // Code to revert the changes
            $table->string('trading_name')->after('company_name');
            $table->string('contact_telephone')->after('address');
            $table->double('latitude')->after('email');
            $table->double('longitude')->after('latitude');

            $table->dropColumn(['inn', 'phone_1', 'phone_2', 'message']);
        });
    }
}