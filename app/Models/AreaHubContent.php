<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaHubContent extends Model
{
    protected $fillable = [
        'area_slug',
        'hub_slug',
        'title',
        'meta_description',
        'content',
    ];

    // 本文中の「<h3>Q. ...</h3><p>A. ...</p>」形式のFAQを抽出（Article::getFaqPairsAttribute()と同じ形式）。
    // これにより、都道府県固有のFAQを管理画面の本文編集からそのまま追加できる。
    public function getFaqPairsAttribute(): array
    {
        if (empty($this->content)) {
            return [];
        }

        preg_match_all(
            '/<h[3-4][^>]*>\s*Q[\.\s、:：]?\s*(.*?)<\/h[3-4]>\s*<p[^>]*>\s*A[\.\s、:：]?\s*(.*?)<\/p>/is',
            $this->content,
            $matches,
            PREG_SET_ORDER
        );

        $pairs = [];
        foreach ($matches as $match) {
            $question = trim(strip_tags($match[1]));
            $answer = trim(strip_tags($match[2]));
            if ($question !== '' && $answer !== '') {
                $pairs[] = ['q' => $question, 'a' => $answer];
            }
        }

        return $pairs;
    }
}
