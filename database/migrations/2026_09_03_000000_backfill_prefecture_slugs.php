<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * add_slug_to_prefectures_table（2026_02_25）のUPDATE文は、当時prefecturesテーブルが
 * 空の状態（migrate:fresh --seed 実行時、migrationがseederより先に走るため）で実行されており、
 * 実際には0件しか更新されず、その後PrefectureSeederがslugなしで47件を作成していた。
 * そのため`?prefecture=tokyo`等のエリア絞り込みが常にサイレントに無効化されていた。
 * このマイグレーションは、既に存在する行のslugが未設定の場合のみ、正しい値で埋め直す。
 */
return new class extends Migration
{
    public function up(): void
    {
        $prefectures = [
            '北海道' => 'hokkaido', '青森県' => 'aomori', '岩手県' => 'iwate', '宮城県' => 'miyagi',
            '秋田県' => 'akita', '山形県' => 'yamagata', '福島県' => 'fukushima', '茨城県' => 'ibaraki',
            '栃木県' => 'tochigi', '群馬県' => 'gunma', '埼玉県' => 'saitama', '千葉県' => 'chiba',
            '東京都' => 'tokyo', '神奈川県' => 'kanagawa', '新潟県' => 'niigata', '富山県' => 'toyama',
            '石川県' => 'ishikawa', '福井県' => 'fukui', '山梨県' => 'yamanashi', '長野県' => 'nagano',
            '岐阜県' => 'gifu', '静岡県' => 'shizuoka', '愛知県' => 'aichi', '三重県' => 'mie',
            '滋賀県' => 'shiga', '京都府' => 'kyoto', '大阪府' => 'osaka', '兵庫県' => 'hyogo',
            '奈良県' => 'nara', '和歌山県' => 'wakayama', '鳥取県' => 'tottori', '島根県' => 'shimane',
            '岡山県' => 'okayama', '広島県' => 'hiroshima', '山口県' => 'yamaguchi', '徳島県' => 'tokushima',
            '香川県' => 'kagawa', '愛媛県' => 'ehime', '高知県' => 'kochi', '福岡県' => 'fukuoka',
            '佐賀県' => 'saga', '長崎県' => 'nagasaki', '熊本県' => 'kumamoto', '大分県' => 'oita',
            '宮崎県' => 'miyazaki', '鹿児島県' => 'kagoshima', '沖縄県' => 'okinawa',
        ];

        foreach ($prefectures as $name => $slug) {
            DB::table('prefectures')
                ->where('name', $name)
                ->where(function ($query) {
                    $query->whereNull('slug')->orWhere('slug', '');
                })
                ->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        // slugを空に戻すと絞り込み機能が再度壊れるため、意図的に何もしない。
    }
};
