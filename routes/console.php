<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// 記事別GA4 PV（累計・前日）の同期（日本時間の朝6時に実行）
Schedule::command('articles:sync-ga-views')
    ->dailyAt('06:00')
    ->timezone('Asia/Tokyo')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/sync-article-ga-views.log'));
