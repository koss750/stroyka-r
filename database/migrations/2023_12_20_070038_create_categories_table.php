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
        Schema::dropIfExists('categories');
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->timestamps();
        });

        // Seed the categories
        $categories = [
            "df_cat_1" => "Дома из профилированного бруса",
            "df_cat_2" => "Бани из клееного бруса",
            "df_cat_3" => "Дома из блоков",
            "df_cat_4" => "Дома из оцилиндрованного бревна",
            "df_cat_5" => "Бани из бруса камерной сушки",
            "df_cat_6" => "Бани из бруса сосна/ель",
            "df_cat_7" => "Бани из оцилиндрованного бревна",
            "df_cat_8" => "Дом-баня из бруса",
            "df_cat_9" => "Дома из бруса камерной сушки",
            "df_cat_10" => "Дома из клееного бруса",
            "df_cat_11" => "Бани с бассейном",
            "df_cat_12" => "Каркасные дома",
            "df_cat_13" => "Бани из бревна кедра",
            "df_cat_14" => "Бани из бревна лиственницы",
            "df_cat_15" => "Бани из бруса кедра",
            "df_cat_16" => "Бани из бруса лиственницы",
            "df_cat_17" => "Дачные дома",
            "df_cat_18" => "Дом-баня из бревна",
            "df_cat_19" => "Дома из бревна кедра",
            "df_cat_20" => "Дома из бревна лиственницы",
            "df_cat_21" => "Дома из бруса кедра",
            "df_cat_22" => "Дома из бруса лиственницы"
        ];

        foreach ($categories as $key => $value) {
            DB::table('categories')->insert([
                'code' => $key,
                'name' => $value
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
