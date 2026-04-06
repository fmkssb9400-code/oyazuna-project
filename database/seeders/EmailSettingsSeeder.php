<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EmailSettings;

class EmailSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'quote_notification_email',
                'label' => '① 管理者通知メール（必須）',
                'value' => 'estimate@oyazuna.com',
                'description' => 'あなた（オヤズナ側）に届くメール。見積もり依頼が来たことを把握し、内容を確認してどの会社に流すか判断するためのメール。',
                'type' => 'email'
            ],
            [
                'key' => 'quote_from_email',
                'label' => '② 業者送信用メール（任意）',
                'value' => 'info@oyazuna.com',
                'description' => '実際に業者へ転送する送信元アドレス。あなたが内容を見てから手動 or 自動で業者に送る用。',
                'type' => 'email'
            ]
        ];

        foreach ($settings as $setting) {
            EmailSettings::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
