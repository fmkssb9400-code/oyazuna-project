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

        $insightParagraph = $this->buildInsightParagraph($matchedCompanies, $count);

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
            'topCompanies' => $topCompanies,
            'companies' => $companies,
            'siblingsByArea' => $siblingsByArea,
            'siblingsByHub' => $siblingsByHub,
        ]);
    }

    /**
     * 該当エリア×工法に実際に一致する企業データから、その組み合わせ固有の分析文を組み立てる。
     * 都道府県の説明文・工法の説明文をそのまま使い回すだけだとページ間で文章が重複するため、
     * 実データ（対応工法の内訳・強みタグの傾向）から数値の異なる文章を自動生成して独自性を持たせる。
     */
    private function buildInsightParagraph($companies, int $count): string
    {
        $ropeCount = $companies->where('rope_support', true)->count();
        $gondolaCount = $companies->where('gondola_supported', true)->count();
        $emergencyCount = $companies->where('emergency_supported', true)->count();
        $weekendCount = $companies->where('weekend_support', true)->count();
        $insuranceCount = $companies->where('liability_insurance', true)->count();

        $topTag = $companies->pluck('strength_tags')
            ->flatten()
            ->filter()
            ->countBy()
            ->sortDesc()
            ->keys()
            ->first();

        $sentences = [];
        $sentences[] = "掲載{$count}社のうち、ロープアクセス対応は{$ropeCount}社、ゴンドラ対応は{$gondolaCount}社です。";

        $supportParts = [];
        if ($emergencyCount > 0) {
            $supportParts[] = "緊急対応可能な業者が{$emergencyCount}社";
        }
        if ($weekendCount > 0) {
            $supportParts[] = "土日対応可能な業者が{$weekendCount}社";
        }
        if ($insuranceCount > 0) {
            $supportParts[] = "賠償責任保険に加入している業者が{$insuranceCount}社";
        }
        if (!empty($supportParts)) {
            $sentences[] = implode('、', $supportParts) . '掲載されています。';
        }

        if ($topTag) {
            $sentences[] = "掲載業者に最も多い強みは「{$topTag}」です。";
        }

        return implode('', $sentences);
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
