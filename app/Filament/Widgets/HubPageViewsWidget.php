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
     * カテゴリハブ（/hub/*）の今月のPVランキング。
     * GA4はページネーション付きパス（?page=2等）を別行で返すため、スラッグ単位で合算する。
     */
    public function getCategoryRows(): array
    {
        $ga = app(GoogleAnalyticsService::class);
        $labels = app(HubController::class)->pages();

        $now = Carbon::now('Asia/Tokyo');
        $topPages = $ga->getTopPagesForPathPrefix('/hub/', $now->copy()->startOfMonth(), $now, 200);

        $aggregated = [];
        foreach ($topPages as $page) {
            $path = parse_url($page['path'], PHP_URL_PATH) ?? $page['path'];
            $slug = trim(str_replace('/hub/', '', $path), '/');

            if ($slug === '' || ! isset($labels[$slug])) {
                continue;
            }

            $aggregated[$slug] = ($aggregated[$slug] ?? 0) + $page['views'];
        }

        arsort($aggregated);

        $rows = [];
        foreach (array_slice($aggregated, 0, 10, true) as $slug => $views) {
            $rows[] = [
                'label' => $labels[$slug]['label'] ?? $slug,
                'slug' => $slug,
                'views' => $views,
            ];
        }

        return $rows;
    }

    /**
     * エリア×ハブ（/area/{県}/{サービス}）の今月のPVランキング。
     * 都道府県トップページ単体（/area/{県}）はここでは除外する。
     */
    public function getAreaHubRows(): array
    {
        $ga = app(GoogleAnalyticsService::class);
        $areaLabels = app(AreaController::class)->pages();
        $hubLabels = app(HubController::class)->pages();

        $now = Carbon::now('Asia/Tokyo');
        $topPages = $ga->getTopPagesForPathPrefix('/area/', $now->copy()->startOfMonth(), $now, 500);

        $aggregated = [];
        foreach ($topPages as $page) {
            $path = parse_url($page['path'], PHP_URL_PATH) ?? $page['path'];
            $segments = explode('/', trim($path, '/'));

            if (count($segments) !== 3) {
                continue;
            }

            [, $areaSlug, $hubSlug] = $segments;

            if (! isset($areaLabels[$areaSlug]) || ! isset($hubLabels[$hubSlug])) {
                continue;
            }

            $key = $areaSlug . '/' . $hubSlug;
            $aggregated[$key] = ($aggregated[$key] ?? 0) + $page['views'];
        }

        arsort($aggregated);

        $rows = [];
        foreach (array_slice($aggregated, 0, 10, true) as $key => $views) {
            [$areaSlug, $hubSlug] = explode('/', $key);

            $rows[] = [
                'label' => ($areaLabels[$areaSlug]['label'] ?? $areaSlug) . '×' . ($hubLabels[$hubSlug]['label'] ?? $hubSlug),
                'area_slug' => $areaSlug,
                'hub_slug' => $hubSlug,
                'views' => $views,
            ];
        }

        return $rows;
    }

    public function isReady(): bool
    {
        return app(GoogleAnalyticsService::class)->isConfigured();
    }
}
