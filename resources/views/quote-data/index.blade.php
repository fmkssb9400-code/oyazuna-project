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
                    <h1 class="text-3xl font-bold text-gray-900 mb-8 flex items-center">
                        <img src="{{ asset('images/estimate.png') }}" alt="見積もりデータ" class="w-10 h-10 mr-3">
                        見積もりデータ一覧
                    </h1>
                    
                    <!-- タブナビゲーション -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-lg overflow-hidden">
                        <div class="border-b border-gray-200">
                            <nav class="flex overflow-x-auto scrollbar-hide">
                                <button class="service-tab flex-shrink-0 py-3 px-4 md:py-4 md:px-6 text-xs md:text-sm font-medium text-center whitespace-nowrap border-b-2 border-blue-500 text-blue-600 bg-blue-50" data-service="window">
                                    窓ガラス清掃
                                </button>
                                <button class="service-tab flex-shrink-0 py-3 px-4 md:py-4 md:px-6 text-xs md:text-sm font-medium text-center whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-service="inspection">
                                    外壁調査
                                </button>
                                <button class="service-tab flex-shrink-0 py-3 px-4 md:py-4 md:px-6 text-xs md:text-sm font-medium text-center whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-service="repair">
                                    外壁補修
                                </button>
                                <button class="service-tab flex-shrink-0 py-3 px-4 md:py-4 md:px-6 text-xs md:text-sm font-medium text-center whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-service="painting">
                                    外壁塗装
                                </button>
                                <button class="service-tab flex-shrink-0 py-3 px-4 md:py-4 md:px-6 text-xs md:text-sm font-medium text-center whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-service="bird_control">
                                    鳥害対策
                                </button>
                                <button class="service-tab flex-shrink-0 py-3 px-4 md:py-4 md:px-6 text-xs md:text-sm font-medium text-center whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-service="sign">
                                    看板作業
                                </button>
                                <button class="service-tab flex-shrink-0 py-3 px-4 md:py-4 md:px-6 text-xs md:text-sm font-medium text-center whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-service="leak">
                                    雨漏り調査
                                </button>
                                <button class="service-tab flex-shrink-0 py-3 px-4 md:py-4 md:px-6 text-xs md:text-sm font-medium text-center whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-service="other">
                                    その他
                                </button>
                            </nav>
                        </div>

                        <!-- タブコンテンツ -->
                        <div class="p-4 md:p-8">
                            @php
                                $tabServices = [
                                    'window' => '窓ガラス清掃',
                                    'inspection' => '外壁調査', 
                                    'repair' => '外壁補修',
                                    'painting' => '外壁塗装',
                                    'bird_control' => '鳥害対策',
                                    'sign' => '看板作業',
                                    'leak' => '雨漏り調査',
                                    'other' => 'その他'
                                ];
                            @endphp
                            
                            @foreach($tabServices as $serviceKey => $serviceName)
                            <div id="tab-{{ $serviceKey }}" class="tab-content {{ $loop->first ? '' : 'hidden' }}">
                                <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-4 md:mb-6">{{ $serviceName }}の見積もりデータ</h3>
                                
                                <div class="space-y-6">
                                    @forelse($quoteData[$serviceKey] ?? [] as $quote)
                                    <div class="bg-white border border-gray-300 rounded-lg shadow-sm">
                                        <div class="bg-gray-100 px-4 md:px-6 py-3 border-b border-gray-200">
                                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 md:gap-4 text-xs md:text-sm">
                                                <div><span class="font-semibold">日付:</span> {{ $quote->quote_date?->format('Y/n/j') ?? '未設定' }}</div>
                                                <div><span class="font-semibold">階数:</span> {{ $quote->floor_count ?? '-' }}階</div>
                                                <div><span class="font-semibold">金額:</span> {{ number_format($quote->total_amount) }}円</div>
                                            </div>
                                        </div>
                                        <div class="p-4 md:p-6">
                                            @if($quote->quote_items)
                                            <div class="overflow-x-auto -mx-4 md:mx-0">
                                                <table class="min-w-full text-xs md:text-sm">
                                                    <thead class="bg-green-100">
                                                        <tr>
                                                            <th class="px-2 md:px-4 py-2 text-left font-medium text-gray-700">項目名</th>
                                                            <th class="px-1 md:px-4 py-2 text-center font-medium text-gray-700">単位</th>
                                                            <th class="px-1 md:px-4 py-2 text-center font-medium text-gray-700">数量</th>
                                                            <th class="px-1 md:px-4 py-2 text-center font-medium text-gray-700">単価/円</th>
                                                            <th class="px-1 md:px-4 py-2 text-center font-medium text-gray-700">回/年</th>
                                                            <th class="px-2 md:px-4 py-2 text-center font-medium text-gray-700">金額/円</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-200">
                                                        @foreach($quote->quote_items as $item)
                                                            @if(isset($item['items']))
                                                                <!-- Group header -->
                                                                <tr class="bg-gray-50">
                                                                    <td class="px-2 md:px-4 py-2 text-xs md:text-sm font-medium">{{ $item['name'] }}</td>
                                                                    <td class="px-2 md:px-4 py-2 text-center text-xs md:text-sm"></td>
                                                                    <td class="px-2 md:px-4 py-2 text-center text-xs md:text-sm"></td>
                                                                    <td class="px-2 md:px-4 py-2 text-center text-xs md:text-sm"></td>
                                                                    <td class="px-2 md:px-4 py-2 text-center text-xs md:text-sm"></td>
                                                                    <td class="px-2 md:px-4 py-2 text-center text-xs md:text-sm">0</td>
                                                                </tr>
                                                                @foreach($item['items'] as $subItem)
                                                                <tr>
                                                                    <td class="px-2 md:px-4 py-2 text-xs md:text-sm pl-8">{{ $subItem['name'] }}</td>
                                                                    <td class="px-2 md:px-4 py-2 text-center text-xs md:text-sm">{{ $subItem['unit'] ?? '' }}</td>
                                                                    <td class="px-2 md:px-4 py-2 text-center text-xs md:text-sm">{{ number_format($subItem['quantity'] ?? 0, 2) }}</td>
                                                                    <td class="px-2 md:px-4 py-2 text-center text-xs md:text-sm">{{ number_format($subItem['unit_price'] ?? 0) }}</td>
                                                                    <td class="px-2 md:px-4 py-2 text-center text-xs md:text-sm">{{ $subItem['frequency'] ?? 1 }}</td>
                                                                    <td class="px-2 md:px-4 py-2 text-center text-xs md:text-sm text-blue-600 font-medium">{{ number_format($subItem['amount'] ?? 0) }}</td>
                                                                </tr>
                                                                @endforeach
                                                            @else
                                                                <!-- Regular item -->
                                                                <tr>
                                                                    <td class="px-2 md:px-4 py-2 text-xs md:text-sm">{{ $item['name'] }}</td>
                                                                    <td class="px-2 md:px-4 py-2 text-center text-xs md:text-sm">{{ $item['unit'] ?? '' }}</td>
                                                                    <td class="px-2 md:px-4 py-2 text-center text-xs md:text-sm">{{ number_format($item['quantity'] ?? 0, 2) }}</td>
                                                                    <td class="px-2 md:px-4 py-2 text-center text-xs md:text-sm">{{ number_format($item['unit_price'] ?? 0) }}</td>
                                                                    <td class="px-2 md:px-4 py-2 text-center text-xs md:text-sm">{{ $item['frequency'] ?? 1 }}</td>
                                                                    <td class="px-2 md:px-4 py-2 text-center text-xs md:text-sm text-blue-600 font-medium">{{ number_format($item['amount'] ?? 0) }}</td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            @endif
                                            <div class="mt-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                                                <div class="text-xs md:text-sm text-gray-600">
                                                    {{ $quote->prefecture }}
                                                    @if($quote->work_area)
                                                        | {{ $serviceKey === 'bird_control' || $serviceKey === 'leak' ? '調査' : ($serviceKey === 'sign' ? 'サイズ' : '施工') }}{{ $serviceKey === 'repair' && $quote->area_unit === 'm' ? '箇所' : '面積' }}: {{ number_format($quote->work_area) }}{{ $quote->area_unit }}
                                                    @endif
                                                </div>
                                                <div class="text-sm md:text-lg font-bold text-blue-600">合計: ¥{{ number_format($quote->total_amount) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="text-center text-gray-500 py-8">
                                        {{ $serviceName }}の見積もりデータはまだありません
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                            @endforeach
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

/* スクロールバーを隠す */
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

/* スマホでのテーブルセル調整 */
@media (max-width: 640px) {
    .min-w-full td {
        min-width: 0 !important;
        white-space: nowrap;
    }
    
    /* 項目名のセルは広めに */
    .min-w-full td:first-child {
        min-width: 120px !important;
        white-space: normal;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.service-tab');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const service = this.getAttribute('data-service');
            
            // タブのアクティブ状態を変更
            tabs.forEach(t => {
                t.classList.remove('border-blue-500', 'text-blue-600', 'bg-blue-50');
                t.classList.add('border-transparent', 'text-gray-500');
            });
            
            this.classList.remove('border-transparent', 'text-gray-500');
            this.classList.add('border-blue-500', 'text-blue-600', 'bg-blue-50');
            
            // コンテンツの表示を変更
            contents.forEach(content => {
                content.classList.add('hidden');
            });
            
            const targetContent = document.getElementById(`tab-${service}`);
            if (targetContent) {
                targetContent.classList.remove('hidden');
            }
        });
    });
});
</script>
@endsection