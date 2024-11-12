<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            // Add missing columns
            $table->string('ogrn', 191)->nullable()->after('inn');
            $table->string('kpp', 191)->nullable()->after('ogrn');
            $table->date('ogrn_date')->nullable()->after('kpp');
            $table->string('legal_address', 191)->nullable()->after('address');
            $table->string('physical_address', 191)->nullable()->after('legal_address');
            $table->string('contact_name', 191)->nullable()->after('phone_2');
            $table->string('state_status', 191)->nullable()->after('status');
            
            // Modify existing columns
            $table->renameColumn('phone_1', 'phone');
            $table->renameColumn('phone_2', 'additional_phone');
            
            // Ensure correct data types
            $table->string('type', 191)->change();
            $table->string('company_name', 191)->change();
            $table->string('inn', 191)->change();
            $table->text('address')->change();
            $table->string('email', 191)->change();
            $table->text('message')->nullable()->change();
            $table->string('status', 191)->nullable()->change();
            $table->string('type_of_organisation', 191)->nullable()->change();
            $table->string('region_code', 191)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            // Revert added columns
            $table->dropColumn(['ogrn', 'kpp', 'ogrn_date', 'legal_address', 'physical_address', 'contact_name', 'state_status']);
            
            // Revert renamed columns
            $table->renameColumn('phone', 'phone_1');
            $table->renameColumn('additional_phone', 'phone_2');
        });
    }
};