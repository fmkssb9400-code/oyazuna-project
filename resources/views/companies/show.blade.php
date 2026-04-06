@extends('layouts.app')

@section('title', $company->name . ' - 業者詳細 - オヤズナ')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6 md:py-8">
    <div class="mb-4">
        <a href="{{ route('companies.index') }}" class="text-blue-600 hover:underline text-sm md:text-base">&larr; 一覧に戻る</a>
    </div>
    
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-4 md:p-8">
            <!-- Header Section -->
            <div class="flex flex-col lg:flex-row gap-6 md:gap-8 mb-6 md:mb-8">
                <div class="flex-1">
                    <div class="flex items-start gap-3 md:gap-4 mb-4 md:mb-6">
                        @if($company->logo_url)
                            <img src="{{ $company->logo_url }}" alt="{{ $company->name }}のロゴ" 
                                 class="w-12 h-12 md:w-16 md:h-16 object-contain flex-shrink-0">
                        @else
                            @php
                                $logoAsset = $company->getLogoAsset();
                            @endphp
                            @if($logoAsset)
                                <img src="{{ Storage::url($logoAsset->path) }}" alt="{{ $company->name }}" 
                                     class="w-12 h-12 md:w-16 md:h-16 object-contain flex-shrink-0">
                            @endif
                        @endif
                        <div class="min-w-0">
                            <h1 class="text-xl md:text-3xl font-bold mb-1 md:mb-2 break-words">{{ $company->name }}</h1>
                            @if($company->official_url)
                                <a href="{{ $company->official_url }}" target="_blank" 
                                   class="text-blue-600 hover:underline text-xs md:text-sm break-all">
                                    公式サイトを見る →
                                </a>
                            @endif
                        </div>
                    </div>
                    
                    <!-- スコア表示 -->
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 md:gap-4 mb-4 md:mb-6">
                        @if($company->recommend_score > 0)
                            <div class="text-center p-2 md:p-3 bg-blue-50 rounded">
                                <div class="text-xs md:text-sm text-gray-600">おすすめ</div>
                                <div class="text-sm md:text-lg font-bold text-blue-600">
                                    ★{{ number_format($company->recommend_score, 1) }}
                                </div>
                            </div>
                        @endif
                        @if($company->safety_score > 0)
                            <div class="text-center p-2 md:p-3 bg-green-50 rounded">
                                <div class="text-xs md:text-sm text-gray-600">安全性</div>
                                <div class="text-sm md:text-lg font-bold text-green-600">
                                    ★{{ number_format($company->safety_score, 1) }}
                                </div>
                            </div>
                        @endif
                        @if($company->performance_score > 0)
                            <div class="text-center p-2 md:p-3 bg-purple-50 rounded">
                                <div class="text-xs md:text-sm text-gray-600">実績</div>
                                <div class="text-sm md:text-lg font-bold text-purple-600">
                                    ★{{ number_format($company->performance_score, 1) }}
                                </div>
                            </div>
                        @endif
                        @if($company->reviews_count > 0)
                            <a href="{{ route('companies.reviews', $company) }}" class="block text-center p-2 md:p-3 bg-orange-50 rounded hover:bg-orange-100 transition-colors duration-200">
                                <div class="text-xs md:text-sm text-gray-600">口コミ</div>
                                <div class="text-xs md:text-lg font-bold text-orange-600">
                                    ★{{ number_format($company->average_rating, 1) }} (<span class="hover:underline">{{ $company->reviews_count }}件</span>)
                                </div>
                            </a>
                        @endif
                    </div>

                    @if($company->description)
                        <div class="mb-6">
                            <div class="mb-3 p-3 bg-yellow-50 border-l-4 border-yellow-400">
                                <p class="text-sm text-yellow-800">※公式サイト・公開情報を基に当サイト基準で評価</p>
                            </div>
                            <h3 class="text-lg font-semibold mb-2">会社概要</h3>
                            <p class="text-gray-700 whitespace-pre-line">{{ $company->description }}</p>
                        </div>
                    @endif
                </div>
                
                <!-- Gallery -->
                @php
                    $galleryAssets = $company->getGalleryAssets();
                @endphp
                @if($galleryAssets->isNotEmpty())
                    <div class="lg:w-1/3">
                        <h3 class="text-lg font-semibold mb-4">実績写真</h3>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($galleryAssets as $asset)
                                <div class="aspect-square bg-gray-200 rounded overflow-hidden">
                                    <img src="{{ Storage::url($asset->path) }}" alt="{{ $asset->caption }}" 
                                         class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- タブナビゲーション -->
            <div class="mb-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8">
                        <button id="tab-company-info" 
                                class="tab-button active border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                                onclick="switchTab('company-info')">
                            会社情報
                        </button>
                        <button id="tab-reviews" 
                                class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                                onclick="switchTab('reviews')">
                            口コミ
                        </button>
                    </nav>
                </div>
            </div>

            <!-- タブコンテンツ -->
            <div id="tab-content-company-info" class="tab-content">
                <!-- 特徴・強みタグ -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-3">この業者の特徴・強み</h3>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $fixedTags = [
                                    '見積もり無料',
                                    'ロープアクセス対応', 
                                    'ブランコ対応',
                                    '高所作業車',
                                    'ゴンドラ対応',
                                    '定期作業',
                                    '口コミ評判',
                                    '事例',
                                    '損害賠償保険加入',
                                    '労災保険加入',
                                    '有資格者在籍',
                                    '法人対応',
                                    '土日対応',
                                    '夜間対応',
                                    '即日対応',
                                    'オンライン相談',
                                    'メール対応',
                                    'LINE対応'
                                ];
                                $companyTags = $company->tags;
                                if (is_string($companyTags)) {
                                    $companyTags = json_decode($companyTags, true) ?: explode(',', $companyTags);
                                }
                                $companyTags = is_array($companyTags) ? $companyTags : [];
                            @endphp
                            @foreach($fixedTags as $tag)
                                @php
                                    $isActive = in_array($tag, $companyTags);
                                    $tagClass = $isActive 
                                        ? 'px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm'
                                        : 'px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-sm';
                                @endphp
                                <span class="{{ $tagClass }}">{{ $tag }}</span>
                            @endforeach
                        </div>
                </div>

                <!-- サービス詳細 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <h3 class="text-lg font-semibold mb-3">対応エリア</h3>
                        <div class="text-gray-700">
                            @if($company->areas && count($company->areas) > 0)
                                {{ implode('、', $company->areas) }}
                            @else
                                {{ $company->prefectures->pluck('name')->implode('、') }}
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold mb-3">対応工法</h3>
                        <div class="space-y-1">
                            @if($company->rope_support)
                                <div class="flex items-center text-green-600">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    ロープアクセス対応
                                </div>
                            @endif
                            @if($company->branco_supported)
                                <div class="flex items-center text-green-600">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    ブランコ対応
                                </div>
                            @endif
                            @if($company->aerial_platform_supported)
                                <div class="flex items-center text-green-600">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    高所作業車
                                </div>
                            @endif
                            @if($company->gondola_supported)
                                <div class="flex items-center text-green-600">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    ゴンドラ対応
                                </div>
                            @endif
                        </div>
                    </div>

                    
                    <div>
                        <h3 class="text-lg font-semibold mb-3">緊急対応</h3>
                        <div class="text-gray-700">
                            @if($company->emergency_supported)
                                <span class="inline-flex items-center px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    対応可能
                                </span>
                            @else
                                <span class="text-gray-500">対応不可</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 実績・安全性 -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    @if($company->achievements_summary)
                        <div>
                            <h3 class="text-lg font-semibold mb-3">実績</h3>
                            <p class="text-gray-700">{{ $company->achievements_summary }}</p>
                        </div>
                    @endif

                    @if($company->safety_items && count($company->safety_items) > 0)
                        <div>
                            <h3 class="text-lg font-semibold mb-3">安全への取り組み</h3>
                            <div class="space-y-2">
                                @foreach($company->safety_items as $item)
                                    <div class="flex items-center text-green-600">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $item }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div id="tab-content-reviews" class="tab-content hidden">
                @if($reviews && $reviews->count() > 0)
                    <div class="space-y-6">
                        @foreach($reviews as $review)
                            <div class="bg-gray-50 rounded-lg p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
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
                                
                                <!-- 良かった点 -->
                                <div class="mb-4">
                                    <h4 class="font-medium text-gray-900 mb-2">良かった点</h4>
                                    <p class="text-gray-700 text-sm leading-relaxed">{{ $review->good_points }}</p>
                                </div>
                                
                                @if($review->improvement_points)
                                    <div>
                                        <h4 class="font-medium text-gray-900 mb-2">改善点</h4>
                                        <p class="text-gray-700 text-sm leading-relaxed">{{ $review->improvement_points }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                        
                        @if($company->reviews_count > 3)
                            <div class="text-center mt-6">
                                <a href="{{ route('companies.reviews', $company) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    すべての口コミを見る ({{ $company->reviews_count }}件)
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="text-gray-500 mb-4">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">口コミはまだありません</h3>
                        <p class="text-gray-500 mb-6">この業者の口コミを最初に投稿してみませんか？</p>
                        <a href="{{ route('reviews.create', $company) }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors inline-block">
                            口コミを投稿する
                        </a>
                    </div>
                @endif
            </div>

            <!-- 会社記事 -->
            @if($articles->count() > 0)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">この業者の記事</h3>
                    <div class="space-y-6">
                        @foreach($articles as $article)
                            <article class="bg-gray-50 rounded-lg p-6">
                                <!-- カスタムCSS出力 -->
                                @if($article->custom_css)
                                    <style>
                                        /* Article {{ $article->id }} Custom CSS */
                                        {!! $article->scoped_custom_css !!}
                                    </style>
                                @endif
                                
                                <div class="prose prose-gray max-w-none text-gray-700 article-content article-content-{{ $article->id }}">
                                    {!! $article->rendered_content !!}
                                </div>
                                <div class="mt-4 text-sm text-gray-500">
                                    公開日: {{ $article->published_at->format('Y年m月d日') }}
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- 記事コンテンツ -->
            @if($company->article_content)
                <div class="mb-8 bg-gray-50 rounded-lg p-6">
                    <div class="prose prose-lg max-w-none">
                        {!! nl2br(e($company->article_content)) !!}
                    </div>
                </div>
            @endif

            
            <!-- CTA Buttons -->
            <div class="border-t pt-6 md:pt-8">
                <div class="flex flex-col sm:flex-row gap-3 md:gap-4 justify-center">
                    <button onclick="addToWishlist({{ $company->id }}, '{{ $company->name }}')" 
                            class="bg-blue-600 text-white px-6 md:px-8 py-3 rounded-lg hover:bg-blue-700 text-sm md:text-base wishlist-toggle" 
                            data-company-id="{{ $company->id }}" data-company-name="{{ $company->name }}">
                        この業者を気になるリストに追加
                    </button>
                    <a href="{{ route('quote.create') }}" 
                       class="bg-orange-600 text-white px-6 md:px-8 py-3 rounded-lg hover:bg-orange-700 text-center text-sm md:text-base">
                        専門業者に相談する
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Wishlist Fixed Footer -->
<div id="wishlist-footer" class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-2xl transform translate-y-full transition-transform duration-300 z-50" style="display: none;">
    <div class="bg-gradient-to-r from-orange-50 to-red-50 border-t-4 border-orange-500">
        <div class="max-w-7xl mx-auto px-3 py-4 sm:px-6 sm:py-5">
            <div class="flex flex-col sm:flex-row items-center gap-3 sm:gap-4 sm:justify-between">
                <div class="flex items-center gap-3 sm:gap-4 flex-wrap justify-center sm:justify-start">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-orange-500 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-base sm:text-lg font-bold text-gray-800">
                                選択中: <span id="wishlist-count" class="text-orange-600">0</span>社
                            </div>
                            <div class="text-xs sm:text-sm text-gray-600">比較・見積もり依頼が可能です</div>
                        </div>
                    </div>
                    <button id="clear-wishlist" class="text-xs sm:text-sm text-gray-500 hover:text-gray-700 bg-white px-2 py-1 sm:px-3 sm:py-1 rounded-full border border-gray-300 hover:border-gray-400 transition-all duration-200 whitespace-nowrap">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        選択をクリア
                    </button>
                </div>
                <a href="{{ route('quote.create') }}" 
                   id="consult-button"
                   class="bg-orange-600 text-white px-4 py-3 sm:px-6 sm:py-4 rounded-lg sm:rounded-xl font-bold text-sm sm:text-base hover:bg-orange-700 transition-all duration-200 flex items-center gap-2 shadow-lg hover:shadow-xl transform hover:scale-105 whitespace-nowrap"
                   style="background: linear-gradient(to right, #ea580c, #dc2626) !important; color: white !important;">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.959 8.959 0 01-4.906-1.451c-.302-.163-.622-.35-.963-.589L4 20l1.729-3.131c-.27-.476-.547-.949-.826-1.448C3.639 13.644 3 11.904 3 12c0-4.418 3.582-8 8-8s8 3.582 8 8z"/>
                    </svg>
                    専門業者に相談する
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// タブ切り替え機能
function switchTab(tabName) {
    // すべてのタブボタンとコンテンツを非アクティブ化
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active');
        btn.classList.remove('border-blue-500', 'text-blue-600');
        btn.classList.add('border-transparent', 'text-gray-500');
    });
    
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // 選択されたタブをアクティブ化
    const activeButton = document.getElementById(`tab-${tabName}`);
    const activeContent = document.getElementById(`tab-content-${tabName}`);
    
    activeButton.classList.add('active');
    activeButton.classList.remove('border-transparent', 'text-gray-500');
    activeButton.classList.add('border-blue-500', 'text-blue-600');
    
    activeContent.classList.remove('hidden');
}

function addToCompare(companyId) {
    $.post('/compare/add/' + companyId, {
        _token: $('meta[name="csrf-token"]').attr('content')
    }).done(function(data) {
        if (data.success) {
            $('#compare-count').text(data.count);
            alert('比較に追加しました');
        }
    }).fail(function() {
        alert('エラーが発生しました');
    });
}

function addToWishlist(companyId, companyName) {
    // Use the same logic as other pages
    toggleWishlist(companyId, companyName);
}

function updateWishlistButton(companyId) {
    let wishlist = JSON.parse(localStorage.getItem('companyWishlist') || '[]');
    const button = document.querySelector(`[data-company-id="${companyId}"]`);
    const isInWishlist = wishlist.some(item => item.id == companyId);
    
    if (isInWishlist) {
        button.textContent = '気になるリストから削除';
        button.className = button.className.replace('bg-blue-600 hover:bg-blue-700', 'bg-red-600 hover:bg-red-700');
    } else {
        button.textContent = 'この業者を気になるリストに追加';
        button.className = button.className.replace('bg-red-600 hover:bg-red-700', 'bg-blue-600 hover:bg-blue-700');
    }
}

// Global wishlist management functions (using companyWishlist key to match other pages)
function toggleWishlist(companyId, companyName) {
    let wishlist = JSON.parse(localStorage.getItem('companyWishlist') || '[]');
    const existingIndex = wishlist.findIndex(item => item.id == companyId);
    
    if (existingIndex > -1) {
        wishlist.splice(existingIndex, 1);
        alert('気になるリストから削除しました');
    } else {
        wishlist.push({
            id: companyId,
            name: companyName
        });
        alert('気になるリストに追加しました');
    }
    
    localStorage.setItem('companyWishlist', JSON.stringify(wishlist));
    updateWishlistFooter();
    updateWishlistButton(companyId);
}

function updateWishlistFooter() {
    let wishlist = JSON.parse(localStorage.getItem('companyWishlist') || '[]');
    const footer = document.getElementById('wishlist-footer');
    const countElement = document.getElementById('wishlist-count');
    
    if (wishlist.length > 0) {
        countElement.textContent = wishlist.length;
        footer.style.display = 'block';
        footer.style.transform = 'translateY(0)';
    } else {
        footer.style.transform = 'translateY(100%)';
        setTimeout(() => {
            footer.style.display = 'none';
        }, 300);
    }
}

function clearWishlist() {
    localStorage.removeItem('companyWishlist');
    updateWishlistFooter();
    const companyId = {{ $company->id }};
    updateWishlistButton(companyId);
    alert('選択をクリアしました');
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    const companyId = {{ $company->id }};
    updateWishlistButton(companyId);
    updateWishlistFooter();
    
    // Clear wishlist button
    document.getElementById('clear-wishlist')?.addEventListener('click', clearWishlist);
    
    // デフォルトで会社情報タブをアクティブにする
    switchTab('company-info');
});
</script>
@endsection