<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DynamicPageCard;

class DynamicPageCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DynamicPageCard::create([
            'type' => 'foundation',
            'title' => 'Фунтамент такой',
            'link' => 'https://tilda.cc/page/preview/?pageid=44379807&lang=RU',
            'image_url' => 'images/tild3863-3634-4331-a534-393764313039__1-35.jpg',
            'dimensions' => '100%',
            'description' => 'Фундамент'
        ]);
        DynamicPageCard::create([
            'type' => 'home',
            'title' => 'Бани такие',
            'link' => 'https://tilda.cc/page/preview/?pageid=44344923&lang=RU',
            'image_url' => 'images/tild3466-3637-4734-a632-393138386461__photo_2023-07-01-172.jpg',
            'description' => 'Смотреть наши проекты домов и бань',
            'dimensions' => '100%'
        ]);
        // Add other cards here...
    }
}
