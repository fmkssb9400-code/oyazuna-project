<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\StaticPage;
use App\Services\RecommendedItemsService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class NewsController extends Controller
{
    public function index()
    {
        // 通常記事を取得
        $articles = Article::published()
            ->whereNull('company_id')
            ->orderBy('published_at', 'desc')
            ->get();

        // 公開された固定記事を取得
        $staticPages = StaticPage::published()
            ->orderBy('published_at', 'desc')
            ->get();

        // 両方を統合して日付順にソート
        $allItems = collect([]);
        
        // 記事を追加（typeフィールドを付与）
        foreach ($articles as $article) {
            $allItems->push((object)[
                'id' => $article->id,
                'title' => $article->title,
                'slug' => $article->slug,
                'published_at' => $article->published_at,
                'featured_image_url' => $article->featured_image_url,
                'type' => 'article',
                'model' => $article
            ]);
        }
        
        // 固定記事を追加
        foreach ($staticPages as $page) {
            $allItems->push((object)[
                'id' => $page->id,
                'title' => $page->title,
                'slug' => $page->slug,
                'published_at' => $page->published_at,
                'featured_image_url' => $page->featured_image ? \Storage::disk('public')->url($page->featured_image) : null,
                'type' => 'static_page',
                'page_type' => $page->page_type,
                'model' => $page
            ]);
        }

        // 公開日時順でソート（降順）
        $allItems = $allItems->sortByDesc('published_at');

        // ページネーション用に分割
        $perPage = 12;
        $currentPage = request()->get('page', 1);
        $currentItems = $allItems->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedItems = new LengthAwarePaginator(
            $currentItems,
            $allItems->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        );

        return view('news.index', compact('paginatedItems'));
    }

    public function show(Article $article)
    {
        // Ensure article is published and not a company-specific article
        if (!$article->is_published || $article->company_id !== null) {
            abort(404);
        }

        // Get featured articles and static pages (excluding current article)
        $recommendedItemsService = new RecommendedItemsService();
        $allFeaturedItems = $recommendedItemsService->getRecommendedItems(10);
        
        // Filter out current article if it's in the list
        $featuredArticles = $allFeaturedItems->reject(function ($item) use ($article) {
            return $item['type'] === 'article' && $item['id'] === $article->id;
        })->take(5);

        return view('news.show', compact('article', 'featuredArticles'));
    }
}
