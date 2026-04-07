<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // JSONファイルからデータを読み込み
        $jsonPath = 'oyazuna_data.json';
        
        if (!Storage::disk('public')->exists($jsonPath)) {
            $this->command->error("JSONファイルが見つかりません: storage/app/public/{$jsonPath}");
            return;
        }

        $jsonContent = Storage::disk('public')->get($jsonPath);
        $companies = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('JSONファイルの形式が正しくありません: ' . json_last_error_msg());
            return;
        }

        $this->command->info('JSONファイルから ' . count($companies) . ' 件のデータを読み込みました。');

        foreach ($companies as $index => $companyData) {
            try {
                // 必須フィールドのチェック
                if (empty($companyData['name']) || empty($companyData['slug'])) {
                    $this->command->warn("会社データ #{$index}: name または slug が不足しています。スキップします。");
                    continue;
                }

                // JSONカラムの処理
                $jsonFields = ['areas', 'safety_items', 'service_categories', 'tags', 'area_regions'];
                foreach ($jsonFields as $field) {
                    if (isset($companyData[$field]) && is_array($companyData[$field])) {
                        $companyData[$field] = json_encode($companyData[$field]);
                    }
                }

                // Boolean値の正規化
                $booleanFields = [
                    'rope_support', 'gondola_supported', 'branco_supported', 
                    'aerial_platform_supported', 'emergency_supported', 'insurance', 'is_featured'
                ];
                foreach ($booleanFields as $field) {
                    if (isset($companyData[$field])) {
                        $companyData[$field] = (bool)$companyData[$field];
                    } else {
                        $companyData[$field] = false;
                    }
                }

                // Integer値の正規化
                $integerFields = [
                    'max_floor', 'recommend_score', 'safety_score', 
                    'performance_score', 'review_count', 'sort_order', 'rank_score'
                ];
                foreach ($integerFields as $field) {
                    if (isset($companyData[$field])) {
                        $companyData[$field] = (int)$companyData[$field];
                    } else {
                        $companyData[$field] = 0;
                    }
                }

                // Decimal値の正規化 (review_score)
                if (isset($companyData['review_score'])) {
                    $companyData['review_score'] = (float)$companyData['review_score'];
                } else {
                    $companyData['review_score'] = 0.0;
                }

                // デフォルト値設定
                $defaults = [
                    'published_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $companyData = array_merge($defaults, $companyData);

                // updateOrInsertでslugベースの更新対応
                DB::table('companies')->updateOrInsert(
                    ['slug' => $companyData['slug']],
                    $companyData
                );

                $this->command->info("会社データを登録/更新しました: {$companyData['name']} (slug: {$companyData['slug']})");

            } catch (\Exception $e) {
                $this->command->error("会社データ #{$index} の処理中にエラーが発生しました: " . $e->getMessage());
                continue;
            }
        }

        $this->command->info('CompanySeederの実行が完了しました。');
    }
}
