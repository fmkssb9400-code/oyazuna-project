<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            ['key' => 'rope', 'label' => 'ロープアクセス'],
            ['key' => 'gondola', 'label' => 'ゴンドラ'],
            ['key' => 'aerial', 'label' => '高所作業車'],
            ['key' => 'scaffold', 'label' => '足場'],
        ];

        foreach ($methods as $method) {
            \App\Models\ServiceMethod::create($method);
        }
    }
}
