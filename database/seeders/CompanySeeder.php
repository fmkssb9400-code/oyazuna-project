<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Review;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample companies with new card UI fields
        $featuredCompanies = [
            [
                'name' => '東京ロープアクセス株式会社',
                'slug' => 'tokyo-rope-access',
                'description' => '都内最大級のロープアクセス専門業者です。高層ビルの窓ガラス清掃を得意とし、50階建てまで対応可能。安全第一をモットーに、豊富な実績を持つプロフェッショナル集団です。',
                'official_url' => 'https://example.com/tokyo-rope',
                'areas' => ['東京都', '神奈川県', '埼玉県'],
                'rope_support' => true,
                'gondola_supported' => true,
                'max_floor' => 50,
                'emergency_supported' => true,
                'achievements_summary' => '大手企業ビル、商業施設など1000件以上の施工実績',
                'safety_items' => [
                    '賠償責任保険加入',
                    '労災保険加入',
                    '建設業許可',
                    '有資格者在籍',
                    '高所作業特別教育修了者',
                    '安全管理責任者'
                ],
                'strength_tags' => [
                    'ゴンドラ対応',
                    '緊急対応可',
                    '土日対応可',
                    '高層ビル対応可',
                    '商業施設対応可'
                ],
                'is_featured' => true,
                'sort_order' => 1,
                'email_quote' => 'info@tokyo-rope.example.com',
                'phone' => '03-1234-5678',
                'published_at' => now(),
            ],
            [
                'name' => '株式会社スカイクリーン',
                'slug' => 'sky-clean',
                'description' => '関西エリア最大のロープアクセス清掃業者です。ビル、マンション、商業施設の外壁・窓ガラス清掃のエキスパート。品質と安全性を追求した作業で多くのお客様から信頼を得ています。',
                'official_url' => 'https://example.com/sky-clean',
                'areas' => ['大阪府', '兵庫県', '京都府', '奈良県'],
                'rope_support' => true,
                'gondola_supported' => false,
                'max_floor' => 35,
                'emergency_supported' => false,
                'achievements_summary' => '関西圏で30年の歴史、年間500件の清掃実績',
                'safety_items' => [
                    '賠償責任保険加入',
                    '労災保険加入',
                    'ロープ高所作業特別教育',
                    'フルハーネス型墜落制止用器具',
                    '足場の組立て等作業主任者'
                ],
                'strength_tags' => [
                    '夜間対応可',
                    '土日対応可',
                    '高層ビル対応可',
                    '商業施設対応可',
                    'アフターサービス充実'
                ],
                'is_featured' => true,
                'sort_order' => 2,
                'email_quote' => 'info@sky-clean.example.com',
                'phone' => '06-1234-5678',
                'published_at' => now(),
            ],
            [
                'name' => '全国ビルメンテナンス株式会社',
                'slug' => 'nationwide-building',
                'description' => '全国47都道府県に対応する総合ビルメンテナンス企業。ロープアクセス、ゴンドラ、高所作業車など、あらゆる工法に対応し、どんな高さの建物でも清掃可能です。24時間365日の緊急対応も承ります。',
                'official_url' => 'https://example.com/nationwide',
                'areas' => ['全国対応'],
                'rope_support' => true,
                'gondola_supported' => true,
                'max_floor' => 100,
                'emergency_supported' => true,
                'achievements_summary' => '全国で年間2000件以上、超高層ビル清掃のリーディングカンパニー',
                'safety_items' => [
                    '賠償責任保険加入',
                    '労災保険加入',
                    '建設業許可',
                    '有資格者在籍',
                    '高所作業特別教育修了者',
                    '安全管理責任者',
                    'ISO9001取得',
                    'ISO14001取得'
                ],
                'strength_tags' => [
                    'ゴンドラ対応',
                    '24時間対応',
                    '緊急対応可',
                    '土日対応可',
                    '高層ビル対応可',
                    '商業施設対応可',
                    '医療施設対応可',
                    'ISO取得'
                ],
                'is_featured' => true,
                'sort_order' => 3,
                'email_quote' => 'info@nationwide.example.com',
                'phone' => '0120-123-456',
                'published_at' => now(),
            ]
        ];

        foreach ($featuredCompanies as $data) {
            $company = Company::create($data);
            
            // Create reviews for each company
            Review::factory(rand(5, 15))->create([
                'company_id' => $company->id
            ]);
        }

        // Create additional non-featured companies
        Company::factory(15)->create()->each(function ($company) {
            // Create reviews for each company
            Review::factory(rand(2, 20))->create([
                'company_id' => $company->id
            ]);
        });
    }
}
