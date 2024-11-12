<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('nova_pending_field_attachments', function (Blueprint $table) {
            $table->string('original_name')->nullable()->after('attachment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nova_pending_field_attachments', function (Blueprint $table) {
            $table->dropColumn('original_name');
        });
    }
};
