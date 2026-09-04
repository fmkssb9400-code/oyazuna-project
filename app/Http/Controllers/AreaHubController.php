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

        $count = $baseQuery()->count();

        // 掲載企業が少なすぎる組み合わせは薄いページになるため公開しない（404）。
        abort_if($count < self::MIN_COMPANIES, 404);

        $topCompanies = $baseQuery()->take(10)->get();
        $companies = $baseQuery()->paginate(10)->withQueryString();

        $ratings = $topCompanies->pluck('average_rating')->filter(fn ($v) => $v !== null);
        $averageRating = $ratings->isNotEmpty() ? round($ratings->avg(), 1) : null;

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
            'topCompanies' => $topCompanies,
            'companies' => $companies,
            'siblingsByArea' => $siblingsByArea,
            'siblingsByHub' => $siblingsByHub,
        ]);
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
