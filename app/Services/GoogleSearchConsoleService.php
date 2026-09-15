<?php

namespace App\Services;

use Carbon\Carbon;
use Google\Client as GoogleClient;
use Google\Service\SearchConsole;
use Google\Service\SearchConsole\SearchAnalyticsQueryRequest;
use Illuminate\Support\Facades\Log;

class GoogleSearchConsoleService
{
    private function client(): ?SearchConsole
    {
        $credentialsPath = config('services.google_search_console.credentials_path');

        if (! config('services.google_search_console.site_url') || ! $credentialsPath || ! file_exists($credentialsPath)) {
            return null;
        }

        $client = new GoogleClient();
        $client->setAuthConfig($credentialsPath);
        $client->addScope(SearchConsole::WEBMASTERS_READONLY);

        return new SearchConsole($client);
    }

    private function siteUrl(): string
    {
        return config('services.google_search_console.site_url');
    }

    /**
     * 指定期間の合計値（クリック数・表示回数・CTR・平均掲載順位）
     */
    public function getTotals(Carbon $start, Carbon $end): array
    {
        $default = ['clicks' => 0, 'impressions' => 0, 'ctr' => 0.0, 'position' => 0.0];

        try {
            $service = $this->client();
            if (! $service) {
                return $default;
            }

            $request = new SearchAnalyticsQueryRequest([
                'startDate' => $start->toDateString(),
                'endDate' => $end->toDateString(),
            ]);

            $rows = $service->searchanalytics->query($this->siteUrl(), $request)->getRows();

            if (empty($rows)) {
                return $default;
            }

            $row = $rows[0];

            return [
                'clicks' => (int) $row->getClicks(),
                'impressions' => (int) $row->getImpressions(),
                'ctr' => (float) $row->getCtr(),
                'position' => (float) $row->getPosition(),
            ];
        } catch (\Throwable $e) {
            Log::warning('GSC getTotals failed: '.$e->getMessage());

            return $default;
        }
    }

    /**
     * 指定期間のクエリ別パフォーマンス（クリック数降順）
     * 戻り値: [['query' => '...', 'clicks' => 10, 'impressions' => 100, 'ctr' => 0.1, 'position' => 5.2], ...]
     */
    public function getTopQueries(Carbon $start, Carbon $end, int $limit = 20): array
    {
        return $this->getTopByDimension('query', $start, $end, $limit);
    }

    /**
     * 指定期間のページ別パフォーマンス（クリック数降順）
     */
    public function getTopPages(Carbon $start, Carbon $end, int $limit = 20): array
    {
        return $this->getTopByDimension('page', $start, $end, $limit);
    }

    private function getTopByDimension(string $dimension, Carbon $start, Carbon $end, int $limit): array
    {
        try {
            $service = $this->client();
            if (! $service) {
                return [];
            }

            $request = new SearchAnalyticsQueryRequest([
                'startDate' => $start->toDateString(),
                'endDate' => $end->toDateString(),
                'dimensions' => [$dimension],
                'rowLimit' => $limit,
            ]);

            $rows = $service->searchanalytics->query($this->siteUrl(), $request)->getRows() ?? [];

            $result = [];
            foreach ($rows as $row) {
                $keys = $row->getKeys();
                $result[] = [
                    $dimension => $keys[0] ?? '',
                    'clicks' => (int) $row->getClicks(),
                    'impressions' => (int) $row->getImpressions(),
                    'ctr' => (float) $row->getCtr(),
                    'position' => (float) $row->getPosition(),
                ];
            }

            return $result;
        } catch (\Throwable $e) {
            Log::warning("GSC getTopByDimension({$dimension}) failed: ".$e->getMessage());

            return [];
        }
    }

    public function isConfigured(): bool
    {
        return (bool) $this->client();
    }
}
