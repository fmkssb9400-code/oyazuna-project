<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $positiveComments = [
            'とても丁寧な作業で満足しています。',
            'スタッフの対応が良く、安心してお任せできました。',
            '予定通りに作業が完了し、仕上がりも綺麗です。',
            '料金も明確で、追加費用なく作業していただけました。',
            '高所作業でしたが、安全に配慮して作業してくれました。',
            '清掃後の仕上がりが非常に良く、また依頼したいと思います。',
            'プロフェッショナルな仕事ぶりで信頼できます。',
        ];

        $neutralComments = [
            '作業自体は問題なく完了しましたが、連絡がもう少し欲しかったです。',
            '清掃は丁寧にしていただけましたが、時間が予定より長くかかりました。',
            '仕上がりは満足ですが、料金がもう少し安ければと思います。',
        ];

        $rating = fake()->numberBetween(1, 5);
        
        if ($rating >= 4) {
            $baseComment = fake()->randomElement($positiveComments);
        } else {
            $baseComment = fake()->randomElement($neutralComments);
        }

        return [
            'reviewer_name' => fake()->name(),
            'rating' => $rating,
            'body' => $baseComment . ' ' . fake()->optional(0.7)->realText(100),
            'status' => fake()->randomElement(['published', 'published', 'published', 'pending']), // 75% published
        ];
    }
}
