<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Create the settings table
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('label')->unique();
            $table->string('title');
            $table->text('value');
            $table->string('affected_users')->default('all');
            $table->string('affected_areas')->default('site');
            $table->timestamps();
        });

        // Seed initial data for display_prices
        DB::table('settings')->insert([
            'label' => 'display_prices',
            'title' => 'Отображать цены',
            'value' => 'total',
            'affected_users' => 'all',
            'affected_areas' => 'site',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
