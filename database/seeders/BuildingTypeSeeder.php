<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BuildingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['key' => 'office', 'label' => 'オフィスビル'],
            ['key' => 'mansion', 'label' => 'マンション'],
            ['key' => 'store', 'label' => '店舗'],
            ['key' => 'factory', 'label' => '工場'],
            ['key' => 'other', 'label' => 'その他'],
        ];

        foreach ($types as $type) {
            \App\Models\BuildingType::create($type);
        }
    }
}
