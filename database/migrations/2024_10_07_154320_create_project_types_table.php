<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ProjectType;

class CreateProjectTypesTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('project_types');
        Schema::create('project_types', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category');
            $table->string('size')->nullable();
            $table->string('subcat')->nullable();
            $table->decimal('price', 8, 2);
            $table->timestamps();
        });

        // Seeding logic
        $categories = ['smeta-normal', 'smeta-foundation'];
        $sizes = [0, 50, 100, 150, 200, 250, 300, 1000];

        foreach ($categories as $category) {
            foreach ($sizes as $size) {
                $price = $this->calculatePrice($size);
                
                ProjectType::create([
                    'title' => "$category - $size",
                    'category' => $category,
                    'size' => (string)$size,
                    'price' => $price,
                ]);
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists('project_types');
    }

    private function calculatePrice($size)
    {
        // Calculate price based on size
        // Prices range from 200 to 2000 RUB
        $minPrice = 200;
        $maxPrice = 2000;
        $maxSize = 1000;

        $price = $minPrice + (($size / $maxSize) * ($maxPrice - $minPrice));
        return round($price, 2);
    }
}