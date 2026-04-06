<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Review;

class ReviewController extends Controller
{
    public function selectCompany(Request $request)
    {
        $search = $request->get('search');
        
        $query = Company::published()
            ->withCount('reviews')
            ->withAvg('reviews as average_rating', 'total_score');
            
        if ($search) {
            $query->search($search);
        }
        
        $companies = $query->paginate(12);
        
        return view('reviews.select-company', compact('companies', 'search'));
    }
    
    public function create(Company $company)
    {
        return view('reviews.create', compact('company'));
    }
    
    public function store(Request $request, Company $company)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'reviewer_name' => 'required|string|max:255',
            'service_category' => 'required|string|max:255',
            'building_type' => 'nullable|string|max:255',
            'project_scale' => 'nullable|string|max:255',
            'usage_period' => 'nullable|string|max:255',
            'continue_request' => 'nullable|string|max:255',
            'good_points' => 'required|string|min:10',
            'improvement_points' => 'nullable|string',
            'service_quality' => 'required|integer|between:1,5',
            'staff_response' => 'required|integer|between:1,5',
            'value_for_money' => 'required|integer|between:1,5',
            'would_use_again' => 'required|integer|between:1,5',
        ]);
        
        Review::create([
            'company_id' => $company->id,
            'reviewer_name' => $request->reviewer_name,
            'company_name' => $request->company_name,
            'service_category' => $request->service_category,
            'building_type' => $request->building_type,
            'project_scale' => $request->project_scale,
            'usage_period' => $request->usage_period,
            'continue_request' => $request->continue_request,
            'good_points' => $request->good_points,
            'improvement_points' => $request->improvement_points,
            'service_quality' => $request->service_quality,
            'staff_response' => $request->staff_response,
            'value_for_money' => $request->value_for_money,
            'would_use_again' => $request->would_use_again,
            'status' => 'published', // Auto-approve reviews
        ]);
        
        return redirect()->route('reviews.complete', $company)
            ->with('success', '口コミを投稿しました。ありがとうございます！');
    }
    
    public function complete(Company $company)
    {
        return view('reviews.complete', compact('company'));
    }
    
}
