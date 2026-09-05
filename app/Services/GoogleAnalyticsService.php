<?php

namespace App\Services;

use Carbon\Carbon;
use Google\Analytics\Data\V1beta\Client\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Filter;
use Google\Analytics\Data\V1beta\Filter\StringFilter;
use Google\Analytics\Data\V1beta\Filter\StringFilter\MatchType;
use Google\Analytics\Data\V1beta\FilterExpression;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\OrderBy;
use Google\Analytics\Data\V1beta\OrderBy\MetricOrderBy;
use Google\Analytics\Data\V1beta\RunReportRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GoogleAnalyticsService
{
    private const CACHE_TTL_SECONDS = 3600;

    private function client(): ?BetaAnalyticsDataClient
    {
        $credentialsPath = config('services.google_analytics.credentials_path');

        if (! config('services.google_analytics.property_id') || ! $credentialsPath || ! file_exists($credentialsPath)) {
            return null;
        }

        return new BetaAnalyticsDataClient([
            'credentials' => $credentialsPath,
        ]);
    }

    private function propertyName(): string
    {
        return 'properties/' . config('services.google_analytics.property_id');
    }

    private function pathPrefixFilter(string $pathPrefix): FilterExpression
    {
        return new FilterExpression([
            'filter' => new Filter([
                'field_name' => 'pagePath',
                'string_filter' => new StringFilter([
                    'match_type' => MatchType::BEGINS_WITH,
                    'value' => $pathPrefix,
                ]),
            ]),
        ]);
    }

    /**
     * 指定期間の合計ページビュー数
     */
    public function getPageViews(Carbon $start, Carbon $end): int
    {
        $cacheKey = 'ga4:page_views:' . $start->toDateString() . ':' . $end->toDateString();

        return Cache::remember($cacheKey, self::CACHE_TTL_SECONDS, function () use ($start, $end) {
            try {
                $client = $this->client();
                if (! $client) {
                    return 0;
                }

                $response = $client->runReport(new RunReportRequest([
                    'property' => $this->propertyName(),
                    'date_ranges' => [
                        new DateRange([
                            'start_date' => $start->toDateString(),
                            'end_date' => $end->toDateString(),
                        ]),
                    ],
                    'metrics' => [
                        new Metric(['name' => 'screenPageViews']),
                    ],
                ]));

                foreach ($response->getRows() as $row) {
                    return (int) $row->getMetricValues()[0]->getValue();
                }

                return 0;
            } catch (\Throwable $e) {
                Log::warning('GA4 getPageViews failed: ' . $e->getMessage());

                return 0;
            }
        });
    }

    /**
     * 指定パス配下（例: /news/）のページビュー数
     */
    public function getPageViewsForPathPrefix(string $pathPrefix, Carbon $start, Carbon $end): int
    {
        $cacheKey = 'ga4:page_views_prefix:' . md5($pathPrefix) . ':' . $start->toDateString() . ':' . $end->toDateString();

        return Cache::remember($cacheKey, self::CACHE_TTL_SECONDS, function () use ($pathPrefix, $start, $end) {
            try {
                $client = $this->client();
                if (! $client) {
                    return 0;
                }

                $response = $client->runReport(new RunReportRequest([
                    'property' => $this->propertyName(),
                    'date_ranges' => [
                        new DateRange([
                            'start_date' => $start->toDateString(),
                            'end_date' => $end->toDateString(),
                        ]),
                    ],
                    'dimensions' => [
                        new Dimension(['name' => 'pagePath']),
                    ],
                    'metrics' => [
                        new Metric(['name' => 'screenPageViews']),
                    ],
                    'dimension_filter' => $this->pathPrefixFilter($pathPrefix),
                ]));

                $total = 0;
                foreach ($response->getRows() as $row) {
                    $total += (int) $row->getMetricValues()[0]->getValue();
                }

                return $total;
            } catch (\Throwable $e) {
                Log::warning('GA4 getPageViewsForPathPrefix failed: ' . $e->getMessage());

                return 0;
            }
        });
    }

    /**
     * 指定パス配下のページごとのPVランキング
     * 戻り値: [['path' => '/news/xxx', 'views' => 123], ...]
     */
    public function getTopPagesForPathPrefix(string $pathPrefix, Carbon $start, Carbon $end, int $limit = 10): array
    {
        $cacheKey = 'ga4:top_pages:' . md5($pathPrefix) . ':' . $start->toDateString() . ':' . $end->toDateString() . ':' . $limit;

        return Cache::remember($cacheKey, self::CACHE_TTL_SECONDS, function () use ($pathPrefix, $start, $end, $limit) {
            try {
                $client = $this->client();
                if (! $client) {
                    return [];
                }

                $response = $client->runReport(new RunReportRequest([
                    'property' => $this->propertyName(),
                    'date_ranges' => [
                        new DateRange([
                            'start_date' => $start->toDateString(),
                            'end_date' => $end->toDateString(),
                        ]),
                    ],
                    'dimensions' => [
                        new Dimension(['name' => 'pagePath']),
                    ],
                    'metrics' => [
                        new Metric(['name' => 'screenPageViews']),
                    ],
                    'dimension_filter' => $this->pathPrefixFilter($pathPrefix),
                    'order_bys' => [
                        new OrderBy([
                            'metric' => new MetricOrderBy(['metric_name' => 'screenPageViews']),
                            'desc' => true,
                        ]),
                    ],
                    'limit' => $limit,
                ]));

                $result = [];
                foreach ($response->getRows() as $row) {
                    $result[] = [
                        'path' => $row->getDimensionValues()[0]->getValue(),
                        'views' => (int) $row->getMetricValues()[0]->getValue(),
                    ];
                }

                return $result;
            } catch (\Throwable $e) {
                Log::warning('GA4 getTopPagesForPathPrefix failed: ' . $e->getMessage());

                return [];
            }
        });
    }

    /**
     * 過去Nヶ月分の月別ページビュー推移
     * 戻り値: ['2026-01' => 1234, '2026-02' => 2345, ...]（月キーは昇順）
     */
    public function getMonthlyPageViews(int $months = 12): array
    {
        $end = Carbon::now()->endOfMonth();
        $start = Carbon::now()->subMonths($months - 1)->startOfMonth();

        $cacheKey = 'ga4:monthly_page_views:' . $start->toDateString() . ':' . $end->toDateString();

        return Cache::remember($cacheKey, self::CACHE_TTL_SECONDS, function () use ($start, $end) {
            $labels = [];
            for ($date = $start->copy(); $date->lte($end); $date->addMonth()) {
                $labels[$date->format('Y-m')] = 0;
            }

            try {
                $client = $this->client();
                if (! $client) {
                    return $labels;
                }

                $response = $client->runReport(new RunReportRequest([
                    'property' => $this->propertyName(),
                    'date_ranges' => [
                        new DateRange([
                            'start_date' => $start->toDateString(),
                            'end_date' => $end->toDateString(),
                        ]),
                    ],
                    'dimensions' => [
                        new Dimension(['name' => 'yearMonth']),
                    ],
                    'metrics' => [
                        new Metric(['name' => 'screenPageViews']),
                    ],
                ]));

                foreach ($response->getRows() as $row) {
                    $yearMonth = $row->getDimensionValues()[0]->getValue(); // e.g. "202609"
                    $key = substr($yearMonth, 0, 4) . '-' . substr($yearMonth, 4, 2);
                    if (array_key_exists($key, $labels)) {
                        $labels[$key] = (int) $row->getMetricValues()[0]->getValue();
                    }
                }

                return $labels;
            } catch (\Throwable $e) {
                Log::warning('GA4 getMonthlyPageViews failed: ' . $e->getMessage());

                return $labels;
            }
        });
    }

    /**
     * 指定パス配下（例: /news/）のページごとのページビュー数を、パスをキーにしたマップで返す
     * 戻り値: ['/news/xxx' => 123, ...]（バッチ処理でのDB反映用。上限件数を絞らない）
     */
    public function getPageViewsByPathPrefix(string $pathPrefix, string $startDate, string $endDate): array
    {
        $cacheKey = 'ga4:page_views_by_path:' . md5($pathPrefix) . ':' . $startDate . ':' . $endDate;

        return Cache::remember($cacheKey, self::CACHE_TTL_SECONDS, function () use ($pathPrefix, $startDate, $endDate) {
            try {
                $client = $this->client();
                if (! $client) {
                    return [];
                }

                $response = $client->runReport(new RunReportRequest([
                    'property' => $this->propertyName(),
                    'date_ranges' => [
                        new DateRange([
                            'start_date' => $startDate,
                            'end_date' => $endDate,
                        ]),
                    ],
                    'dimensions' => [
                        new Dimension(['name' => 'pagePath']),
                    ],
                    'metrics' => [
                        new Metric(['name' => 'screenPageViews']),
                    ],
                    'dimension_filter' => $this->pathPrefixFilter($pathPrefix),
                    'limit' => 100000,
                ]));

                $result = [];
                foreach ($response->getRows() as $row) {
                    $path = $row->getDimensionValues()[0]->getValue();
                    $views = (int) $row->getMetricValues()[0]->getValue();
                    $result[$path] = ($result[$path] ?? 0) + $views;
                }

                return $result;
            } catch (\Throwable $e) {
                Log::warning('GA4 getPageViewsByPathPrefix failed: ' . $e->getMessage());

                return [];
            }
        });
    }

    public function isConfigured(): bool
    {
        return (bool) $this->client();
    }

    /**
     * GA4に実際にデータが入っている最新の日付。
     * 反映ラグにより「今日」のデータがまだ無いことがあるため、直近7日を遡って
     * PVが1件以上ある最新の日を返す。データが1件も無ければ null。
     */
    public function getLatestDataDate(): ?Carbon
    {
        $cacheKey = 'ga4:latest_data_date:' . Carbon::today()->toDateString();

        return Cache::remember($cacheKey, self::CACHE_TTL_SECONDS, function () {
            try {
                $client = $this->client();
                if (! $client) {
                    return null;
                }

                $start = Carbon::today()->subDays(6);
                $end = Carbon::today();

                $response = $client->runReport(new RunReportRequest([
                    'property' => $this->propertyName(),
                    'date_ranges' => [
                        new DateRange([
                            'start_date' => $start->toDateString(),
                            'end_date' => $end->toDateString(),
                        ]),
                    ],
                    'dimensions' => [
                        new Dimension(['name' => 'date']),
                    ],
                    'metrics' => [
                        new Metric(['name' => 'screenPageViews']),
                    ],
                ]));

                $latest = null;
                foreach ($response->getRows() as $row) {
                    if ((int) $row->getMetricValues()[0]->getValue() <= 0) {
                        continue;
                    }

                    $date = Carbon::createFromFormat('Ymd', $row->getDimensionValues()[0]->getValue());
                    if (! $latest || $date->gt($latest)) {
                        $latest = $date;
                    }
                }

                return $latest;
            } catch (\Throwable $e) {
                Log::warning('GA4 getLatestDataDate failed: ' . $e->getMessage());

                return null;
            }
        });
    }
}
