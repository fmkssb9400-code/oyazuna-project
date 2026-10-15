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
    private const MAX_PENDING = 14; // 未公開(予約中)のパイプライン記事の上限(7日×2枠)
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

        $jst = $publishAt->copy()->setTimezone('Asia/Tokyo');
        if (!in_array($jst->hour, [6, 12], true) || $jst->minute !== 0 || $jst->second !== 0) {
            $errors[] = 'published_atはJSTの6:00または12:00ちょうどのみ許可';
        }
        if (Article::where('published_at', $publishAt)->exists()) {
            $errors[] = '同じ公開枠に既に記事がある';
        }
        if ($this->pendingIngestCount() >= self::MAX_PENDING) {
            $errors[] = '予約中のパイプライン記事が上限(' . self::MAX_PENDING . '本)に達している';
        }
        if (!$this->option('dry-run') && !$this->auditLogWritable()) {
            $errors[] = '監査ログに書き込めないため作成を拒否(上限管理が効かなくなるため)';
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

        file_put_contents(
            $this->auditLogPath(),
            json_encode(['at' => now()->toIso8601String(), 'id' => $article->id, 'slug' => $article->slug]) . "\n",
            FILE_APPEND | LOCK_EX
        );

        $this->line(json_encode([
            'ok' => true,
            'id' => $article->id,
            'slug' => $article->slug,
            'published_at_utc' => $article->published_at->toIso8601String(),
        ], JSON_UNESCAPED_UNICODE));

        return 0;
    }

    private function auditLogPath(): string
    {
        return storage_path('logs/pipeline-ingest.log');
    }

    private function auditLogWritable(): bool
    {
        $path = $this->auditLogPath();

        return file_exists($path) ? is_writable($path) : is_writable(dirname($path));
    }

    // 監査ログ上のパイプライン記事のうち、まだ公開日時が来ていない(予約中)本数
    private function pendingIngestCount(): int
    {
        $path = $this->auditLogPath();
        if (!is_file($path)) {
            return 0;
        }

        $ids = [];
        foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $row = json_decode($line, true);
            if (isset($row['id'])) {
                $ids[] = (int) $row['id'];
            }
        }
        if (!$ids) {
            return 0;
        }

        return Article::whereIn('id', $ids)->where('published_at', '>', now())->count();
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
