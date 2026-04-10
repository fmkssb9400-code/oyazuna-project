@extends('layouts.app')

@section('title', '専門業者一覧 - オヤズナ')

@section('content')
<div class="min-h-screen bg-blue-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">専門業者一覧</h1>
            <p class="text-lg text-gray-600">高所ロープ作業会社を比較・検索</p>
        </div>


        <!-- Search Form Section -->
        <div class="bg-gradient-to-br from-slate-50 to-blue-50 rounded-2xl border border-blue-100 p-5 mb-8 max-w-4xl mx-auto">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">業者検索</h3>
            </div>
            
            <!-- Prefecture Selection -->
            <div class="mb-6">
                <div class="mb-3">
                    <label for="prefecture-select" class="text-sm font-semibold text-gray-700">都道府県を選択</label>
                </div>
                <select id="prefecture-select" class="w-full px-4 md:px-6 py-3 md:py-4 pr-8 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white text-gray-700 text-base md:text-lg">
                    <option value="">全ての都道府県</option>
                    @php
                    $prefectureMapping = [
                        '北海道' => 'hokkaido', '青森県' => 'aomori', '岩手県' => 'iwate', '宮城県' => 'miyagi',
                        '秋田県' => 'akita', '山形県' => 'yamagata', '福島県' => 'fukushima', '茨城県' => 'ibaraki',
                        '栃木県' => 'tochigi', '群馬県' => 'gunma', '埼玉県' => 'saitama', '千葉県' => 'chiba',
                        '東京都' => 'tokyo', '神奈川県' => 'kanagawa', '新潟県' => 'niigata', '富山県' => 'toyama',
                        '石川県' => 'ishikawa', '福井県' => 'fukui', '山梨県' => 'yamanashi', '長野県' => 'nagano',
                        '岐阜県' => 'gifu', '静岡県' => 'shizuoka', '愛知県' => 'aichi', '三重県' => 'mie',
                        '滋賀県' => 'shiga', '京都府' => 'kyoto', '大阪府' => 'osaka', '兵庫県' => 'hyogo',
                        '奈良県' => 'nara', '和歌山県' => 'wakayama', '鳥取県' => 'tottori', '島根県' => 'shimane',
                        '岡山県' => 'okayama', '広島県' => 'hiroshima', '山口県' => 'yamaguchi', '徳島県' => 'tokushima',
                        '香川県' => 'kagawa', '愛媛県' => 'ehime', '高知県' => 'kochi', '福岡県' => 'fukuoka',
                        '佐賀県' => 'saga', '長崎県' => 'nagasaki', '熊本県' => 'kumamoto', '大分県' => 'oita',
                        '宮崎県' => 'miyazaki', '鹿児島県' => 'kagoshima', '沖縄県' => 'okinawa'
                    ];
                    $currentPrefecture = request()->get('prefecture');
                    @endphp
                    @foreach($prefectureMapping as $name => $slug)
                        <option value="{{ $slug }}" {{ $currentPrefecture === $slug ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Service Type Selection -->
            <div class="mb-6">
                <div class="mb-4">
                    <span class="text-sm font-semibold text-gray-700">サービス内容を選択</span>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3" id="service-options">
                    @php
                    $requestedServices = request()->get('service', '');
                    $selectedServices = $requestedServices ? explode(',', $requestedServices) : [];
                    @endphp
                    <button type="button" data-service="window" class="service-option px-4 py-3 text-sm font-medium rounded-xl border-2 transition-all duration-300 {{ in_array('window', $selectedServices) ? 'bg-blue-50 border-blue-400 text-blue-700 shadow-md' : 'bg-white border-gray-200 text-gray-700 hover:bg-blue-50 hover:border-blue-400 hover:text-blue-700 hover:shadow-md' }}">
                        窓ガラス清掃
                    </button>
                    <button type="button" data-service="inspection" class="service-option px-4 py-3 text-sm font-medium rounded-xl border-2 transition-all duration-300 {{ in_array('inspection', $selectedServices) ? 'bg-green-50 border-green-400 text-green-700 shadow-md' : 'bg-white border-gray-200 text-gray-700 hover:bg-green-50 hover:border-green-400 hover:text-green-700 hover:shadow-md' }}">
                        外壁調査
                    </button>
                    <button type="button" data-service="repair" class="service-option px-4 py-3 text-sm font-medium rounded-xl border-2 transition-all duration-300 {{ in_array('repair', $selectedServices) ? 'bg-orange-50 border-orange-400 text-orange-700 shadow-md' : 'bg-white border-gray-200 text-gray-700 hover:bg-orange-50 hover:border-orange-400 hover:text-orange-700 hover:shadow-md' }}">
                        外壁補修
                    </button>
                    <button type="button" data-service="painting" class="service-option px-4 py-3 text-sm font-medium rounded-xl border-2 transition-all duration-300 {{ in_array('painting', $selectedServices) ? 'bg-purple-50 border-purple-400 text-purple-700 shadow-md' : 'bg-white border-gray-200 text-gray-700 hover:bg-purple-50 hover:border-purple-400 hover:text-purple-700 hover:shadow-md' }}">
                        外壁塗装（部分）
                    </button>
                    <button type="button" data-service="bird_control" class="service-option px-4 py-3 text-sm font-medium rounded-xl border-2 transition-all duration-300 {{ in_array('bird_control', $selectedServices) ? 'bg-yellow-50 border-yellow-400 text-yellow-700 shadow-md' : 'bg-white border-gray-200 text-gray-700 hover:bg-yellow-50 hover:border-yellow-400 hover:text-yellow-700 hover:shadow-md' }}">
                        鳥害対策
                    </button>
                    <button type="button" data-service="sign" class="service-option px-4 py-3 text-sm font-medium rounded-xl border-2 transition-all duration-300 {{ in_array('sign', $selectedServices) ? 'bg-indigo-50 border-indigo-400 text-indigo-700 shadow-md' : 'bg-white border-gray-200 text-gray-700 hover:bg-indigo-50 hover:border-indigo-400 hover:text-indigo-700 hover:shadow-md' }}">
                        看板作業
                    </button>
                    <button type="button" data-service="leak_inspection" class="service-option px-4 py-3 text-sm font-medium rounded-xl border-2 transition-all duration-300 {{ in_array('leak_inspection', $selectedServices) ? 'bg-teal-50 border-teal-400 text-teal-700 shadow-md' : 'bg-white border-gray-200 text-gray-700 hover:bg-teal-50 hover:border-teal-400 hover:text-teal-700 hover:shadow-md' }}">
                        雨漏り調査
                    </button>
                    <button type="button" data-service="other" class="service-option px-4 py-3 text-sm font-medium rounded-xl border-2 transition-all duration-300 {{ in_array('other', $selectedServices) ? 'bg-gray-50 border-gray-400 text-gray-800 shadow-md' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50 hover:border-gray-400 hover:text-gray-800 hover:shadow-md' }}">
                        その他
                    </button>
                </div>
            </div>

            <!-- Sort Type Selection -->
            <div class="mb-6">
                <div class="mb-4">
                    <span class="text-sm font-semibold text-gray-700">優先する条件を選択</span>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3" id="sort-options">
                    <button type="button" data-sort="recommend" class="sort-option px-4 py-3 text-sm font-medium rounded-xl border-2 transition-all duration-300 {{ $activeSort === 'recommend' ? 'bg-blue-50 border-blue-400 text-blue-700 shadow-md' : 'bg-white border-gray-200 text-gray-700 hover:bg-blue-50 hover:border-blue-400 hover:text-blue-700 hover:shadow-md' }}">
                        おすすめ
                    </button>
                    <button type="button" data-sort="safety" class="sort-option px-4 py-3 text-sm font-medium rounded-xl border-2 transition-all duration-300 {{ $activeSort === 'safety' ? 'bg-green-50 border-green-400 text-green-700 shadow-md' : 'bg-white border-gray-200 text-gray-700 hover:bg-green-50 hover:border-green-400 hover:text-green-700 hover:shadow-md' }}">
                        安全性重視
                    </button>
                    <button type="button" data-sort="performance" class="sort-option px-4 py-3 text-sm font-medium rounded-xl border-2 transition-all duration-300 {{ $activeSort === 'performance' ? 'bg-orange-50 border-orange-400 text-orange-700 shadow-md' : 'bg-white border-gray-200 text-gray-700 hover:bg-orange-50 hover:border-orange-400 hover:text-orange-700 hover:shadow-md' }}">
                        実績重視
                    </button>
                    <button type="button" data-sort="reviews" class="sort-option px-4 py-3 text-sm font-medium rounded-xl border-2 transition-all duration-300 {{ $activeSort === 'reviews' ? 'bg-purple-50 border-purple-400 text-purple-700 shadow-md' : 'bg-white border-gray-200 text-gray-700 hover:bg-purple-50 hover:border-purple-400 hover:text-purple-700 hover:shadow-md' }}">
                        口コミ重視
                    </button>
                </div>
            </div>

            <!-- Search Button -->
            <div class="text-left">
                <button id="search-button" class="px-8 py-4 bg-orange-600 text-white font-bold text-lg rounded-xl hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    条件で業者を検索
                </button>
            </div>
        </div>

        <!-- Main Content with Sidebar -->
        <div class="max-w-7xl mx-auto px-4">
            <!-- プロモーション含有の表示 -->
            <div class="mb-4 text-center">
                <p class="text-sm text-gray-600">本ページにはプロモーションが含まれています</p>
            </div>
            
            <div class="lg:grid lg:grid-cols-3 lg:gap-8">
                <!-- 左：業者一覧 -->
                <div class="lg:col-span-2">
                    <div class="space-y-6 mb-8" id="companies-container">
                        @foreach($companies as $company)
                            <x-company-card :company="$company" />
                        @endforeach
                    </div>
                </div>
                
                <!-- 右：サイドバー -->
                <aside class="mt-10 lg:mt-0 space-y-6">
                    <!-- 検索フォーム -->
                    <div class="bg-white rounded-lg shadow border border-gray-200">
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">会社名・キーワード検索</h3>
                            <div class="relative">
                                <input type="text" 
                                       id="search-input" 
                                       name="search"
                                       value="{{ $searchTerm ?? '' }}"
                                       placeholder="例：ロープアクセス、高所作業..." 
                                       class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white text-gray-700">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                            </div>
                            @if($searchTerm)
                            <div class="mt-3">
                                <p class="text-sm text-gray-600">検索中: <span class="font-medium text-blue-600">"{{ $searchTerm }}"</span></p>
                                <button onclick="clearSearch()" class="text-sm text-blue-600 hover:text-blue-800 underline">検索をクリア</button>
                            </div>
                            @endif
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

                    <!-- お問い合わせバナー -->
                    <div class="bg-orange-600 rounded-lg shadow text-white">
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
                    <div class="bg-white rounded-lg shadow border border-gray-200">
                        <div class="p-4">
                            <div class="text-xs text-gray-400 mb-2 text-center">広告</div>
                            <div class="ad-container">
                                {!! $siteSettings['sidebar_ad_2'] !!}
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- おすすめ記事 -->
                    <div class="bg-white rounded-xl shadow p-6">
                        <h2 class="text-xl font-bold mb-6 text-blue-600">
                            おすすめ記事
                        </h2>

                        <div class="space-y-6">
                            @forelse($featuredArticles as $item)
                                <a href="{{ $item['url'] ?? '#' }}"
                                   class="flex gap-4 hover:opacity-80 transition">

                                    <div class="w-24 h-16 bg-blue-100 rounded flex items-center justify-center flex-shrink-0 overflow-hidden">
                                        @if(!empty($item['featured_image_url']))
                                            <img src="{{ $item['featured_image_url'] }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                            </svg>
                                        @endif
                                    </div>

                                    <div class="flex-1">
                                        <div class="text-sm font-semibold leading-snug mb-1">
                                            {{ $item['title'] ?? '記事タイトル' }}
                                        </div>
                                    </div>

                                </a>
                            @empty
                                <div class="text-center text-gray-500 text-sm py-8">
                                    おすすめ記事がありません
                                </div>
                            @endforelse
                        </div>
                    </div>

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

        <!-- Pagination -->
        <div id="pagination-container">
            @if($companies->hasPages())
            <div class="flex justify-center">
                {{ $companies->withQueryString()->links() }}
            </div>
            @endif
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.959 8.959 0 01-4.906-1.451c-.302-.163-.622-.35-.963-.589L4 20l1.729-3.131c-.270-.476-.547-.949-.826-1.448C3.639 13.644 3 11.904 3 12c0-4.418 3.582-8 8-8s8 3.582 8 8z"/>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sortOptions = document.querySelectorAll('.sort-option');
    const serviceOptions = document.querySelectorAll('.service-option');
    const prefectureSelect = document.getElementById('prefecture-select');
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');
    const companiesContainer = document.getElementById('companies-container');
    const paginationContainer = document.getElementById('pagination-container');
    let currentSort = getCurrentSortFromPage();
    let selectedServices = getSelectedServicesFromPage();
    let currentSearch = '{{ $searchTerm ?? "" }}';
    
    // Wishlist management
    let wishlist = JSON.parse(localStorage.getItem('companyWishlist') || '[]');
    const wishlistFooter = document.getElementById('wishlist-footer');
    const wishlistCount = document.getElementById('wishlist-count');
    const clearWishlistBtn = document.getElementById('clear-wishlist');

    // Get current sort from page state
    function getCurrentSortFromPage() {
        const activeSortOption = document.querySelector('.sort-option.bg-blue-50, .sort-option.bg-green-50, .sort-option.bg-orange-50, .sort-option.bg-purple-50');
        return activeSortOption ? activeSortOption.getAttribute('data-sort') : 'recommend';
    }

    // Get selected services from page state
    function getSelectedServicesFromPage() {
        const activeServiceOptions = document.querySelectorAll('.service-option.bg-blue-50, .service-option.bg-green-50, .service-option.bg-orange-50, .service-option.bg-purple-50, .service-option.bg-yellow-50, .service-option.bg-indigo-50, .service-option.bg-teal-50, .service-option.bg-gray-50');
        return Array.from(activeServiceOptions).map(option => option.getAttribute('data-service'));
    }

    // Get current prefecture
    function getCurrentPrefecture() {
        return prefectureSelect.value;
    }

    // Update URL and reload companies
    function updateCompanies(sort, prefecture, services, search) {
        // Build URL parameters
        const params = new URLSearchParams();
        if (sort && sort !== 'recommend') {
            params.set('sort', sort);
        }
        if (prefecture) {
            params.set('prefecture', prefecture);
        }
        if (services && services.length > 0) {
            params.set('service', services.join(','));
        }
        if (search && search.trim()) {
            params.set('search', search.trim());
        }

        // Build new URL
        const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        
        // Navigate to new URL
        window.location.href = newUrl;
    }

    // Handle search button click
    searchButton.addEventListener('click', function() {
        const prefecture = getCurrentPrefecture();
        updateCompanies(currentSort, prefecture, selectedServices);
    });

    // Handle service option clicks (multiple selection)
    serviceOptions.forEach(option => {
        option.addEventListener('click', function() {
            const serviceType = this.getAttribute('data-service');
            
            // Toggle service selection
            const index = selectedServices.indexOf(serviceType);
            if (index > -1) {
                selectedServices.splice(index, 1);
                updateServiceInactive(this);
            } else {
                selectedServices.push(serviceType);
                updateServiceActive(this);
            }
        });
    });

    // Handle sort option clicks
    sortOptions.forEach(option => {
        option.addEventListener('click', function() {
            const sortType = this.getAttribute('data-sort');
            currentSort = sortType;
            
            // Update active sort styling
            updateSortActive(this);
            
            // Update companies
            updateCompanies(currentSort, getCurrentPrefecture(), selectedServices, currentSearch);
        });
    });

    // Update service option active state (for multiple selection)
    function updateServiceActive(activeOption) {
        const service = activeOption.getAttribute('data-service');
        activeOption.classList.remove('bg-white', 'border-gray-200', 'text-gray-700');
        
        // Apply service-specific colors
        switch(service) {
            case 'window':
                activeOption.classList.add('bg-blue-50', 'border-blue-400', 'text-blue-700', 'shadow-md');
                break;
            case 'inspection':
                activeOption.classList.add('bg-green-50', 'border-green-400', 'text-green-700', 'shadow-md');
                break;
            case 'repair':
                activeOption.classList.add('bg-orange-50', 'border-orange-400', 'text-orange-700', 'shadow-md');
                break;
            case 'painting':
                activeOption.classList.add('bg-purple-50', 'border-purple-400', 'text-purple-700', 'shadow-md');
                break;
            case 'bird_control':
                activeOption.classList.add('bg-yellow-50', 'border-yellow-400', 'text-yellow-700', 'shadow-md');
                break;
            case 'sign':
                activeOption.classList.add('bg-indigo-50', 'border-indigo-400', 'text-indigo-700', 'shadow-md');
                break;
            case 'leak_inspection':
                activeOption.classList.add('bg-teal-50', 'border-teal-400', 'text-teal-700', 'shadow-md');
                break;
            case 'other':
                activeOption.classList.add('bg-gray-50', 'border-gray-400', 'text-gray-800', 'shadow-md');
                break;
        }
    }

    // Update service option inactive state
    function updateServiceInactive(inactiveOption) {
        // Remove all active colors
        inactiveOption.classList.remove(
            'bg-blue-50', 'border-blue-400', 'text-blue-700',
            'bg-green-50', 'border-green-400', 'text-green-700',
            'bg-orange-50', 'border-orange-400', 'text-orange-700',
            'bg-purple-50', 'border-purple-400', 'text-purple-700',
            'bg-yellow-50', 'border-yellow-400', 'text-yellow-700',
            'bg-indigo-50', 'border-indigo-400', 'text-indigo-700',
            'bg-teal-50', 'border-teal-400', 'text-teal-700',
            'bg-gray-50', 'border-gray-400', 'text-gray-800',
            'shadow-md'
        );
        // Add inactive colors
        inactiveOption.classList.add('bg-white', 'border-gray-200', 'text-gray-700');
    }

    // Update sort option active state
    function updateSortActive(activeOption) {
        sortOptions.forEach(option => {
            option.classList.remove('bg-blue-50', 'border-blue-500', 'text-blue-700');
            option.classList.add('bg-gray-50', 'border-gray-300', 'text-gray-700', 'hover:bg-gray-100');
        });
        
        activeOption.classList.remove('bg-gray-50', 'border-gray-300', 'text-gray-700', 'hover:bg-gray-100');
        activeOption.classList.add('bg-blue-50', 'border-blue-500', 'text-blue-700');
    }

    // Wishlist functions
    function initWishlist() {
        updateWishlistUI();
        bindWishlistEvents();
    }

    function updateWishlistUI() {
        // Update counter
        wishlistCount.textContent = wishlist.length;
        
        // Show/hide footer
        if (wishlist.length > 0) {
            wishlistFooter.style.display = 'block';
            setTimeout(() => {
                wishlistFooter.classList.remove('translate-y-full');
            }, 10);
        } else {
            wishlistFooter.classList.add('translate-y-full');
            setTimeout(() => {
                wishlistFooter.style.display = 'none';
            }, 300);
        }
        
        // Update checkboxes
        document.querySelectorAll('.wishlist-toggle').forEach(toggle => {
            const companyId = parseInt(toggle.dataset.companyId);
            const checkbox = toggle.querySelector('.wishlist-checkbox');
            const checkmark = toggle.querySelector('.checkmark');
            const icon = toggle.querySelector('.wishlist-icon');
            
            const isInWishlist = wishlist.some(item => item.id === companyId);
            
            checkbox.checked = isInWishlist;
            if (isInWishlist) {
                checkmark.classList.remove('hidden');
                icon.classList.add('bg-green-50', 'border-green-500');
                icon.classList.remove('border-gray-400');
            } else {
                checkmark.classList.add('hidden');
                icon.classList.remove('bg-green-50', 'border-green-500');
                icon.classList.add('border-gray-400');
            }
        });
    }

    function bindWishlistEvents() {
        // Wishlist toggle click events
        document.addEventListener('click', function(e) {
            const toggle = e.target.closest('.wishlist-toggle');
            if (toggle) {
                e.preventDefault();
                const companyId = parseInt(toggle.dataset.companyId);
                const companyName = toggle.dataset.companyName;
                
                toggleWishlistItem({
                    id: companyId,
                    name: companyName
                });
            }
        });
        
        // Clear wishlist
        clearWishlistBtn.addEventListener('click', function() {
            wishlist = [];
            localStorage.setItem('companyWishlist', JSON.stringify(wishlist));
            updateWishlistUI();
        });
    }

    function toggleWishlistItem(company) {
        const existingIndex = wishlist.findIndex(item => item.id === company.id);
        
        if (existingIndex !== -1) {
            // Remove from wishlist
            wishlist.splice(existingIndex, 1);
        } else {
            // Add to wishlist
            wishlist.push(company);
        }
        
        // Save to localStorage
        localStorage.setItem('companyWishlist', JSON.stringify(wishlist));
        updateWishlistUI();
    }

    // Search functionality
    if (searchInput) {
        // Debounce function
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Search on input with debounce
        searchInput.addEventListener('input', debounce(function(e) {
            const searchTerm = e.target.value.trim();
            currentSearch = searchTerm;
            updateCompanies(currentSort, getCurrentPrefecture(), selectedServices, searchTerm);
        }, 500));

        // Search on Enter key
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const searchTerm = e.target.value.trim();
                currentSearch = searchTerm;
                updateCompanies(currentSort, getCurrentPrefecture(), selectedServices, searchTerm);
            }
        });
    }

    // Clear search function
    window.clearSearch = function() {
        if (searchInput) {
            searchInput.value = '';
            currentSearch = '';
            updateCompanies(currentSort, getCurrentPrefecture(), selectedServices, '');
        }
    };

    // Prefecture select change
    prefectureSelect.addEventListener('change', function() {
        updateCompanies(currentSort, this.value, selectedServices, currentSearch);
    });

    // Initialize wishlist
    initWishlist();
});
</script>

<style>
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
@endsection