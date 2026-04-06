<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prefecture;
use App\Models\BuildingType;
use App\Models\ServiceCategory;
use App\Models\ServiceMethod;
use App\Models\SiteSetting;
use App\Models\Company;
use App\Models\Article;
use App\Services\RecommendedItemsService;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'recommend');
        
        // Get prefecture filter
        $prefectureFilter = $request->get('prefecture');
        
        // Get service filter
        $serviceFilter = $request->get('service');
        $filters = [];
        if ($prefectureFilter) {
            $filters['prefecture'] = $prefectureFilter;
        }
        
        $prefectures = Prefecture::all();
        $buildingTypes = BuildingType::all();
        $serviceCategories = ServiceCategory::all();
        $serviceMethods = ServiceMethod::all();

        $heroImage = SiteSetting::get('hero_image');
        $heroTitle = SiteSetting::get('hero_title', '高所窓ガラス清掃業者を');
        $heroSubtitle = SiteSetting::get('hero_subtitle', 'まとめて比較・一括見積もり');
        $heroDescription = SiteSetting::get('hero_description', '専門業者へ安全に直接つなぐプラットフォーム');
        $siteLogo = SiteSetting::get('site_logo');
        $siteName = SiteSetting::get('site_name', 'オヤズナ');

        // Featured companies from database with filtering and sorting
        $query = Company::published()->where('is_featured', true);
        
        // Apply prefecture filter if specified
        if ($prefectureFilter) {
            // Convert prefecture slug to prefecture name for filtering
            $prefectureNames = [
                'hokkaido' => '北海道', 'aomori' => '青森県', 'iwate' => '岩手県', 'miyagi' => '宮城県',
                'akita' => '秋田県', 'yamagata' => '山形県', 'fukushima' => '福島県', 'ibaraki' => '茨城県',
                'tochigi' => '栃木県', 'gunma' => '群馬県', 'saitama' => '埼玉県', 'chiba' => '千葉県',
                'tokyo' => '東京都', 'kanagawa' => '神奈川県', 'niigata' => '新潟県', 'toyama' => '富山県',
                'ishikawa' => '石川県', 'fukui' => '福井県', 'yamanashi' => '山梨県', 'nagano' => '長野県',
                'gifu' => '岐阜県', 'shizuoka' => '静岡県', 'aichi' => '愛知県', 'mie' => '三重県',
                'shiga' => '滋賀県', 'kyoto' => '京都府', 'osaka' => '大阪府', 'hyogo' => '兵庫県',
                'nara' => '奈良県', 'wakayama' => '和歌山県', 'tottori' => '鳥取県', 'shimane' => '島根県',
                'okayama' => '岡山県', 'hiroshima' => '広島県', 'yamaguchi' => '山口県', 'tokushima' => '徳島県',
                'kagawa' => '香川県', 'ehime' => '愛媛県', 'kochi' => '高知県', 'fukuoka' => '福岡県',
                'saga' => '佐賀県', 'nagasaki' => '長崎県', 'kumamoto' => '熊本県', 'oita' => '大分県',
                'miyazaki' => '宮崎県', 'kagoshima' => '鹿児島県', 'okinawa' => '沖縄県'
            ];
            
            $prefectureName = $prefectureNames[$prefectureFilter] ?? null;
            if ($prefectureName) {
                $query->whereJsonContains('areas', $prefectureName);
            }
        }
        
        // Apply service filter if specified
        if ($serviceFilter) {
            switch ($serviceFilter) {
                case 'window':
                case 'exterior':
                case 'inspection':
                case 'sign':
                case 'other':
                    $query->whereJsonContains('service_categories', $serviceFilter);
                    break;
            }
        }
        
        $query = $query->withCount('reviews')
            ->withAvg('reviews as average_rating', 'total_score');

        // ソート処理：項目の多い順（非null値の多い順）
        switch ($sort) {
            case 'safe':
                $query->orderByRaw('(CASE WHEN safety_score IS NOT NULL THEN 1 ELSE 0 END +
                                   CASE WHEN safety_items IS NOT NULL THEN 1 ELSE 0 END) DESC')
                    ->orderBy('safety_score', 'desc');
                break;

            case 'result':
                $query->orderByRaw('(CASE WHEN performance_score IS NOT NULL THEN 1 ELSE 0 END +
                                   CASE WHEN achievements_summary IS NOT NULL THEN 1 ELSE 0 END) DESC')
                    ->orderBy('performance_score', 'desc');
                break;

            case 'review':
                $query->orderBy('reviews_count', 'desc')
                    ->orderBy('average_rating', 'desc');
                break;

            default: // recommend
                $query->orderByRaw('(CASE WHEN recommend_score IS NOT NULL THEN 1 ELSE 0 END +
                                   CASE WHEN is_featured = 1 THEN 1 ELSE 0 END +
                                   CASE WHEN strength_tags IS NOT NULL THEN 1 ELSE 0 END) DESC')
                    ->orderBy('recommend_score', 'desc');
                break;
        }

        $companies = $query->limit(8)->get();

        // Featured articles and static pages from database
        $recommendedItemsService = new RecommendedItemsService();
        $featuredArticles = $recommendedItemsService->getRecommendedItems(8);

        // Get total company count
        $companyCount = Company::published()->count();

        // Get ranking data for each service category (top 3 companies)
        $rankingData = [
            'window' => $this->getServiceRanking('window'),
            'inspection' => $this->getServiceRanking('inspection'), 
            'repair' => $this->getServiceRanking('repair'),
            'painting' => $this->getServiceRanking('painting')
        ];

        return view('home.index', compact(
            'prefectures', 
            'buildingTypes', 
            'serviceCategories', 
            'serviceMethods',
            'heroImage',
            'heroTitle',
            'heroSubtitle', 
            'heroDescription',
            'companies',
            'featuredArticles',
            'siteLogo',
            'siteName',
            'sort',
            'prefectureFilter',
            'serviceFilter',
            'companyCount',
            'rankingData'
        ));
    }

    public function getCompaniesBySort($sort)
    {
        $companies = Company::featured()
            ->published()
            ->withCount('reviews')
            ->withAvg('reviews as average_rating', 'total_score');

        // Sort companies based on sort parameter - star rating (high to low)
        switch ($sort) {
            case 'recommend':
                $companies = $companies->orderByDesc('recommend_score');
                break;
            case 'safety':
                $companies = $companies->orderByDesc('safety_score');
                break;
            case 'performance':
                $companies = $companies->orderByDesc('performance_score');
                break;
            case 'reviews':
                // Sort by review count (most to least), then by average rating
                $companies = $companies->orderByDesc('reviews_count')
                          ->orderByDesc('average_rating');
                break;
            default:
                $companies = $companies->orderByDesc('recommend_score');
                break;
        }

        $companies = $companies->limit(8)->get();

        return response()->json([
            'html' => view('components.company-cards-list', ['companies' => $companies])->render()
        ]);
    }

    private function getServiceRanking($serviceType)
    {
        $serviceCategories = [
            'window' => ['窓ガラス清掃', 'window_cleaning'],
            'inspection' => ['外壁調査', 'wall_inspection', 'inspection'],
            'repair' => ['外壁補修', 'wall_repair', 'repair'],
            'painting' => ['外壁塗装', 'wall_painting', 'painting']
        ];

        $searchTerms = $serviceCategories[$serviceType] ?? [$serviceType];

        return Company::published()
            ->where(function ($query) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $query->orWhereJsonContains('service_categories', $term)
                          ->orWhere('services', 'LIKE', '%' . $term . '%')
                          ->orWhere('description', 'LIKE', '%' . $term . '%');
                }
            })
            ->withCount(['reviews' => function($query) { $query->published(); }])
            ->withAvg(['reviews as average_rating' => function($query) { $query->published(); }], 'total_score')
            ->orderByRaw('COALESCE(average_rating, 0) DESC')
            ->orderByDesc('reviews_count')
            ->orderByDesc('recommend_score')
            ->limit(3)
            ->get()
            ->map(function ($company) {
                return [
                    'name' => $company->name,
                    'logo_url' => $company->ranking_logo_url ?: $company->logo_url,
                    'average_rating' => $company->average_rating ?: 0,
                    'reviews_count' => $company->reviews_count ?: 0,
                    'url' => route('companies.show', $company->slug ?: $company->id),
                    'star_rating' => ($company->reviews_count > 0) ? min(5, max(1, round($company->average_rating))) : 0,
                    'has_reviews' => $company->reviews_count > 0
                ];
            });
    }
}
