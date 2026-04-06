<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['key' => 'window', 'label' => '窓ガラス清掃'],
            ['key' => 'inspection', 'label' => '外壁調査'],
            ['key' => 'repair', 'label' => '外壁補修'],
            ['key' => 'painting', 'label' => '外壁塗装（部分）'],
            ['key' => 'bird_control', 'label' => '鳥害対策'],
            ['key' => 'sign', 'label' => '看板作業'],
            ['key' => 'leak_inspection', 'label' => '雨漏り調査'],
            ['key' => 'other', 'label' => 'その他'],
        ];

        foreach ($categories as $category) {
            \App\Models\ServiceCategory::create($category);
        }
    }
}
