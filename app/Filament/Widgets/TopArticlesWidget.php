<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Article;
use App\Services\GoogleAnalyticsService;
use Carbon\Carbon;

class TopArticlesWidget extends Widget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
    protected static string $view = 'filament.widgets.top-articles-widget';

    public function getRows(): array
    {
        $ga = app(GoogleAnalyticsService::class);

        $now = Carbon::now('Asia/Tokyo');
        $topPages = $ga->getTopPagesForPathPrefix('/news/', $now->copy()->startOfMonth(), $now, 10);

        $slugs = array_map(
            fn (array $page) => trim(str_replace('/news/', '', $page['path']), '/'),
            $topPages
        );

        $articles = Article::whereIn('slug', $slugs)
            ->published()
            ->get()
            ->keyBy('slug');

        $rows = [];
        foreach ($topPages as $page) {
            $slug = trim(str_replace('/news/', '', $page['path']), '/');
            $article = $articles->get($slug);

            if (! $article) {
                continue;
            }

            $rows[] = [
                'article' => $article,
                'views' => $page['views'],
            ];
        }

        return $rows;
    }

    public function isReady(): bool
    {
        return app(GoogleAnalyticsService::class)->isConfigured();
    }

    /**
     * このウィジェットが表示しているGA4データを実際に取得した時刻。
     * まだ一度もキャッシュされていない（今回が初回取得）場合は現在時刻で代替する。
     */
    public function getAsOfLabel(): string
    {
        $ga = app(GoogleAnalyticsService::class);
        $now = Carbon::now('Asia/Tokyo');

        $fetchedAt = $ga->getTopPagesFetchedAt('/news/', $now->copy()->startOfMonth(), $now, 10);

        return ($fetchedAt ?? $now)->format('n月j日 H:i時点');
    }
}
