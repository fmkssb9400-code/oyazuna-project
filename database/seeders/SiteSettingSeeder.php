<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'site_logo',
                'value' => null,
                'type' => 'image',
                'label' => 'サイトロゴ',
                'description' => 'ヘッダーに表示されるサイトロゴ画像',
            ],
            [
                'key' => 'site_name',
                'value' => 'オヤズナ',
                'type' => 'text',
                'label' => 'サイト名',
                'description' => 'ヘッダーに表示されるサイト名（ロゴがない場合のテキスト）',
            ],
            [
                'key' => 'hero_image',
                'value' => null,
                'type' => 'image',
                'label' => 'ヒーロー画像',
                'description' => 'トップページのヘッダー下に表示される背景画像',
            ],
            [
                'key' => 'hero_title',
                'value' => '高所窓ガラス清掃業者を',
                'type' => 'text',
                'label' => 'ヒーローメインタイトル',
                'description' => 'トップページのメインタイトル',
            ],
            [
                'key' => 'hero_subtitle',
                'value' => 'まとめて比較・一括見積もり',
                'type' => 'text',
                'label' => 'ヒーローサブタイトル',
                'description' => 'トップページのサブタイトル',
            ],
            [
                'key' => 'hero_description',
                'value' => 'ロープアクセス対応業者がすぐ見つかる！',
                'type' => 'text',
                'label' => 'ヒーロー説明文',
                'description' => 'トップページの説明テキスト',
            ],
        ];

        foreach ($settings as $setting) {
            $existing = \App\Models\SiteSetting::where('key', $setting['key'])->first();
            if (!$existing) {
                \App\Models\SiteSetting::create($setting);
            } else if (empty($existing->value) && !empty($setting['value'])) {
                $existing->update(['value' => $setting['value']]);
            }
        }
    }
}
