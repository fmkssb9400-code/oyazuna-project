<?php

namespace App\Console\Commands;

use App\Services\GoogleAnalyticsService;
use App\Services\GoogleSearchConsoleService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SendWeeklyPerformanceReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:weekly-performance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'GSC・GA4の週次パフォーマンスをメールでレポートする（4回に1回は直近4週間の月次サマリーも添える）';

    private const STATE_FILE = 'report_state.json';

    // 前期間比±10%を「グッドポイント／伸び代ポイント」として拾う目安の閾値
    private const MOVE_THRESHOLD = 0.10;

    public function handle(GoogleSearchConsoleService $gsc, GoogleAnalyticsService $ga): int
    {
        $to = config('services.report.email_to');
        if (! $to) {
            $this->error('REPORT_EMAIL_TO が未設定です。.envに設定してください。');

            return self::FAILURE;
        }

        $today = Carbon::now('Asia/Tokyo');
        $thisMonday = $today->copy()->startOfWeek(Carbon::MONDAY);

        // 「先週」の月〜日
        $periodStart = $thisMonday->copy()->subWeek();
        $periodEnd = $thisMonday->copy()->subDay()->endOfDay();
        $comparePeriodStart = $periodStart->copy()->subWeek();
        $comparePeriodEnd = $periodEnd->copy()->subWeek();

        $this->info("対象期間: {$periodStart->toDateString()} 〜 {$periodEnd->toDateString()}");

        $weekly = $this->buildSection($gsc, $ga, $periodStart, $periodEnd, $comparePeriodStart, $comparePeriodEnd);
        $body = $this->disclaimer()."\n\n".$this->renderSection('週次サマリー', $periodStart, $periodEnd, $weekly);

        $isMonthlyDue = $this->bumpWeekCounter();

        if ($isMonthlyDue) {
            $monthlyStart = $periodStart->copy()->subWeeks(3);
            $monthlyEnd = $periodEnd->copy();
            $compareMonthlyStart = $monthlyStart->copy()->subWeeks(4);
            $compareMonthlyEnd = $monthlyEnd->copy()->subWeeks(4);

            $monthly = $this->buildSection($gsc, $ga, $monthlyStart, $monthlyEnd, $compareMonthlyStart, $compareMonthlyEnd);
            $body .= "\n\n".$this->renderSection('月次サマリー（直近4週間）', $monthlyStart, $monthlyEnd, $monthly);
        }

        $subject = sprintf(
            '【oyazuna】週次パフォーマンスレポート（%s〜%s）%s',
            $periodStart->format('n/j'),
            $periodEnd->format('n/j'),
            $isMonthlyDue ? '＋月次サマリー' : ''
        );

        Mail::raw($body, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });

        $this->info('レポートを送信しました: '.$to);

        return self::SUCCESS;
    }

    private function disclaimer(): string
    {
        return "※このレポートは各指標の前期間比を機械的に閾値判定（±10%）したものです。数値の解釈・施策判断はレポートを見た上で別途検討してください。";
    }

    private function buildSection(
        GoogleSearchConsoleService $gsc,
        GoogleAnalyticsService $ga,
        Carbon $start,
        Carbon $end,
        Carbon $compareStart,
        Carbon $compareEnd
    ): array {
        $gscCurrent = $gsc->getTotals($start, $end);
        $gscPrevious = $gsc->getTotals($compareStart, $compareEnd);

        $gaCurrent = $ga->getPageViews($start, $end);
        $gaPrevious = $ga->getPageViews($compareStart, $compareEnd);

        $metrics = [
            [
                'noun' => 'GSC経由の検索結果クリック数',
                'unit' => '件',
                'current' => $gscCurrent['clicks'],
                'previous' => $gscPrevious['clicks'],
                'higherIsBetter' => true,
                'decimals' => 0,
                'positionStyle' => false,
                'suggestion' => '表示回数に対してクリック率（CTR）が下がっていないか確認し、タイトルやmeta descriptionが検索意図に合っているか見直すと改善が見込めます。',
            ],
            [
                'noun' => 'GSC検索結果への表示回数（インプレッション）',
                'unit' => '件',
                'current' => $gscCurrent['impressions'],
                'previous' => $gscPrevious['impressions'],
                'higherIsBetter' => true,
                'decimals' => 0,
                'positionStyle' => false,
                'suggestion' => '対象キーワードでの掲載順位が落ちていないか確認し、コンテンツの網羅性を広げるか関連記事を増やすと改善が見込めます。',
            ],
            [
                'noun' => 'GSC検索結果の平均掲載順位',
                'unit' => '位',
                'current' => $gscCurrent['position'],
                'previous' => $gscPrevious['position'],
                'higherIsBetter' => false,
                'decimals' => 1,
                'positionStyle' => true,
                'suggestion' => '順位が下がったページ・クエリを特定し、内部リンクの追加やコンテンツの更新（最新の情報・出典への差し替え）を検討すると改善が見込めます。',
            ],
            [
                'noun' => 'GA4のページビュー数',
                'unit' => '件',
                'current' => $gaCurrent,
                'previous' => $gaPrevious,
                'higherIsBetter' => true,
                'decimals' => 0,
                'positionStyle' => false,
                'suggestion' => '検索以外の流入経路（SNS・直接流入・外部リンクなど）に大きな変化がないか確認すると原因の切り分けがしやすくなります。',
            ],
        ];

        $goodPoints = [];
        $growthPoints = [];

        foreach ($metrics as $m) {
            $delta = $this->computeDelta($m['current'], $m['previous']);
            if ($delta['pct'] === null) {
                continue;
            }

            $isImprovement = $m['higherIsBetter'] ? $delta['pct'] >= self::MOVE_THRESHOLD : $delta['pct'] <= -self::MOVE_THRESHOLD;
            $isDecline = $m['higherIsBetter'] ? $delta['pct'] <= -self::MOVE_THRESHOLD : $delta['pct'] >= self::MOVE_THRESHOLD;

            $sentence = $this->metricSentence(
                $m['noun'],
                $m['unit'],
                $m['previous'],
                $m['current'],
                $delta['pct'],
                $m['decimals'],
                $m['positionStyle']
            );

            if ($isImprovement) {
                $goodPoints[] = $sentence;
            } elseif ($isDecline) {
                $growthPoints[] = $sentence.' '.$m['suggestion'];
            }
        }

        $queryMovers = $this->buildMovers(
            $gsc->getTopQueries($start, $end, 50),
            $gsc->getTopQueries($compareStart, $compareEnd, 50),
            'query'
        );

        foreach (array_slice($queryMovers['gainers'], 0, 3) as $g) {
            $goodPoints[] = sprintf(
                '検索クエリ「%s」からのクリックが%d件から%d件に増えました。',
                $g['key'],
                $g['previous'],
                $g['current']
            );
        }
        foreach (array_slice($queryMovers['losers'], 0, 3) as $l) {
            $growthPoints[] = sprintf(
                '検索クエリ「%s」からのクリックが%d件から%d件に減りました。該当ページの掲載順位・タイトル・見出しが検索意図とずれていないか見直すと改善が見込めます。',
                $l['key'],
                $l['previous'],
                $l['current']
            );
        }

        return [
            'gsc' => $gscCurrent,
            'gscPrevious' => $gscPrevious,
            'ga' => $gaCurrent,
            'gaPrevious' => $gaPrevious,
            'goodPoints' => $goodPoints,
            'growthPoints' => $growthPoints,
        ];
    }

    /**
     * $positionStyle=trueの場合、数値が小さくなることを「上昇（良化）」として表現する
     * （検索順位は数値が小さいほど良いため、件数系の指標と語の向きが逆になる）。
     */
    private function metricSentence(
        string $noun,
        string $unit,
        int|float $previous,
        int|float $current,
        float $pct,
        int $decimals,
        bool $positionStyle
    ): string {
        if ($positionStyle) {
            $verb = $current < $previous ? '上昇' : '低下';
        } else {
            $verb = $current > $previous ? '増加' : '減少';
        }

        return sprintf(
            '%sが前期間の%s%sから%s%sに%sしました（前期間比%+.1f%%）。',
            $noun,
            number_format($previous, $decimals),
            $unit,
            number_format($current, $decimals),
            $unit,
            $verb,
            $pct * 100
        );
    }

    private function computeDelta(int|float $current, int|float $previous): array
    {
        $abs = $current - $previous;
        $pct = $previous > 0 ? $abs / $previous : null;

        return ['abs' => $abs, 'pct' => $pct];
    }

    /**
     * 上位50件（クリック数降順）の範囲内で2期間を突き合わせ、増減の大きい順に並べる。
     * どちらの期間でも上位50件に入らないクエリ・ページは対象外（小規模サイト運用が前提の簡易実装）。
     */
    private function buildMovers(array $currentRows, array $compareRows, string $dimension): array
    {
        $currentMap = [];
        foreach ($currentRows as $row) {
            $currentMap[$row[$dimension]] = $row['clicks'];
        }

        $previousMap = [];
        foreach ($compareRows as $row) {
            $previousMap[$row[$dimension]] = $row['clicks'];
        }

        $keys = array_unique(array_merge(array_keys($currentMap), array_keys($previousMap)));

        $moves = [];
        foreach ($keys as $key) {
            $current = $currentMap[$key] ?? 0;
            $previous = $previousMap[$key] ?? 0;
            if ($current === $previous) {
                continue;
            }

            $moves[] = ['key' => $key, 'current' => $current, 'previous' => $previous, 'delta' => $current - $previous];
        }

        usort($moves, fn ($a, $b) => $b['delta'] <=> $a['delta']);

        $gainers = array_values(array_filter($moves, fn ($m) => $m['delta'] > 0));
        $losers = array_reverse(array_values(array_filter($moves, fn ($m) => $m['delta'] < 0)));

        return ['gainers' => $gainers, 'losers' => $losers];
    }

    private function renderSection(string $title, Carbon $start, Carbon $end, array $section): string
    {
        $lines = [];
        $lines[] = "■ {$title}（{$start->format('Y/n/j')}〜{$end->format('Y/n/j')}）";
        $lines[] = '';
        $lines[] = sprintf(
            'GSC: クリック%d件（前期間%d件） / 表示回数%d件（前期間%d件） / 平均掲載順位%.1f位（前期間%.1f位）',
            $section['gsc']['clicks'],
            $section['gscPrevious']['clicks'],
            $section['gsc']['impressions'],
            $section['gscPrevious']['impressions'],
            $section['gsc']['position'],
            $section['gscPrevious']['position']
        );
        $lines[] = sprintf('GA4: ページビュー%d件（前期間%d件）', $section['ga'], $section['gaPrevious']);
        $lines[] = '';
        $lines[] = '【グッドポイント】';
        $lines[] = empty($section['goodPoints'])
            ? '（前期間比±10%以上の動きなし）'
            : implode("\n", array_map(fn ($p) => "・{$p}", $section['goodPoints']));
        $lines[] = '';
        $lines[] = '【伸び代ポイント】';
        $lines[] = empty($section['growthPoints'])
            ? '（前期間比±10%以上の動きなし）'
            : implode("\n", array_map(fn ($p) => "・{$p}", $section['growthPoints']));

        return implode("\n", $lines);
    }

    /**
     * 実行回数（週）を1〜4でカウントし、4回目なら月次サマリーを出す合図を返す。
     */
    private function bumpWeekCounter(): bool
    {
        $state = [];
        if (Storage::exists(self::STATE_FILE)) {
            $state = json_decode(Storage::get(self::STATE_FILE), true) ?: [];
        }

        $count = ($state['week_count'] ?? 0) + 1;
        $isMonthlyDue = $count >= 4;

        Storage::put(self::STATE_FILE, json_encode(['week_count' => $isMonthlyDue ? 0 : $count]));

        return $isMonthlyDue;
    }
}
