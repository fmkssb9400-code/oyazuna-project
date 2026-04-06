<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $companyName = fake()->company();
        $slug = \Illuminate\Support\Str::slug($companyName) . '-' . fake()->numberBetween(1, 999);
        
        return [
            'name' => $companyName,
            'slug' => $slug,
            'description' => fake()->realText(300),
            'official_url' => fake()->url(),
            'areas' => fake()->randomElements([
                '東京都', '神奈川県', '埼玉県', '千葉県', '大阪府', '愛知県', '兵庫県', '福岡県', '北海道', '静岡県'
            ], fake()->numberBetween(2, 5)),
            'rope_support' => fake()->boolean(80),
            'gondola_supported' => fake()->boolean(60),
            'max_floor' => fake()->numberBetween(10, 100),
            'emergency_supported' => fake()->boolean(40),
            'achievements_summary' => fake()->sentence(),
            'safety_items' => fake()->randomElements([
                '賠償責任保険加入',
                '労災保険加入',
                '建設業許可',
                '有資格者在籍',
                '高所作業特別教育修了者',
                '安全管理責任者',
                'フルハーネス型墜落制止用器具',
                '足場の組立て等作業主任者',
                'ロープ高所作業特別教育',
                'ゴンドラ特別教育'
            ], fake()->numberBetween(3, 7)),
            'strength_tags' => fake()->randomElements([
                'ゴンドラ対応',
                '夜間対応可',
                '土日対応可',
                '高層ビル対応可',
                '商業施設対応可',
                '医療施設対応可',
                '緊急対応可',
                '24時間対応',
                'ISO取得',
                '環境配慮',
                'アフターサービス充実'
            ], fake()->numberBetween(4, 8)),
            'is_featured' => fake()->boolean(20),
            'sort_order' => fake()->numberBetween(0, 100),
            'service_areas' => fake()->sentence(),
            'security_points' => fake()->randomElements([
                '賠償責任保険加入',
                '労災保険加入',
                '建設業許可',
                '有資格者在籍',
                '高所作業特別教育修了者'
            ], fake()->numberBetween(2, 5)),
            'performance_summary' => fake()->sentence(),
            'recommend_score' => fake()->numberBetween(60, 100),
            'safety_score' => fake()->numberBetween(60, 100),
            'performance_score' => fake()->numberBetween(60, 100),
            'review_score' => fake()->randomFloat(1, 3.0, 5.0),
            'review_count' => fake()->numberBetween(5, 200),
            'email_quote' => fake()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'address_text' => fake()->address(),
            'insurance' => fake()->boolean(90),
            'price_note' => fake()->sentence(),
            'rank_score' => fake()->numberBetween(60, 100),
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
