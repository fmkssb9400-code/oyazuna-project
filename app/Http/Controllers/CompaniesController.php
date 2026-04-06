<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Article;
use App\Services\RecommendedItemsService;

class CompaniesController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'recommend');
        
        // Get search term
        $searchTerm = $request->get('search');
        
        // Get prefecture filter
        $prefectureFilter = $request->get('prefecture');
        
        // Get service filter
        $serviceFilter = $request->get('service');
        
        $filters = [];
        if ($prefectureFilter) {
            $filters['prefecture'] = $prefectureFilter;
        }
        
        // Start with forQuote scope which includes published() check
        if (!empty($filters)) {
            $query = Company::forQuote($filters);
        } else {
            $query = Company::published();
        }
        
        // Apply service filter if specified (but only if no search term)
        if ($serviceFilter && !$searchTerm) {
            switch ($serviceFilter) {
                case 'window':
                case 'inspection':
                case 'repair':
                case 'painting':
                case 'bird_control':
                case 'sign':
                case 'leak_inspection':
                case 'other':
                    $query->whereJsonContains('service_categories', $serviceFilter);
                    break;
            }
        }
        
        // Apply search filter if search term is provided (ignore service filter when searching)
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('slug', 'like', '%' . $searchTerm . '%')
                  ->orWhere('address_text', 'like', '%' . $searchTerm . '%')
                  ->orWhere('service_areas', 'like', '%' . $searchTerm . '%')
                  ->orWhere('areas', 'like', '%' . $searchTerm . '%')
                  ->orWhere('service_categories', 'like', '%' . $searchTerm . '%')
                  ->orWhere('strength_tags', 'like', '%' . $searchTerm . '%')
                  ->orWhere('achievements_summary', 'like', '%' . $searchTerm . '%')
                  ->orWhere('performance_summary', 'like', '%' . $searchTerm . '%')
                  ->orWhere('safety_items', 'like', '%' . $searchTerm . '%')
                  ->orWhere('article_content', 'like', '%' . $searchTerm . '%')
                  ->orWhere('tags', 'like', '%' . $searchTerm . '%');
            });
        }
        
        $query = $query->withCount('reviews')
            ->withAvg('reviews as average_rating', 'total_score');

        // Sort companies based on sort parameter - star rating (high to low)
        switch ($sort) {
            case 'safety':
                $query->orderByDesc('safety_score');
                break;
            case 'performance':
                $query->orderByDesc('performance_score');
                break;
            case 'reviews':
                // Sort by review count (most to least), then by average rating
                $query->orderByDesc('reviews_count')
                      ->orderByDesc('average_rating');
                break;
            case 'recommend':
            default:
                $query->orderByDesc('recommend_score');
                break;
        }

        // Append query parameters to pagination links
        $companies = $query->paginate(12)->withQueryString();

        // Featured articles and static pages from database
        $recommendedItemsService = new RecommendedItemsService();
        $featuredArticles = $recommendedItemsService->getRecommendedItems(8);

        return view('companies.index', [
            'companies' => $companies,
            'activeSort' => $sort,
            'featuredArticles' => $featuredArticles,
            'serviceFilter' => $serviceFilter,
            'searchTerm' => $searchTerm
        ]);
    }

    public function getCompaniesBySort(Request $request, $sort)
    {
        // Get search term
        $searchTerm = $request->get('search');
        
        // Get prefecture filter
        $prefectureFilter = $request->get('prefecture');
        
        // Get service filter
        $serviceFilter = $request->get('service');
        
        $filters = [];
        if ($prefectureFilter) {
            $filters['prefecture'] = $prefectureFilter;
        }
        
        // Start with forQuote scope which includes published() check
        if (!empty($filters)) {
            $query = Company::forQuote($filters);
        } else {
            $query = Company::published();
        }
        
        // Apply service filter if specified (but only if no search term)
        if ($serviceFilter && !$searchTerm) {
            switch ($serviceFilter) {
                case 'window':
                case 'inspection':
                case 'repair':
                case 'painting':
                case 'bird_control':
                case 'sign':
                case 'leak_inspection':
                case 'other':
                    $query->whereJsonContains('service_categories', $serviceFilter);
                    break;
            }
        }
        
        // Apply search filter if search term is provided (ignore service filter when searching)
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('slug', 'like', '%' . $searchTerm . '%')
                  ->orWhere('address_text', 'like', '%' . $searchTerm . '%')
                  ->orWhere('service_areas', 'like', '%' . $searchTerm . '%')
                  ->orWhere('areas', 'like', '%' . $searchTerm . '%')
                  ->orWhere('service_categories', 'like', '%' . $searchTerm . '%')
                  ->orWhere('strength_tags', 'like', '%' . $searchTerm . '%')
                  ->orWhere('achievements_summary', 'like', '%' . $searchTerm . '%')
                  ->orWhere('performance_summary', 'like', '%' . $searchTerm . '%')
                  ->orWhere('safety_items', 'like', '%' . $searchTerm . '%')
                  ->orWhere('article_content', 'like', '%' . $searchTerm . '%')
                  ->orWhere('tags', 'like', '%' . $searchTerm . '%');
            });
        }
        
        $query = $query->withCount('reviews')
            ->withAvg('reviews as average_rating', 'total_score');

        // Sort companies based on sort parameter - star rating (high to low)
        switch ($sort) {
            case 'safety':
                $query->orderByDesc('safety_score');
                break;
            case 'performance':
                $query->orderByDesc('performance_score');
                break;
            case 'reviews':
                // Sort by review count (most to least), then by average rating
                $query->orderByDesc('reviews_count')
                      ->orderByDesc('average_rating');
                break;
            case 'recommend':
            default:
                $query->orderByDesc('recommend_score');
                break;
        }

        $companies = $query->paginate(12);

        return response()->json([
            'html' => view('companies.companies-list', ['companies' => $companies])->render(),
            'pagination' => view('components.pagination', ['companies' => $companies])->render()
        ]);
    }
}