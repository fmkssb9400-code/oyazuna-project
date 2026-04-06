<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Delete all existing service categories
        \App\Models\ServiceCategory::truncate();
        
        // Insert new service categories
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore original service categories
        \App\Models\ServiceCategory::truncate();
        
        $originalCategories = [
            ['key' => 'window', 'label' => '窓ガラス清掃'],
            ['key' => 'wall', 'label' => '外壁清掃'],
            ['key' => 'sign', 'label' => '看板清掃'],
            ['key' => 'other', 'label' => 'その他'],
        ];

        foreach ($originalCategories as $category) {
            \App\Models\ServiceCategory::create($category);
        }
    }
};
