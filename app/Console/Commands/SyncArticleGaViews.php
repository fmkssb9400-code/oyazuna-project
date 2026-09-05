<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Services\GoogleAnalyticsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncArticleGaViews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:sync-ga-views';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'GA4から記事ごとの累計PVと前日PVを取得してarticlesテーブルに反映する';

    /**
     * Execute the console command.
     */
    public function handle(GoogleAnalyticsService $ga): int
    {
        $targetDate = now()->subDay();
        $targetDateStr = $targetDate->format('Y-m-d');

        $this->info('累計PVを取得中...');
        $cumulative = $ga->getPageViewsByPathPrefix('/news/', '2015-08-14', $targetDateStr);

        $this->info("前日PV（{$targetDateStr}）を取得中...");
        $daily = $ga->getPageViewsByPathPrefix('/news/', $targetDateStr, $targetDateStr);

        if (empty($cumulative) && empty($daily)) {
            $this->warn('GA4からデータを取得できませんでした（認証情報未設定の可能性があります）。処理を中断します。');

            return self::SUCCESS;
        }

        $articles = Article::whereNull('company_id')->whereNotNull('slug')->get(['id', 'slug']);
        $updated = 0;

        foreach ($articles as $article) {
            $path = '/news/' . $article->slug;

            // updated_at（サイトマップのlastmodに使用）を変更しないよう、Eloquentのsave/updateではなくDBファサードで直接更新する
            DB::table('articles')
                ->where('id', $article->id)
                ->update([
                    'ga_total_views' => $cumulative[$path] ?? 0,
                    'ga_daily_views' => $daily[$path] ?? 0,
                    'ga_stats_date' => $targetDateStr,
                ]);

            $updated++;
        }

        $this->info("{$updated}件の記事のGA PVを更新しました（{$targetDateStr}時点）。");
        Log::info('GA4記事別PVを同期しました', ['updated' => $updated, 'target_date' => $targetDateStr]);

        return self::SUCCESS;
    }
}
