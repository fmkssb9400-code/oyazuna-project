<?php

namespace App\Filament\Widgets;

use App\Http\Controllers\AreaController;
use App\Http\Controllers\HubController;
use App\Services\GoogleAnalyticsService;
use Carbon\Carbon;
use Filament\Widgets\Widget;

class HubPageViewsWidget extends Widget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    protected static string $view = 'filament.widgets.hub-page-views-widget';

    /**
     * GA4のページビュー一覧をスラッグ（またはキー）単位で合算する。
     * GA4はページネーション付きパス（?page=2等）を別行で返すため、ここで合算する。
     *
     * @param array<int, array{path: string, views: int}> $topPages
     * @param callable(string): (string|null) $keyResolver パスからキーを作る。対象外ならnullを返す
     * @return array<string, int>
     */
    private function aggregateByKey(array $topPages, callable $keyResolver): array
    {
        $aggregated = [];
        foreach ($topPages as $page) {
            $path = parse_url($page['path'], PHP_URL_PATH) ?? $page['path'];
            $key = $keyResolver($path);

            if ($key === null) {
                continue;
            }

            $aggregated[$key] = ($aggregated[$key] ?? 0) + $page['views'];
        }

        return $aggregated;
    }

    /**
     * カテゴリハブ（/hub/*）の本日・今月累計のPVランキング。
     * ランキング順は今月累計を基準にし、本日分は参考値として併記する。
     */
    public function getCategoryRows(): array
    {
        $ga = app(GoogleAnalyticsService::class);
        $labels = app(HubController::class)->pages();

        $latestDataDate = $ga->getLatestDataDate() ?? Carbon::today('Asia/Tokyo');

        $keyResolver = function (string $path) use ($labels): ?string {
            $slug = trim(str_replace('/hub/', '', $path), '/');

            return ($slug === '' || ! isset($labels[$slug])) ? null : $slug;
        };

        $monthTotals = $this->aggregateByKey(
            $ga->getTopPagesForPathPrefix('/hub/', $latestDataDate->copy()->startOfMonth(), $latestDataDate, 200),
            $keyResolver
        );
        $todayTotals = $this->aggregateByKey(
            $ga->getTopPagesForPathPrefix('/hub/', $latestDataDate, $latestDataDate, 200),
            $keyResolver
        );

        arsort($monthTotals);

        $rows = [];
        foreach (array_slice($monthTotals, 0, 10, true) as $slug => $monthViews) {
            $rows[] = [
                'label' => $labels[$slug]['label'] ?? $slug,
                'slug' => $slug,
                'today_views' => $todayTotals[$slug] ?? 0,
                'month_views' => $monthViews,
            ];
        }

        return $rows;
    }

    /**
     * エリア×ハブ（/area/{県}/{サービス}）の本日・今月累計のPVランキング。
     * 都道府県トップページ単体（/area/{県}）はここでは除外する。
     */
    public function getAreaHubRows(): array
    {
        $ga = app(GoogleAnalyticsService::class);
        $areaLabels = app(AreaController::class)->pages();
        $hubLabels = app(HubController::class)->pages();

        $latestDataDate = $ga->getLatestDataDate() ?? Carbon::today('Asia/Tokyo');

        $keyResolver = function (string $path) use ($areaLabels, $hubLabels): ?string {
            $segments = explode('/', trim($path, '/'));

            if (count($segments) !== 3) {
                return null;
            }

            [, $areaSlug, $hubSlug] = $segments;

            if (! isset($areaLabels[$areaSlug]) || ! isset($hubLabels[$hubSlug])) {
                return null;
            }

            return $areaSlug . '/' . $hubSlug;
        };

        $monthTotals = $this->aggregateByKey(
            $ga->getTopPagesForPathPrefix('/area/', $latestDataDate->copy()->startOfMonth(), $latestDataDate, 500),
            $keyResolver
        );
        $todayTotals = $this->aggregateByKey(
            $ga->getTopPagesForPathPrefix('/area/', $latestDataDate, $latestDataDate, 500),
            $keyResolver
        );

        arsort($monthTotals);

        $rows = [];
        foreach (array_slice($monthTotals, 0, 10, true) as $key => $monthViews) {
            [$areaSlug, $hubSlug] = explode('/', $key);

            $rows[] = [
                'label' => ($areaLabels[$areaSlug]['label'] ?? $areaSlug) . '×' . ($hubLabels[$hubSlug]['label'] ?? $hubSlug),
                'area_slug' => $areaSlug,
                'hub_slug' => $hubSlug,
                'today_views' => $todayTotals[$key] ?? 0,
                'month_views' => $monthViews,
            ];
        }

        return $rows;
    }

    public function isReady(): bool
    {
        return app(GoogleAnalyticsService::class)->isConfigured();
    }
}
