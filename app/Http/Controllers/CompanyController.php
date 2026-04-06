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
        $filters = $request->only([
            'prefecture_id',
            'prefecture',
            'building_type_id', 
            'floors', 
            'service_category_id', 
            'preferred_service_method_id', 
            'emergency'
        ]);

        $query = Company::forQuote($filters)
            ->with(['prefectures', 'serviceMethods', 'buildingTypes', 'serviceCategories'])
            ->withCount('reviews')
            ->withAvg('reviews as average_rating', 'total_score');

        // Sort by recommended (rank_score desc, emergency for urgent, max_floor desc)
        if ($request->get('sort') === 'height') {
            $query->orderByDesc('max_floor')->orderByDesc('rank_score');
        } else {
            $query->orderByDesc('rank_score');
            if (isset($filters['emergency']) && $filters['emergency']) {
                $query->orderByDesc('emergency_supported');
            }
            $query->orderByDesc('max_floor')->orderByDesc('id');
        }

        $companies = $query->paginate(12);

        $prefectures = Prefecture::all();
        $buildingTypes = BuildingType::all();
        $serviceCategories = ServiceCategory::all();
        $serviceMethods = ServiceMethod::all();

        return view('companies.index', compact(
            'companies', 
            'prefectures', 
            'buildingTypes', 
            'serviceCategories', 
            'serviceMethods',
            'filters'
        ));
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
