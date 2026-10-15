<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;

/**
 * 自動記事パイプライン用の読み取り専用一覧（重複・カニバリ確認用）。本文は出力しない。
 */
class ListArticlesReadonly extends Command
{
    protected $signature = 'articles:list-ro';

    protected $description = 'Print id/slug/title/status of all articles as JSON (read-only)';

    public function handle(): int
    {
        $rows = Article::orderBy('id')->get(['id', 'slug', 'title', 'is_published', 'published_at'])
            ->map(fn ($a) => [
                'id' => $a->id,
                'slug' => $a->slug,
                'title' => $a->title,
                'is_published' => (bool) $a->is_published,
                'published_at_utc' => $a->published_at?->toIso8601String(),
            ]);

        $this->line($rows->toJson(JSON_UNESCAPED_UNICODE));

        return 0;
    }
}
