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
            ],
            [
                'key' => 'quote_from_name',
                'label' => '③ 送信者名',
                'value' => 'オヤズナ',
                'description' => 'メール送信時の送信者名として表示される名前',
                'type' => 'text'
            ],
            [
                'key' => 'quote_subject_template',
                'label' => '④ メール件名テンプレート',
                'value' => '【オヤズナ】新しい見積もり依頼が届きました',
                'description' => '業者に送信されるメールの件名',
                'type' => 'text'
            ],
            [
                'key' => 'quote_email_template',
                'label' => '⑤ メール本文テンプレート',
                'value' => "{{company_name}}様\n\nいつもお世話になっております。\nオヤズナより新しい見積もり依頼をお送りいたします。\n\n■依頼者情報\n会社名：{{client_company}}\nご担当者名：{{client_name}}\nメールアドレス：{{client_email}}\n電話番号：{{client_phone}}\n\n■建物情報\n都道府県：{{prefecture}}\n市区町村：{{city}}\n建物名：{{building_name}}\n建物種別：{{building_type}}\n階数：{{floors}}\n\n■依頼内容\nサービス内容：{{service_category}}\n希望時期：{{preferred_timing}}\n重視する点：{{priorities}}\n\n■備考\n{{note}}\n\nよろしくお願いいたします。",
                'description' => '業者に送信されるメール本文のテンプレート。{{変数名}}で動的な値を挿入できます。',
                'type' => 'textarea'
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
