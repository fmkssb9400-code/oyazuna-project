<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// 記事別GA4 PV（累計・直近日）の同期（毎時実行、ダッシュボードと同程度の鮮度に揃える）
Schedule::command('articles:sync-ga-views')
    ->hourly()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/sync-article-ga-views.log'));
