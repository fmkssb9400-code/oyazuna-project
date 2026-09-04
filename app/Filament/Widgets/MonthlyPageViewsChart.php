<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Services\GoogleAnalyticsService;
use Carbon\Carbon;

class MonthlyPageViewsChart extends ChartWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = '月間PV推移';

    protected function getData(): array
    {
        $monthly = app(GoogleAnalyticsService::class)->getMonthlyPageViews(12);

        $data = array_values($monthly);
        $labels = array_map(
            fn (string $yearMonth) => Carbon::createFromFormat('Y-m', $yearMonth)->format('Y年n月'),
            array_keys($monthly)
        );

        return [
            'datasets' => [
                [
                    'label' => 'PV数',
                    'data' => $data,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
        ];
    }
}