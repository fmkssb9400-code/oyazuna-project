<!-- サイドバー -->
<aside class="space-y-6">
    <!-- サイドバー広告1 -->
    @php
        $siteSettings = app(\App\Models\SiteSetting::class)->getSettings();
    @endphp
    
    @if(!empty($siteSettings['sidebar_ad_1']))
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-4">
            <div class="text-xs text-gray-400 mb-2 text-center">広告</div>
            <div class="ad-container">
                {!! $siteSettings['sidebar_ad_1'] !!}
            </div>
        </div>
    </div>
    @endif
    
    <!-- お問い合わせバナー -->
    <div class="bg-orange-600 rounded-lg shadow-sm border border-gray-200 text-white">
        <div class="p-6 text-center">
            <h4 class="text-lg font-bold mb-2">お急ぎの方へ</h4>
            <p class="text-sm mb-4">最短で業者をお探しします</p>
            <a href="{{ route('quote.create') }}" class="block bg-white text-orange-600 px-4 py-2 rounded font-medium hover:bg-gray-50 transition-colors">
                専門業者に相談する
            </a>
        </div>
    </div>
    
    <!-- サイドバー広告2 -->
    @if(!empty($siteSettings['sidebar_ad_2']))
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-4">
            <div class="text-xs text-gray-400 mb-2 text-center">広告</div>
            <div class="ad-container">
                {!! $siteSettings['sidebar_ad_2'] !!}
            </div>
        </div>
    </div>
    @endif
    
    <!-- おすすめ記事 -->
    @if(isset($featuredArticles) && count($featuredArticles) > 0)
        <x-recommended-articles :articles="$featuredArticles" />
    @endif
    
    <!-- サイドバー広告3 -->
    @if(!empty($siteSettings['sidebar_ad_3']))
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-4">
            <div class="text-xs text-gray-400 mb-2 text-center">広告</div>
            <div class="ad-container">
                {!! $siteSettings['sidebar_ad_3'] !!}
            </div>
        </div>
    </div>
    @endif
</aside>

<style>
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