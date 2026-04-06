@props(['articles' => []])

<!-- 広告枠 -->
@php
    $siteSettings = app(\App\Models\SiteSetting::class)->getSettings();
@endphp

@if(!empty($siteSettings['sidebar_ad_code']))
<div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
    <div class="p-4">
        <div class="text-xs text-gray-400 mb-2 text-center">広告</div>
        <div class="ad-container">
            {!! $siteSettings['sidebar_ad_code'] !!}
        </div>
    </div>
</div>
@endif

<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-6">おすすめ記事</h3>
        
        <div class="space-y-4">
            @foreach($articles as $article)
            <a href="{{ $article['url'] }}" class="block group hover:bg-gray-50 rounded-lg p-3 transition-colors">
                <div class="flex gap-3">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-12 rounded bg-blue-100 flex items-center justify-center overflow-hidden">
                            @if(!empty($article['featured_image_url']))
                                <img src="{{ $article['featured_image_url'] }}" alt="{{ $article['title'] }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                </svg>
                            @endif
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-medium text-gray-900 line-clamp-2 group-hover:text-blue-600 transition-colors">
                            {{ $article['title'] }}
                        </h4>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>

<style>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* 広告コンテナのスタイル */
.ad-container {
    text-align: center;
    overflow: hidden;
}

.ad-container * {
    max-width: 100% !important;
    height: auto !important;
}

/* レスポンシブ広告対応 */
@media (max-width: 768px) {
    .ad-container {
        font-size: 14px;
    }
}
</style>