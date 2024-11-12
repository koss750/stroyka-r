<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Design;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('design_seos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('design_id')->unique()->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('keywords')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('alt_description')->nullable();
            $table->string('alt_title')->nullable();
            $table->string('alt_image')->nullable();
            $table->json('additional_meta')->nullable();
            $table->timestamps();
        });

        // Seed the table with empty records for existing designs
        $this->seedDesignSeos();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('design_seos');
    }

    private function seedDesignSeos()
    {
        $designs = Design::all();

        foreach ($designs as $design) {
            DB::table('design_seos')->insert([
                'design_id' => $design->id,
                'title' => $design->title,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
};