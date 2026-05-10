@extends('layouts.app')

@section('title', '見積もりデータ一覧 - オヤズナ | 高所ロープ作業の見積もり・相場データベース')

@section('content')
<div class="min-h-screen bg-blue-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Main Content with Sidebar -->
        <div class="max-w-7xl mx-auto px-4">
            <div class="lg:grid lg:grid-cols-3 lg:gap-8">
                <!-- 左：メインコンテンツ -->
                <div class="lg:col-span-2 space-y-6">
                    <h1 class="text-3xl font-bold text-gray-900 mb-8">見積もりデータ一覧</h1>
                    
                    <!-- コンテンツカード -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-lg p-8">
                        <div class="text-center py-16">
                            <div class="mb-6">
                                <svg class="w-20 h-20 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900 mb-3">見積もりデータ</h2>
                            <p class="text-gray-600 max-w-md mx-auto">
                                高所ロープ作業の見積もりデータをこちらでご確認いただけます。
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- 右：サイドバー -->
                <aside class="mt-10 lg:mt-0 space-y-4">
                    <!-- お問い合わせフォーム -->
                    <div class="bg-gray-500 rounded-lg shadow text-white">
                        <div class="p-6">
                            <h4 class="text-lg font-bold mb-2 text-center">お急ぎの方へ</h4>
                            <p class="text-sm mb-4 text-center">最短で業者をお探しします</p>
                            
                            <form action="{{ route('quote.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <input type="text" 
                                           name="name" 
                                           placeholder="お名前" 
                                           required
                                           class="w-full px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent">
                                </div>
                                
                                <div>
                                    <input type="tel" 
                                           name="phone" 
                                           placeholder="電話番号" 
                                           required
                                           class="w-full px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent">
                                </div>
                                
                                <div>
                                    <select name="service_type" 
                                            required
                                            class="w-full px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent">
                                        <option value="">サービスを選択</option>
                                        <option value="window_cleaning">窓ガラス清掃</option>
                                        <option value="building_cleaning">ビル清掃</option>
                                        <option value="wall_painting">外壁塗装</option>
                                        <option value="roof_repair">屋根修理</option>
                                        <option value="sign_installation">看板設置</option>
                                        <option value="other">その他</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <textarea name="message" 
                                              placeholder="ご要望・詳細（任意）" 
                                              rows="3"
                                              class="w-full px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent resize-none"></textarea>
                                </div>
                                
                                <button type="submit" 
                                        class="glowing-button w-full bg-orange-600 text-white px-4 py-3 rounded-md font-bold hover:bg-orange-700 transition-colors">
                                    無料で相談する
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- おすすめ記事 -->
                    <div class="bg-white rounded-xl shadow p-6">
                        <h2 class="text-xl font-bold mb-6 text-blue-600">
                            おすすめ記事
                        </h2>

                        <div class="space-y-4">
                            @forelse($featuredArticles as $item)
                                <a href="{{ $item['url'] ?? '#' }}"
                                   class="flex gap-4 hover:opacity-80 transition-opacity">

                                    <div class="w-20 h-16 bg-blue-100 rounded flex items-center justify-center flex-shrink-0 overflow-hidden">
                                        @if(!empty($item['featured_image_url']))
                                            <img src="{{ $item['featured_image_url'] }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                            </svg>
                                        @endif
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-semibold text-gray-900 leading-tight line-clamp-2">
                                            {{ $item['title'] ?? '記事タイトル' }}
                                        </h3>
                                    </div>

                                </a>
                            @empty
                                <div class="text-center text-gray-500 text-sm py-8">
                                    おすすめ記事がありません
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- サイドバー広告1 -->
                    @php
                        $siteSettings = app(\App\Models\SiteSetting::class)->getSettings();
                    @endphp
                    
                    @if(!empty($siteSettings['sidebar_ad_1']))
                    <div class="bg-white rounded-lg shadow border border-gray-200">
                        <div class="p-4">
                            <div class="text-xs text-gray-400 mb-2 text-center">広告</div>
                            <div class="ad-container">
                                {!! $siteSettings['sidebar_ad_1'] !!}
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- サイドバー広告2 -->
                    @if(!empty($siteSettings['sidebar_ad_2']))
                    <div class="bg-white rounded-lg shadow border border-gray-200">
                        <div class="p-4">
                            <div class="text-xs text-gray-400 mb-2 text-center">広告</div>
                            <div class="ad-container">
                                {!! $siteSettings['sidebar_ad_2'] !!}
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- サイドバー広告3 -->
                    @if(!empty($siteSettings['sidebar_ad_3']))
                    <div class="bg-white rounded-lg shadow border border-gray-200">
                        <div class="p-4">
                            <div class="text-xs text-gray-400 mb-2 text-center">広告</div>
                            <div class="ad-container">
                                {!! $siteSettings['sidebar_ad_3'] !!}
                            </div>
                        </div>
                    </div>
                    @endif
                </aside>
            </div>
        </div>
    </div>
</div>

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

/* 記事タイトルの行制限 */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection