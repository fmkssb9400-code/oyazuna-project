<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Prefecture;
use App\Models\BuildingType;
use App\Models\ServiceCategory;
use App\Models\ServiceMethod;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::with(['prefectures', 'serviceMethods', 'buildingTypes', 'serviceCategories'])
            ->withCount('reviews')
            ->withAvg('reviews as average_rating', 'total_score');

        // Prefecture filter
        if ($prefecture = $request->get('prefecture')) {
            $prefectureName = $this->getPrefectureName($prefecture);
            if ($prefectureName) {
                $query->whereJsonContains('areas', $prefectureName);
            }
        }

        // Service filter (multiple services support)
        if ($service = $request->get('service')) {
            $services = array_filter(explode(',', $service));
            if (!empty($services)) {
                $query->where(function ($q) use ($services) {
                    foreach ($services as $svc) {
                        $q->orWhereJsonContains('service_categories', $svc);
                    }
                });
            }
        }

        // Search filter
        if ($searchTerm = $request->get('search')) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('service_areas', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('performance_summary', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Sorting
        $sort = $request->get('sort', 'recommend');
        switch ($sort) {
            case 'safety':
                $query->orderByDesc('safety_score')->orderByDesc('rank_score');
                break;
            case 'performance':
            case 'result':
                $query->orderByDesc('performance_score')->orderByDesc('rank_score');
                break;
            case 'reviews':
                $query->orderByDesc('average_rating')->orderByDesc('reviews_count')->orderByDesc('rank_score');
                break;
            case 'recommend':
            default:
                $query->orderByDesc('recommend_score')->orderByDesc('rank_score')->orderByDesc('id');
                break;
        }

        $companies = $query->paginate(12);

        // Pass current filters to view
        $prefectureFilter = $request->get('prefecture');
        $serviceFilter = $request->get('service');
        $searchTerm = $request->get('search');
        $activeSort = $sort;

        return view('companies.index', compact(
            'companies', 
            'prefectureFilter',
            'serviceFilter',
            'searchTerm',
            'activeSort'
        ));
    }

    private function getPrefectureName($slug)
    {
        $prefectureMapping = [
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

        return $prefectureMapping[$slug] ?? null;
    }


    public function show(Company $company)
    {
        $company->load(['prefectures', 'serviceMethods', 'buildingTypes', 'serviceCategories', 'assets'])
            ->loadCount('reviews')
            ->loadAvg('reviews as average_rating', 'total_score');
        
        // 公開済み記事を取得
        $articles = $company->articles()
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->orderBy('published_at', 'desc')
            ->get();
        
        // 口コミを取得（最新3件）
        $reviews = $company->reviews()
            ->published()
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();
        
        return view('companies.show', compact('company', 'articles', 'reviews'));
    }

    public function reviews(Request $request, Company $company)
    {
        $sort = $request->get('sort', 'newest');
        
        $reviewsQuery = $company->reviews()->published();
        
        // Apply sorting
        switch ($sort) {
            case 'highest':
                $reviewsQuery->orderByDesc('total_score')->orderByDesc('created_at');
                break;
            case 'lowest':
                $reviewsQuery->orderBy('total_score')->orderByDesc('created_at');
                break;
            case 'oldest':
                $reviewsQuery->orderBy('created_at');
                break;
            case 'newest':
            default:
                $reviewsQuery->orderByDesc('created_at');
                break;
        }
        
        $reviews = $reviewsQuery->paginate(10);
        
        // Calculate statistics
        $totalReviews = $company->reviews()->published()->count();
        $averageRating = $company->reviews()->published()->avg('total_score') ?: 0;
        
        // Rating distribution
        $ratingCounts = [];
        for ($i = 1; $i <= 5; $i++) {
            $ratingCounts[$i] = $company->reviews()
                ->published()
                ->where('total_score', '>=', $i - 0.5)
                ->where('total_score', '<', $i + 0.5)
                ->count();
        }
        
        return view('companies.reviews', compact(
            'company', 
            'reviews', 
            'totalReviews', 
            'averageRating', 
            'ratingCounts',
            'sort'
        ));
    }
}
