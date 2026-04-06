<?php

namespace App\Services;

use App\Models\Article;
use App\Models\StaticPage;
use Illuminate\Support\Collection;

class RecommendedItemsService
{
    /**
     * おすすめ記事と固定ページを統合して取得
     */
    public function getRecommendedItems(int $limit = 8): Collection
    {
        // 通常記事を取得（featured且つcompany_idがnull）
        $articles = Article::published()
            ->whereNull('company_id')
            ->featured()
            ->orderBy('published_at', 'desc')
            ->get();

        // 公開された固定記事を取得
        $staticPages = StaticPage::published()
            ->orderBy('published_at', 'desc')
            ->get();

        // 両方を統合して適切な形式に変換
        $allItems = collect([]);
        
        // 記事を追加
        foreach ($articles as $article) {
            $allItems->push([
                'id' => $article->id,
                'title' => $article->title,
                'url' => $article->slug ? route('news.show', $article->slug) : '#',
                'summary' => $this->extractSummary($article->content),
                'published_at' => $article->published_at,
                'type' => 'article',
                'featured_image_url' => $article->featured_image_url
            ]);
        }
        
        // 固定記事を追加（page_typeに応じてURLを設定）
        foreach ($staticPages as $page) {
            $guideRoute = match($page->page_type) {
                'window-cleaning-price-guide' => 'guide.window-cleaning-price',
                'window-cleaning-contractor-guide' => 'guide.window-cleaning-contractor-selection',
                'exterior-wall-painting-price-guide' => 'guide.exterior-wall-painting-pricing',
                'exterior-wall-painting-contractor-guide' => 'guide.exterior-wall-painting-contractor-selection',
                default => null
            };
            
            if ($guideRoute) {
                $allItems->push([
                    'id' => 'static_' . $page->id,
                    'title' => $page->title,
                    'url' => route($guideRoute),
                    'summary' => $this->extractSummary($page->content),
                    'published_at' => $page->published_at,
                    'type' => 'static_page',
                    'featured_image_url' => $page->featured_image ? \Storage::disk('public')->url($page->featured_image) : null
                ]);
            }
        }

        // 公開日時順でソートして制限
        return $allItems
            ->sortByDesc('published_at')
            ->take($limit)
            ->values();
    }

    /**
     * コンテンツからサマリーを抽出
     */
    private function extractSummary(?string $content, int $length = 100): string
    {
        if (empty($content)) {
            return '';
        }

        // HTMLタグを除去
        $text = strip_tags($content);
        
        // 改行や余分な空白を除去
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);
        
        // 指定された長さに切り詰め
        if (mb_strlen($text) > $length) {
            $text = mb_substr($text, 0, $length) . '...';
        }
        
        return $text;
    }
}