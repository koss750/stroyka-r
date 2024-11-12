<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DesignMaterial;

class DesignMaterialSeeder extends Seeder
{
    public function run()
    {
        // Define materials with sizes
        $materials = [
            'CBC' => ['108х2500', '108х2000', '500x600', /* more sizes... */],
            'ЖБ' => ['150х150х3000', '200х200х3000'],
            'Бревно' => ['300x600', '300x700', '300x800', '300x900', '300x1000', '400x600', '400x700', '400x800', '400x900', '400x1000',
        '500x700', '500x800', '500x900', '500x1000',
        '600x700', '600x800', '600x900', '600x1000'],
            'ОЦБ Сосна/Ель' => [200, 220, 240, 260, 280, 300],
            'ОЦБ Лиственница' => [200, 220, 240, 260, 280, 300],
            'ОЦБ Кедр' => [200, 220, 240, 260, 280, 300],
            'Бревно ручной рубки' => [200, 220, 240, 260, 280, 300, 320, 340, 360, 400],
            'Проф.бруст ест.влажность' => ['145x140', '145x190', '190x190'],
            'Проф бруст камерной сушки' => ['140x145', '145x195', '195x195'],
            'Клееный брус' => [
                '145x160', '145x200', '190x160', '190x200', '145x240',
                '190x240', '230x160', '230x200', '230x240', '250x160',
                '250x200', '280x200',
            ],
            'Кровля' => ['под руб', 'металлочерепица', 'мягенько']
        ];

        // Insert materials into the database
        foreach ($materials as $name => $sizes) {
            foreach ($sizes as $size) {
                DesignMaterial::create([
                    'title' => $name,
                    'subsection' => $size,
                    'description' => "$name $size"
                    // You may have additional fields to set, such as 'type' or 'price'
                ]);
            }
        }
    }
}