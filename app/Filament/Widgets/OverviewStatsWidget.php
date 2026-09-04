<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Article;
use App\Models\ConsultationSubmission;
use App\Models\Company;
use App\Services\GoogleAnalyticsService;
use Carbon\Carbon;

class OverviewStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $ga = app(GoogleAnalyticsService::class);

        $latestDataDate = $ga->getLatestDataDate() ?? Carbon::today();

        $thisMonth = $ga->getPageViews($latestDataDate->copy()->startOfMonth(), $latestDataDate);
        $lastMonth = $ga->getPageViews($latestDataDate->copy()->subMonthNoOverflow()->startOfMonth(), $latestDataDate->copy()->subMonthNoOverflow()->endOfMonth());
        $monthlyGrowth = $lastMonth > 0 ? (($thisMonth - $lastMonth) / $lastMonth) * 100 : 0;

        $today = $ga->getPageViews($latestDataDate, $latestDataDate);
        $yesterday = $ga->getPageViews($latestDataDate->copy()->subDay(), $latestDataDate->copy()->subDay());
        $dailyGrowth = $yesterday > 0 ? (($today - $yesterday) / $yesterday) * 100 : 0;

        $totalArticleViews = $ga->getPageViewsForPathPrefix('/news/', $latestDataDate->copy()->startOfMonth(), $latestDataDate);
        $articleCount = Article::published()->count();

        $consultationPageViews = $ga->getPageViewsForPathPrefix('/quote', $latestDataDate->copy()->startOfMonth(), $latestDataDate);
        $consultationSubmissions = ConsultationSubmission::thisMonth()->count();
        $consultationConversionRate = $consultationPageViews > 0 ? ($consultationSubmissions / $consultationPageViews) * 100 : 0;

        // Work method statistics
        $ropeCompanies = Company::where('rope_support', true)->published()->count();
        $brancoCompanies = Company::where('branco_supported', true)->published()->count();
        $aerialCompanies = Company::where('aerial_platform_supported', true)->published()->count();
        $gondolaCompanies = Company::where('gondola_supported', true)->published()->count();

        $asOf = '（' . $latestDataDate->format('n/j') . '時点）';

        return [
            Stat::make('今月のPV数', number_format($thisMonth) . ' ' . $asOf)
                ->description($monthlyGrowth >= 0 ? '+' . number_format($monthlyGrowth, 1) . '% 先月比' : number_format($monthlyGrowth, 1) . '% 先月比')
                ->descriptionIcon($monthlyGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($monthlyGrowth >= 0 ? 'success' : 'danger'),

            Stat::make('今日のPV数', number_format($today) . ' ' . $asOf)
                ->description($dailyGrowth >= 0 ? '+' . number_format($dailyGrowth, 1) . '% 昨日比' : number_format($dailyGrowth, 1) . '% 昨日比')
                ->descriptionIcon($dailyGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($dailyGrowth >= 0 ? 'success' : 'danger'),

            Stat::make('記事の今月PV数', number_format($totalArticleViews) . ' ' . $asOf)
                ->description('記事数: ' . number_format($articleCount) . '件')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),

            Stat::make('相談フォーム送信数', number_format($consultationSubmissions))
                ->description('今月のCV率: ' . number_format($consultationConversionRate, 1) . '%')
                ->descriptionIcon('heroicon-m-chat-bubble-left-ellipsis')
                ->color('warning'),

            Stat::make('ロープアクセス対応', number_format($ropeCompanies) . '社')
                ->description('公開中の業者数')
                ->descriptionIcon('heroicon-m-wrench-screwdriver')
                ->color('info'),

            Stat::make('ブランコ対応', number_format($brancoCompanies) . '社')
                ->description('公開中の業者数')
                ->descriptionIcon('heroicon-m-wrench-screwdriver')
                ->color('info'),

            Stat::make('高所作業車', number_format($aerialCompanies) . '社')
                ->description('公開中の業者数')
                ->descriptionIcon('heroicon-m-truck')
                ->color('info'),

            Stat::make('ゴンドラ対応', number_format($gondolaCompanies) . '社')
                ->description('公開中の業者数')
                ->descriptionIcon('heroicon-m-wrench-screwdriver')
                ->color('info'),
        ];
    }
}
