<?php

namespace App\Console\Commands;

use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

/**
 * 自動記事パイプライン専用の取り込みコマンド（標準入力のJSONから予約記事を1本作成）。
 * 制限付きSSH鍵のforced commandからのみ呼ばれる想定。「作成のみ・更新削除不可・検証に落ちたら保存しない」。
 */
class IngestArticle extends Command
{
    protected $signature = 'articles:ingest {--dry-run : 検証のみ行い保存しない}';

    protected $description = 'Create one scheduled article from JSON on stdin (restricted pipeline entrypoint)';

    private const MAX_BYTES = 300000;
    private const MAX_PER_24H = 4;
    private const MIN_TEXT_LENGTH = 4000;

    public function handle(): int
    {
        $raw = stream_get_contents(STDIN, self::MAX_BYTES + 1);
        if ($raw === false || $raw === '' || strlen($raw) > self::MAX_BYTES) {
            return $this->reject('stdinが空、または上限(300KB)超過');
        }

        $data = json_decode($raw, true);
        if (!is_array($data)) {
            return $this->reject('JSONとして不正');
        }

        $v = Validator::make($data, [
            'title' => ['required', 'string', 'min:10', 'max:100'],
            'slug' => ['required', 'string', 'max:100', 'regex:/^(?=.*[a-z])[a-z0-9]+(-[a-z0-9]+)*$/', 'unique:articles,slug'],
            'excerpt' => ['required', 'string', 'min:80', 'max:200'],
            'content' => ['required', 'string'],
            'tags' => ['nullable', 'array', 'max:8'],
            'tags.*' => ['string', 'max:30'],
            'published_at' => ['required', 'date_format:Y-m-d\TH:i:sP'],
        ]);
        if ($v->fails()) {
            return $this->reject(implode(' / ', $v->errors()->all()));
        }

        $errors = $this->contentErrors($data['content']);

        $publishAt = Carbon::parse($data['published_at'])->utc();
        if ($publishAt->lt(now()->addMinutes(30)) || $publishAt->gt(now()->addDays(7))) {
            $errors[] = 'published_atは「30分後〜7日以内」の未来日時のみ許可';
        }

        if (Article::where('created_at', '>=', now()->subDay())->count() >= self::MAX_PER_24H) {
            $errors[] = '24時間あたりの作成上限(' . self::MAX_PER_24H . '本)に達している';
        }

        if ($errors) {
            return $this->reject(implode(' / ', $errors));
        }

        if ($this->option('dry-run')) {
            $this->line(json_encode(['ok' => true, 'dry_run' => true], JSON_UNESCAPED_UNICODE));
            return 0;
        }

        $article = Article::create([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'excerpt' => $data['excerpt'],
            'content' => $data['content'],
            'tags' => $data['tags'] ?? [],
            'is_published' => true,
            'published_at' => $publishAt,
            'supervisor_id' => 1,
            'show_supervisor' => true,
        ]);

        $this->line(json_encode([
            'ok' => true,
            'id' => $article->id,
            'slug' => $article->slug,
            'published_at_utc' => $article->published_at->toIso8601String(),
        ], JSON_UNESCAPED_UNICODE));

        return 0;
    }

    private function contentErrors(string $html): array
    {
        $errors = [];

        if (mb_strlen(strip_tags($html)) < self::MIN_TEXT_LENGTH) {
            $errors[] = '本文が' . self::MIN_TEXT_LENGTH . '字未満';
        }
        if (preg_match('/<\s*(script|iframe|object|embed|form|style|link|meta|base)\b/i', $html)) {
            $errors[] = '禁止タグ(script/iframe/object/embed/form/style/link/meta/base)を含む';
        }
        if (preg_match('/\son[a-z]+\s*=/i', $html) || preg_match('/(javascript|data)\s*:/i', $html)) {
            $errors[] = 'イベントハンドラまたはjavascript:/data:URLを含む';
        }
        if (stripos($html, '#f1c40f') !== false) {
            $errors[] = '黄色ハイライトspan(#f1c40f)を含む';
        }

        return $errors;
    }

    private function reject(string $message): int
    {
        $this->line(json_encode(['ok' => false, 'error' => $message], JSON_UNESCAPED_UNICODE));
        return 1;
    }
}
