<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Company;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(HubController $hub, AreaController $area, AreaHubController $areaHub): Response
    {
        $urls = [];

        // 固定ページ
        $urls[] = ['loc' => route('home'), 'changefreq' => 'daily', 'priority' => '1.0'];
        $urls[] = ['loc' => route('companies.index'), 'changefreq' => 'daily', 'priority' => '0.9'];
        $urls[] = ['loc' => route('news.index'), 'changefreq' => 'daily', 'priority' => '0.6'];
        $urls[] = ['loc' => route('guide.window-cleaning-price'), 'changefreq' => 'monthly', 'priority' => '0.6'];
        $urls[] = ['loc' => route('guide.window-cleaning-contractor-selection'), 'changefreq' => 'monthly', 'priority' => '0.6'];
        $urls[] = ['loc' => route('guide.exterior-wall-painting-pricing'), 'changefreq' => 'monthly', 'priority' => '0.6'];
        $urls[] = ['loc' => route('guide.exterior-wall-painting-contractor-selection'), 'changefreq' => 'monthly', 'priority' => '0.6'];

        // ハブページ（工法・条件軸）
        foreach ($hub->slugs() as $slug) {
            $urls[] = ['loc' => route('hub.category', $slug), 'changefreq' => 'weekly', 'priority' => '0.8'];
        }

        // エリアページ（都道府県軸）
        foreach ($area->slugs() as $slug) {
            $urls[] = ['loc' => route('area.show', $slug), 'changefreq' => 'weekly', 'priority' => '0.8'];
        }

        // 都道府県×工法の掛け合わせページ（企業数の閾値を満たすものだけ自動的に含まれる）
        foreach ($areaHub->qualifyingCombinations($area, $hub) as $combination) {
            $urls[] = [
                'loc' => route('area.hub.show', [$combination['areaSlug'], $combination['hubSlug']]),
                'changefreq' => 'weekly',
                'priority' => '0.75',
            ];
        }

        // 掲載企業ページ
        Company::published()
            ->select('slug', 'updated_at')
            ->orderBy('id')
            ->get()
            ->each(function (Company $company) use (&$urls) {
                $urls[] = [
                    'loc' => route('companies.show', $company->slug),
                    'lastmod' => optional($company->updated_at)->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                ];
            });

        // 記事ページ
        Article::published()
            ->select('slug', 'updated_at')
            ->orderBy('id')
            ->get()
            ->each(function (Article $article) use (&$urls) {
                $urls[] = [
                    'loc' => route('news.show', $article->slug),
                    'lastmod' => optional($article->updated_at)->toAtomString(),
                    'changefreq' => 'monthly',
                    'priority' => '0.6',
                ];
            });

        $xml = view('sitemap.index', ['urls' => $urls])->render();

        return response($xml, 200)->header('Content-Type', 'text/xml; charset=UTF-8');
    }
}
