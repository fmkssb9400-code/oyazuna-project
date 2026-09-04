<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AreaHubController extends Controller
{
    /**
     * 都道府県×工法の掛け合わせページを公開する最低掲載企業数の目安。
     * 単軸のarea/hubページと同じ基準（薄いページ防止のインデックスゲート）。
     */
    private const MIN_COMPANIES = 10;

    public function show(Request $request, string $areaSlug, string $hubSlug, AreaController $area, HubController $hub)
    {
        $areaConfig = $area->pages()[$areaSlug] ?? null;
        $hubConfig = $hub->pages()[$hubSlug] ?? null;

        abort_if(is_null($areaConfig) || is_null($hubConfig), 404);
        abort_unless($hubConfig['type'] === 'category', 404);

        $baseQuery = function () use ($areaConfig, $hubConfig): Builder {
            return Company::published()
                ->withCount('reviews')
                ->withAvg('reviews as average_rating', 'total_score')
                ->where(function (Builder $query) use ($areaConfig) {
                    $query->whereJsonContains('areas', $areaConfig['prefecture']);
                    foreach ($areaConfig['aliases'] ?? [] as $alias) {
                        $query->orWhereJsonContains('areas', $alias);
                    }
                })
                ->whereJsonContains('service_categories', $hubConfig['key'])
                ->orderByDesc('recommend_score');
        };

        // 該当する全企業を取得し、比較表・一覧・分析文の元データとして使い回す。
        $matchedCompanies = $baseQuery()->get();
        $count = $matchedCompanies->count();

        // 掲載企業が少なすぎる組み合わせは薄いページになるため公開しない（404）。
        abort_if($count < self::MIN_COMPANIES, 404);

        $topCompanies = $matchedCompanies->take(10);
        $companies = $baseQuery()->paginate(10)->withQueryString();

        $ratings = $matchedCompanies->pluck('average_rating')->filter(fn ($v) => $v !== null);
        $averageRating = $ratings->isNotEmpty() ? round($ratings->avg(), 1) : null;

        $stats = $this->computeStats($matchedCompanies, $count);
        $insightParagraph = $this->buildInsightParagraph($stats);
        $sections = $this->buildComboSections($stats, $areaConfig, $hubConfig);

        $combinations = collect($this->qualifyingCombinations($area, $hub));
        $siblingsByArea = $combinations->where('areaSlug', $areaSlug)->where('hubSlug', '!=', $hubSlug)->values();
        $siblingsByHub = $combinations->where('hubSlug', $hubSlug)->where('areaSlug', '!=', $areaSlug)->values();

        return view('area_hub.show', [
            'areaConfig' => $areaConfig,
            'hubConfig' => $hubConfig,
            'areaSlug' => $areaSlug,
            'hubSlug' => $hubSlug,
            'count' => $count,
            'averageRating' => $averageRating,
            'insightParagraph' => $insightParagraph,
            'sections' => $sections,
            'topCompanies' => $topCompanies,
            'companies' => $companies,
            'siblingsByArea' => $siblingsByArea,
            'siblingsByHub' => $siblingsByHub,
        ]);
    }

    /**
     * 該当エリア×工法に実際に一致する企業データから、分析文・FAQの元になる集計値を計算する。
     * 分析文とFAQの両方で同じ集計を使い回すため、ここで一箇所にまとめる。
     */
    private function computeStats($companies, int $count): array
    {
        $topTag = $companies->pluck('strength_tags')
            ->flatten()
            ->filter()
            ->countBy()
            ->sortDesc()
            ->keys()
            ->first();

        return [
            'count' => $count,
            'ropeCount' => $companies->where('rope_support', true)->count(),
            'gondolaCount' => $companies->where('gondola_supported', true)->count(),
            'emergencyCount' => $companies->where('emergency_supported', true)->count(),
            'weekendCount' => $companies->where('weekend_support', true)->count(),
            'nightCount' => $companies->where('night_support', true)->count(),
            'insuranceCount' => $companies->where('liability_insurance', true)->count(),
            'topTag' => $topTag,
        ];
    }

    /**
     * 集計値から、比較表の上に置く短い要約文を組み立てる。詳細な内訳はbuildComboSections()側の
     * 解説セクションに任せ、ここでは冒頭で数値がひと目でわかる1文に留める（ページ内での言い回しの
     * 重複を避けるため、緊急対応・保険加入・強みタグなどの詳細はここでは書かない）。
     */
    private function buildInsightParagraph(array $stats): string
    {
        return "掲載{$stats['count']}社のうち、ロープアクセス対応は{$stats['ropeCount']}社、ゴンドラ対応は{$stats['gondolaCount']}社です。";
    }

    /**
     * 集計値から、その組み合わせ固有の「見出し＋本文」解説セクションを組み立てる。
     * area/hubページの$config['sections']と同じ構造（heading/body）で返し、同じビュー表示ロジックを使い回す。
     * 都道府県の説明文・工法の説明文の使い回しではなく、実データの集計値だけから組み立てるため、
     * 組み合わせが変われば数値・順位が変わり、ページ間の文章重複が発生しない。
     */
    private function buildComboSections(array $stats, array $areaConfig, array $hubConfig): array
    {
        $methodBody = "{$areaConfig['prefecture']}で{$hubConfig['label']}に対応する業者を{$stats['count']}社掲載しています。"
            . "このうちロープアクセス対応が{$stats['ropeCount']}社、ゴンドラ対応が{$stats['gondolaCount']}社で、"
            . ($stats['ropeCount'] >= $stats['gondolaCount']
                ? 'ロープアクセス対応の業者が中心です。'
                : 'ゴンドラ対応の業者が中心です。')
            . '建物の高さ・形状・設置スペースによって適した工法は異なるため、現地調査のうえで最適な工法を提案してもらうことをおすすめします。';

        $supportParts = [];
        if ($stats['emergencyCount'] > 0) {
            $supportParts[] = "緊急対応可能な業者が{$stats['emergencyCount']}社";
        }
        if ($stats['weekendCount'] > 0) {
            $supportParts[] = "土日対応可能な業者が{$stats['weekendCount']}社";
        }
        if ($stats['nightCount'] > 0) {
            $supportParts[] = "夜間対応可能な業者が{$stats['nightCount']}社";
        }
        if ($stats['insuranceCount'] > 0) {
            $supportParts[] = "賠償責任保険に加入している業者が{$stats['insuranceCount']}社";
        }
        $supportBody = $supportParts
            ? implode('、', $supportParts) . '掲載しています。具体的な対応可否は業者によって異なるため、見積もり依頼時に希望の日時・条件を伝えて確認してください。'
            : '対応日時や保険加入状況は業者によって異なるため、見積もり依頼時に確認してください。';

        $body = [$methodBody, $supportBody];

        if ($stats['topTag']) {
            $body[] = "掲載業者に最も多い強みは「{$stats['topTag']}」です。業者を選ぶ際は、価格だけでなく実績・保険加入・対応可能な条件を比較したうえで判断することが重要です。";
        }

        return [
            [
                'heading' => "{$areaConfig['prefecture']}の{$hubConfig['label']}対応業者データ",
                'body' => $body,
            ],
        ];
    }

    /**
     * 閾値（掲載企業10社以上）を満たす都道府県×工法の組み合わせ一覧を返す。
     * サイトマップ生成・関連ページの内部リンク表示で共通利用するため、結果を短時間キャッシュする。
     *
     * @return array<int, array{areaSlug: string, hubSlug: string, areaConfig: array, hubConfig: array, count: int}>
     */
    public function qualifyingCombinations(AreaController $area, HubController $hub): array
    {
        return Cache::remember('area_hub.qualifying_combinations', now()->addHours(6), function () use ($area, $hub) {
            $categoryHubs = collect($hub->pages())->where('type', 'category');
            $combinations = [];

            foreach ($area->pages() as $areaSlug => $areaConfig) {
                foreach ($categoryHubs as $hubSlug => $hubConfig) {
                    $count = Company::published()
                        ->where(function (Builder $query) use ($areaConfig) {
                            $query->whereJsonContains('areas', $areaConfig['prefecture']);
                            foreach ($areaConfig['aliases'] ?? [] as $alias) {
                                $query->orWhereJsonContains('areas', $alias);
                            }
                        })
                        ->whereJsonContains('service_categories', $hubConfig['key'])
                        ->count();

                    if ($count >= self::MIN_COMPANIES) {
                        $combinations[] = [
                            'areaSlug' => $areaSlug,
                            'hubSlug' => $hubSlug,
                            'areaConfig' => $areaConfig,
                            'hubConfig' => $hubConfig,
                            'count' => $count,
                        ];
                    }
                }
            }

            return $combinations;
        });
    }
}
