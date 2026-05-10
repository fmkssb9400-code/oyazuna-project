<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuoteSubmission;
use App\Models\Prefecture;
use App\Services\RecommendedItemsService;
use Illuminate\Support\Facades\Storage;

class QuoteDataController extends Controller
{
    public function index()
    {
        $quoteSubmissions = QuoteSubmission::where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        
        // おすすめ記事をホームページと同じ方法で取得
        $recommendedItemsService = new RecommendedItemsService();
        $featuredArticles = $recommendedItemsService->getRecommendedItems(8);
        
        return view('quote-data.index', compact('quoteSubmissions', 'featuredArticles'));
    }

    public function create()
    {
        $prefectures = Prefecture::all();
        
        return view('quote-data.create', compact('prefectures'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // 必須項目
            'work_type' => 'required|string|in:' . implode(',', array_keys(QuoteSubmission::WORK_TYPES)),
            'prefecture' => 'required|string',
            'comment' => 'required|string|max:1000',
            'quote_images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB
            
            // 任意項目
            'building_floors' => 'nullable|integer|min:1',
            'order_status' => 'nullable|string|in:' . implode(',', array_keys(QuoteSubmission::ORDER_STATUSES)),
            'quote_date' => 'nullable|date',
        ]);

        // 画像ファイルを保存
        $imagePaths = [];
        if ($request->hasFile('quote_images')) {
            foreach ($request->file('quote_images') as $image) {
                $path = $image->store('quote-submissions', 'public');
                $imagePaths[] = $path;
            }
        }

        // データベースに保存
        QuoteSubmission::create([
            'work_type' => $validated['work_type'],
            'prefecture' => $validated['prefecture'],
            'comment' => $validated['comment'],
            'images' => $imagePaths,
            'building_floors' => $validated['building_floors'] ?? null,
            'order_status' => $validated['order_status'] ?? null,
            'quote_date' => $validated['quote_date'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('quote-data.complete');
    }

    public function complete()
    {
        return view('quote-data.complete');
    }
}
