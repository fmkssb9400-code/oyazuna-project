@extends('layouts.app')

@section('title', $company->name . 'の口コミ一覧')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Company Reviews Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center gap-4 mb-4">
                <img src="{{ asset('images/building_kaisya_small.png') }}" alt="会社アイコン" class="w-12 h-12 object-contain">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $company->name }}の口コミ一覧</h1>
                    <p class="text-gray-600">{{ $company->name }}をご利用いただいた方からの口コミをご紹介します</p>
                </div>
            </div>
            
            <!-- Rating Summary -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="text-center">
                    <div class="text-4xl font-bold text-gray-900 mb-2">
                        {{ number_format($averageRating, 1) }}
                    </div>
                    <div class="flex justify-center items-center mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-6 h-6 {{ $i <= round($averageRating) ? 'text-yellow-400' : 'text-gray-300' }}" 
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <p class="text-gray-600">{{ $totalReviews }}件の口コミ</p>
                </div>
                
                <div class="space-y-2">
                    @for($rating = 5; $rating >= 1; $rating--)
                        <div class="flex items-center gap-2">
                            <span class="text-sm w-8">{{ $rating }}⭐</span>
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-yellow-400 h-2 rounded-full" 
                                     style="width: {{ $totalReviews > 0 ? ($ratingCounts[$rating] / $totalReviews) * 100 : 0 }}%"></div>
                            </div>
                            <span class="text-sm text-gray-600 w-8">{{ $ratingCounts[$rating] ?? 0 }}</span>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Sort and Filter -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-900">口コミ一覧</h2>
            <div class="flex items-center gap-2">
                <label class="text-sm text-gray-600">並び替え:</label>
                <select id="sortSelect" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                    <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>新着順</option>
                    <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>古い順</option>
                    <option value="highest" {{ $sort === 'highest' ? 'selected' : '' }}>評価が高い順</option>
                    <option value="lowest" {{ $sort === 'lowest' ? 'selected' : '' }}>評価が低い順</option>
                </select>
            </div>
        </div>

        <!-- Reviews List -->
        <div class="space-y-6">
            @forelse($reviews as $review)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <!-- User Icon -->
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">
                                    <span class="text-gray-400">利用したサービス:</span> {{ $review->service_category_ja }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            @if($review->total_score)
                                <div class="flex items-center gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= round($review->total_score) ? 'text-yellow-400' : 'text-gray-300' }}" 
                                             fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                    <span class="text-sm font-medium ml-1">{{ number_format($review->total_score, 1) }}</span>
                                </div>
                            @endif
                            <p class="text-xs text-gray-400 mt-1">{{ $review->created_at->format('Y年m月d日') }}</p>
                        </div>
                    </div>

                    <!-- Review Details -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4 text-sm">
                        @if($review->building_type)
                            <div>
                                <span class="text-gray-500">建物種別:</span>
                                <span class="font-medium">{{ $review->building_type_ja }}</span>
                            </div>
                        @endif
                        @if($review->project_scale)
                            <div>
                                <span class="text-gray-500">規模:</span>
                                <span class="font-medium">{{ $review->project_scale_ja }}</span>
                            </div>
                        @endif
                        @if($review->usage_period)
                            <div>
                                <span class="text-gray-500">期間:</span>
                                <span class="font-medium">{{ $review->usage_period_ja }}</span>
                            </div>
                        @endif
                        @if($review->continue_request)
                            <div>
                                <span class="text-gray-500">継続依頼:</span>
                                <span class="font-medium">{{ $review->continue_request_ja }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Rating Breakdown -->
                    @if($review->service_quality || $review->staff_response || $review->value_for_money || $review->would_use_again)
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
                            @if($review->service_quality)
                                <div class="text-center">
                                    <p class="text-xs text-gray-500 mb-1">サービス品質</p>
                                    <p class="font-medium">{{ $review->service_quality }}/5</p>
                                </div>
                            @endif
                            @if($review->staff_response)
                                <div class="text-center">
                                    <p class="text-xs text-gray-500 mb-1">スタッフ対応</p>
                                    <p class="font-medium">{{ $review->staff_response }}/5</p>
                                </div>
                            @endif
                            @if($review->value_for_money)
                                <div class="text-center">
                                    <p class="text-xs text-gray-500 mb-1">料金・コスパ</p>
                                    <p class="font-medium">{{ $review->value_for_money }}/5</p>
                                </div>
                            @endif
                            @if($review->would_use_again)
                                <div class="text-center">
                                    <p class="text-xs text-gray-500 mb-1">また利用したいか</p>
                                    <p class="font-medium">{{ $review->would_use_again }}/5</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Review Content -->
                    <div class="space-y-4">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">良かった点</h4>
                            <p class="text-gray-700 leading-relaxed">{{ $review->good_points }}</p>
                        </div>
                        
                        @if($review->improvement_points)
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">改善点</h4>
                                <p class="text-gray-700 leading-relaxed">{{ $review->improvement_points }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.959 8.959 0 01-4.906-1.452l-3.22 1.288a.75.75 0 01-.953-.953l1.288-3.22A8.959 8.959 0 013 12c0-4.418 3.582-8 8-8s8 3.582 8 8z"></path>
                    </svg>
                    <p class="text-gray-500 text-lg mb-4">まだ口コミがありません</p>
                    <a href="{{ route('reviews.create', $company) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        口コミを投稿する
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($reviews->hasPages())
            <div class="mt-8">
                {{ $reviews->appends(['sort' => $sort])->links() }}
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex justify-center gap-4 mt-8">
            <a href="{{ route('companies.show', $company) }}" 
               class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                {{ $company->name }}の詳細に戻る
            </a>
            <a href="{{ route('reviews.create', $company) }}" 
               class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                {{ $company->name }}の口コミを投稿する
            </a>
        </div>
    </div>
</div>

<script>
document.getElementById('sortSelect').addEventListener('change', function() {
    const url = new URL(window.location);
    url.searchParams.set('sort', this.value);
    window.location.href = url.toString();
});
</script>
@endsection