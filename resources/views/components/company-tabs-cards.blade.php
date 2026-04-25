<section class="bg-blue-50">
  <div class="max-w-7xl mx-auto px-4 py-6 md:py-10">

    <!-- 人気ランキングから探すセクション -->
    <section style="margin-bottom: 100px;">
      <div class="ranking-header-container flex items-center justify-center mb-8">
        <h1 class="heading-6 text-xl md:text-2xl font-bold">
          ランキングから探す
        </h1>
      </div>
      
      <!-- 4つのカテゴリーのランキング -->
      <div class="relative max-w-full">
        <!-- スライドボタン（左） -->
        <button id="slide-left" class="absolute left-2 top-1/2 transform -translate-y-1/2 z-20 bg-white rounded-full shadow-xl p-3 hover:bg-gray-50 transition-all duration-200 border border-gray-200 hover:shadow-2xl hidden md:flex items-center justify-center">
          <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
          </svg>
        </button>
        
        <!-- スライドボタン（右） -->
        <button id="slide-right" class="absolute right-2 top-1/2 transform -translate-y-1/2 z-20 bg-white rounded-full shadow-xl p-3 hover:bg-gray-50 transition-all duration-200 border border-gray-200 hover:shadow-2xl hidden md:flex items-center justify-center">
          <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
          </svg>
        </button>
        
        <!-- カードコンテナ -->
        <div class="ranking-slider-container relative mx-4 md:mx-8">
          <div id="ranking-cards" class="flex gap-4 md:gap-6 overflow-x-auto scrollbar-hide scroll-smooth pb-4">
        <!-- 窓ガラス清掃会社 -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 flex-shrink-0 border border-gray-100" style="width: 340px; min-width: 340px;">
          <div class="bg-gradient-to-r from-gray-200 to-gray-300 text-center py-6">
            <h3 class="font-bold text-base text-black">窓ガラス清掃会社</h3>
          </div>
          <div class="p-6">
            @if(isset($rankingData['window']) && count($rankingData['window']) > 0)
              @foreach($rankingData['window'] as $index => $company)
              <!-- {{ $index + 1 }}位 -->
              <a href="{{ $company['url'] }}" class="block">
                <div class="flex items-center {{ $index < 2 ? 'mb-5' : 'mb-4' }} relative hover:bg-gray-50 p-2 rounded-lg transition-colors duration-200">
                  <div class="w-20 h-20 mr-8 flex-shrink-0 relative">
                    <div class="w-full h-full bg-gray-200 rounded-lg overflow-hidden shadow-sm">
                      @if($company['logo_url'])
                        <img src="{{ $company['logo_url'] }}" alt="{{ $company['name'] }}ロゴ" class="w-full h-full object-cover">
                      @else
                        <img src="{{ asset('images/company_placeholder.png') }}" alt="会社ロゴ" class="w-full h-full object-cover">
                      @endif
                    </div>
                    <!-- ランキングバッジ -->
                    <div class="absolute z-20" style="top: -4px; left: -4px;">
                      <img src="{{ asset('images/ranking-crown-no' . ($index + 1) . '.png') }}" alt="{{ $index + 1 }}位" class="w-6 h-6">
                    </div>
                  </div>
                  <div class="flex-1 min-w-0 ml-4">
                    <div class="font-bold text-base text-gray-800 leading-tight mb-1 hover:text-blue-600 transition-colors duration-200">{{ $company['name'] }}</div>
                    <div class="flex items-center">
                      @if($company['has_reviews'] ?? false)
                        <div class="flex text-yellow-400 text-base">
                          @for($i = 1; $i <= 5; $i++)
                            @if($i <= ($company['star_rating'] ?? 0))
                              ★
                            @else
                              ☆
                            @endif
                          @endfor
                        </div>
                        <span class="text-sm text-gray-600 ml-2 font-medium">{{ number_format(($company['average_rating'] ?: 0) / 20, 1) }}({{ $company['reviews_count'] }}件)</span>
                      @else
                        <span class="text-sm text-gray-600 font-medium">口コミ投稿募集中</span>
                      @endif
                    </div>
                  </div>
                </div>
              </a>
              @endforeach
            @else
              <!-- フォールバック用のサンプルデータ -->
              <div class="flex items-center mb-5 relative">
                <div class="w-20 h-20 mr-8 flex-shrink-0 relative">
                  <div class="w-full h-full bg-gray-200 rounded-lg overflow-hidden shadow-sm">
                    <img src="{{ asset('images/company_placeholder.png') }}" alt="会社ロゴ" class="w-full h-full object-cover">
                  </div>
                  <div class="absolute z-20" style="top: -4px; left: -4px;">
                    <img src="{{ asset('images/ranking-crown-no1.png') }}" alt="1位" class="w-6 h-6">
                  </div>
                </div>
                <div class="flex-1 min-w-0 ml-4">
                  <div class="font-bold text-base text-gray-800 leading-tight mb-1">データ準備中</div>
                  <div class="flex items-center">
                    <div class="flex text-yellow-400 text-base">★★★★★</div>
                    <span class="text-sm text-gray-600 ml-2 font-medium">準備中</span>
                  </div>
                </div>
              </div>
            @endif
          </div>
          <div class="p-5">
            <a href="{{ route('companies.index', ['service' => 'window']) }}" class="flex items-center justify-center text-white font-bold text-base bg-gradient-to-r from-blue-600 to-blue-700 py-4 px-6 rounded-lg shadow-lg hover:shadow-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
              </svg>
              ランキングページへ
            </a>
          </div>
        </div>

        <!-- 外壁調査会社 -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 flex-shrink-0 border border-gray-100" style="width: 340px; min-width: 340px;">
          <div class="bg-gradient-to-r from-gray-200 to-gray-300 text-center py-6">
            <h3 class="font-bold text-base text-black">外壁調査会社</h3>
          </div>
          <div class="p-6">
            @if(isset($rankingData['inspection']) && count($rankingData['inspection']) > 0)
              @foreach($rankingData['inspection'] as $index => $company)
              <!-- {{ $index + 1 }}位 -->
              <a href="{{ $company['url'] }}" class="block">
                <div class="flex items-center {{ $index < 2 ? 'mb-5' : 'mb-4' }} relative hover:bg-gray-50 p-2 rounded-lg transition-colors duration-200">
                  <div class="w-20 h-20 mr-8 flex-shrink-0 relative">
                    <div class="w-full h-full bg-gray-200 rounded-lg overflow-hidden shadow-sm">
                      @if($company['logo_url'])
                        <img src="{{ $company['logo_url'] }}" alt="{{ $company['name'] }}ロゴ" class="w-full h-full object-cover">
                      @else
                        <img src="{{ asset('images/company_placeholder.png') }}" alt="会社ロゴ" class="w-full h-full object-cover">
                      @endif
                    </div>
                    <!-- ランキングバッジ -->
                    <div class="absolute z-20" style="top: -4px; left: -4px;">
                      <img src="{{ asset('images/ranking-crown-no' . ($index + 1) . '.png') }}" alt="{{ $index + 1 }}位" class="w-6 h-6">
                    </div>
                  </div>
                  <div class="flex-1 min-w-0 ml-4">
                    <div class="font-bold text-base text-gray-800 leading-tight mb-1 hover:text-blue-600 transition-colors duration-200">{{ $company['name'] }}</div>
                    <div class="flex items-center">
                      @if($company['has_reviews'] ?? false)
                        <div class="flex text-yellow-400 text-base">
                          @for($i = 1; $i <= 5; $i++)
                            @if($i <= ($company['star_rating'] ?? 0))
                              ★
                            @else
                              ☆
                            @endif
                          @endfor
                        </div>
                        <span class="text-sm text-gray-600 ml-2 font-medium">{{ number_format(($company['average_rating'] ?: 0) / 20, 1) }}({{ $company['reviews_count'] }}件)</span>
                      @else
                        <span class="text-sm text-gray-600 font-medium">口コミ投稿募集中</span>
                      @endif
                    </div>
                  </div>
                </div>
              </a>
              @endforeach
            @else
              <!-- フォールバック用のサンプルデータ -->
              <div class="flex items-center mb-5 relative">
                <div class="w-20 h-20 mr-8 flex-shrink-0 relative">
                  <div class="w-full h-full bg-gray-200 rounded-lg overflow-hidden shadow-sm">
                    <img src="{{ asset('images/company_placeholder.png') }}" alt="会社ロゴ" class="w-full h-full object-cover">
                  </div>
                  <div class="absolute z-20" style="top: -4px; left: -4px;">
                    <img src="{{ asset('images/ranking-crown-no1.png') }}" alt="1位" class="w-6 h-6">
                  </div>
                </div>
                <div class="flex-1 min-w-0 ml-4">
                  <div class="font-bold text-base text-gray-800 leading-tight mb-1">データ準備中</div>
                  <div class="flex items-center">
                    <div class="flex text-yellow-400 text-base">★★★★★</div>
                    <span class="text-sm text-gray-600 ml-2 font-medium">準備中</span>
                  </div>
                </div>
              </div>
            @endif
          </div>
          <div class="p-5">
            <a href="{{ route('companies.index', ['service' => 'inspection']) }}" class="flex items-center justify-center text-white font-bold text-base bg-gradient-to-r from-blue-600 to-blue-700 py-4 px-6 rounded-lg shadow-lg hover:shadow-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
              </svg>
              ランキングページへ
            </a>
          </div>
        </div>

        <!-- 外壁補修会社 -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 flex-shrink-0 border border-gray-100" style="width: 340px; min-width: 340px;">
          <div class="bg-gradient-to-r from-gray-200 to-gray-300 text-center py-6">
            <h3 class="font-bold text-base text-black">外壁補修会社</h3>
          </div>
          <div class="p-6">
            @if(isset($rankingData['repair']) && count($rankingData['repair']) > 0)
              @foreach($rankingData['repair'] as $index => $company)
              <!-- {{ $index + 1 }}位 -->
              <a href="{{ $company['url'] }}" class="block">
                <div class="flex items-center {{ $index < 2 ? 'mb-5' : 'mb-4' }} relative hover:bg-gray-50 p-2 rounded-lg transition-colors duration-200">
                  <div class="w-20 h-20 mr-8 flex-shrink-0 relative">
                    <div class="w-full h-full bg-gray-200 rounded-lg overflow-hidden shadow-sm">
                      @if($company['logo_url'])
                        <img src="{{ $company['logo_url'] }}" alt="{{ $company['name'] }}ロゴ" class="w-full h-full object-cover">
                      @else
                        <img src="{{ asset('images/company_placeholder.png') }}" alt="会社ロゴ" class="w-full h-full object-cover">
                      @endif
                    </div>
                    <!-- ランキングバッジ -->
                    <div class="absolute z-20" style="top: -4px; left: -4px;">
                      <img src="{{ asset('images/ranking-crown-no' . ($index + 1) . '.png') }}" alt="{{ $index + 1 }}位" class="w-6 h-6">
                    </div>
                  </div>
                  <div class="flex-1 min-w-0 ml-4">
                    <div class="font-bold text-base text-gray-800 leading-tight mb-1 hover:text-blue-600 transition-colors duration-200">{{ $company['name'] }}</div>
                    <div class="flex items-center">
                      @if($company['has_reviews'] ?? false)
                        <div class="flex text-yellow-400 text-base">
                          @for($i = 1; $i <= 5; $i++)
                            @if($i <= ($company['star_rating'] ?? 0))
                              ★
                            @else
                              ☆
                            @endif
                          @endfor
                        </div>
                        <span class="text-sm text-gray-600 ml-2 font-medium">{{ number_format(($company['average_rating'] ?: 0) / 20, 1) }}({{ $company['reviews_count'] }}件)</span>
                      @else
                        <span class="text-sm text-gray-600 font-medium">口コミ投稿募集中</span>
                      @endif
                    </div>
                  </div>
                </div>
              </a>
              @endforeach
            @else
              <!-- フォールバック用のサンプルデータ -->
              <div class="flex items-center mb-5 relative">
                <div class="w-20 h-20 mr-8 flex-shrink-0 relative">
                  <div class="w-full h-full bg-gray-200 rounded-lg overflow-hidden shadow-sm">
                    <img src="{{ asset('images/company_placeholder.png') }}" alt="会社ロゴ" class="w-full h-full object-cover">
                  </div>
                  <div class="absolute z-20" style="top: -4px; left: -4px;">
                    <img src="{{ asset('images/ranking-crown-no1.png') }}" alt="1位" class="w-6 h-6">
                  </div>
                </div>
                <div class="flex-1 min-w-0 ml-4">
                  <div class="font-bold text-base text-gray-800 leading-tight mb-1">データ準備中</div>
                  <div class="flex items-center">
                    <div class="flex text-yellow-400 text-base">★★★★★</div>
                    <span class="text-sm text-gray-600 ml-2 font-medium">準備中</span>
                  </div>
                </div>
              </div>
            @endif
          </div>
          <div class="p-5">
            <a href="{{ route('companies.index', ['service' => 'repair']) }}" class="flex items-center justify-center text-white font-bold text-base bg-gradient-to-r from-blue-600 to-blue-700 py-4 px-6 rounded-lg shadow-lg hover:shadow-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
              </svg>
              ランキングページへ
            </a>
          </div>
        </div>

        <!-- 外壁塗装会社（部分） -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 flex-shrink-0 border border-gray-100" style="width: 340px; min-width: 340px;">
          <div class="bg-gradient-to-r from-gray-200 to-gray-300 text-center py-6">
            <h3 class="font-bold text-base text-black">外壁塗装会社（部分）</h3>
          </div>
          <div class="p-6">
            @if(isset($rankingData['painting']) && count($rankingData['painting']) > 0)
              @foreach($rankingData['painting'] as $index => $company)
              <!-- {{ $index + 1 }}位 -->
              <a href="{{ $company['url'] }}" class="block">
                <div class="flex items-center {{ $index < 2 ? 'mb-5' : 'mb-4' }} relative hover:bg-gray-50 p-2 rounded-lg transition-colors duration-200">
                  <div class="w-20 h-20 mr-8 flex-shrink-0 relative">
                    <div class="w-full h-full bg-gray-200 rounded-lg overflow-hidden shadow-sm">
                      @if($company['logo_url'])
                        <img src="{{ $company['logo_url'] }}" alt="{{ $company['name'] }}ロゴ" class="w-full h-full object-cover">
                      @else
                        <img src="{{ asset('images/company_placeholder.png') }}" alt="会社ロゴ" class="w-full h-full object-cover">
                      @endif
                    </div>
                    <!-- ランキングバッジ -->
                    <div class="absolute z-20" style="top: -4px; left: -4px;">
                      <img src="{{ asset('images/ranking-crown-no' . ($index + 1) . '.png') }}" alt="{{ $index + 1 }}位" class="w-6 h-6">
                    </div>
                  </div>
                  <div class="flex-1 min-w-0 ml-4">
                    <div class="font-bold text-base text-gray-800 leading-tight mb-1 hover:text-blue-600 transition-colors duration-200">{{ $company['name'] }}</div>
                    <div class="flex items-center">
                      @if($company['has_reviews'] ?? false)
                        <div class="flex text-yellow-400 text-base">
                          @for($i = 1; $i <= 5; $i++)
                            @if($i <= ($company['star_rating'] ?? 0))
                              ★
                            @else
                              ☆
                            @endif
                          @endfor
                        </div>
                        <span class="text-sm text-gray-600 ml-2 font-medium">{{ number_format(($company['average_rating'] ?: 0) / 20, 1) }}({{ $company['reviews_count'] }}件)</span>
                      @else
                        <span class="text-sm text-gray-600 font-medium">口コミ投稿募集中</span>
                      @endif
                    </div>
                  </div>
                </div>
              </a>
              @endforeach
            @else
              <!-- フォールバック用のサンプルデータ -->
              <div class="flex items-center mb-5 relative">
                <div class="w-20 h-20 mr-8 flex-shrink-0 relative">
                  <div class="w-full h-full bg-gray-200 rounded-lg overflow-hidden shadow-sm">
                    <img src="{{ asset('images/company_placeholder.png') }}" alt="会社ロゴ" class="w-full h-full object-cover">
                  </div>
                  <div class="absolute z-20" style="top: -4px; left: -4px;">
                    <img src="{{ asset('images/ranking-crown-no1.png') }}" alt="1位" class="w-6 h-6">
                  </div>
                </div>
                <div class="flex-1 min-w-0 ml-4">
                  <div class="font-bold text-base text-gray-800 leading-tight mb-1">データ準備中</div>
                  <div class="flex items-center">
                    <div class="flex text-yellow-400 text-base">★★★★★</div>
                    <span class="text-sm text-gray-600 ml-2 font-medium">準備中</span>
                  </div>
                </div>
              </div>
            @endif
          </div>
          <div class="p-5">
            <a href="{{ route('companies.index', ['service' => 'painting']) }}" class="flex items-center justify-center text-white font-bold text-base bg-gradient-to-r from-blue-600 to-blue-700 py-4 px-6 rounded-lg shadow-lg hover:shadow-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
              </svg>
              ランキングページへ
            </a>
          </div>
        </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 1) header（見出し+説明）: ここはgridの外 -->
    <header class="text-left mt-2 md:mt-4 mb-6 md:mb-8">
      <h2 class="heading-21 text-xl md:text-2xl font-bold">おすすめの専門業者</h2>
    </header>

    <!-- 2) grid（カード一覧 + サイドバー）: ここから左右同じ開始位置 -->
    <div class="lg:grid lg:grid-cols-3 lg:gap-8 items-start">

      <!-- 左：業者カード一覧（カードの上端が基準） -->
      <div class="lg:col-span-2 min-w-0">
        <!-- Search Form Section -->
        <div class="bg-transparent rounded-3xl p-6 mb-8">
            <!-- Top Row: 3 Selection Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- Prefecture Selection Card -->
                <button type="button" id="prefecture-card" class="bg-white bg-opacity-90 backdrop-blur-sm rounded-2xl p-4 text-left hover:bg-opacity-100 transition-all duration-300 shadow-md hover:shadow-lg cursor-pointer">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <!-- Map/Globe icon -->
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-800">都道府県</h3>
                            <p class="text-sm text-gray-600">から探す</p>
                        </div>
                    </div>
                    <div id="selected-prefecture" class="mt-2 text-sm text-blue-600 hidden">選択中: <span></span></div>
                </button>

                <!-- Service Selection Card -->
                <button type="button" id="service-card" class="bg-white bg-opacity-90 backdrop-blur-sm rounded-2xl p-4 text-left hover:bg-opacity-100 transition-all duration-300 shadow-md hover:shadow-lg cursor-pointer">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <!-- Cog/Service icon -->
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-800">サービス内容</h3>
                            <p class="text-sm text-gray-600">から探す</p>
                        </div>
                    </div>
                    <div id="selected-services" class="mt-2 text-sm text-blue-600 hidden">選択中: <span></span></div>
                </button>

                <!-- Condition Selection Card -->
                <button type="button" id="condition-card" class="bg-white bg-opacity-90 backdrop-blur-sm rounded-2xl p-4 text-left hover:bg-opacity-100 transition-all duration-300 shadow-md hover:shadow-lg cursor-pointer">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <!-- Smiley face icon -->
                            <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-800">条件</h3>
                            <p class="text-sm text-gray-600">から探す</p>
                        </div>
                    </div>
                    <div id="selected-condition" class="mt-2 text-sm text-blue-600 hidden">選択中: <span></span></div>
                </button>
            </div>

            <!-- Main Search Button -->
            <div class="mb-4">
                <button type="button" id="main-search-button" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold text-lg px-8 py-4 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    条件で業者を検索
                </button>
            </div>

            <!-- Bottom Row: Keyword Search -->
            <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-2xl p-4 hover:bg-opacity-100 transition-all duration-300 shadow-md">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <!-- Search icon -->
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" id="keyword-search" placeholder="キーワードで探す" class="flex-1 bg-transparent border-none outline-none text-lg font-medium text-gray-800 placeholder-gray-600">
                    <button type="button" id="keyword-search-button" class="p-2 text-gray-600 hover:text-gray-800 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Hidden dropdown menus -->
            <div id="prefecture-dropdown" class="hidden mt-4 bg-white rounded-2xl p-4 shadow-lg max-h-60 overflow-y-auto">
                <h4 class="font-bold text-gray-800 mb-3">都道府県を選択</h4>
                <select id="prefecture-select" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
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
                    $currentPrefecture = $prefectureFilter ?? '';
                    @endphp
                    @foreach($prefectureMapping as $name => $slug)
                        <option value="{{ $slug }}" {{ $currentPrefecture === $slug ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div id="service-dropdown" class="hidden mt-4 bg-white rounded-2xl p-4 shadow-lg">
                <h4 class="font-bold text-gray-800 mb-3">サービス内容を選択</h4>
                <div class="grid grid-cols-2 gap-3" id="service-options">
                    <button type="button" data-service="window" class="service-option px-4 py-2 text-sm font-medium rounded-lg border transition-all duration-300 bg-gray-50 border-gray-300 text-gray-700 hover:bg-blue-50 hover:border-blue-400 hover:text-blue-700">
                        窓ガラス清掃
                    </button>
                    <button type="button" data-service="inspection" class="service-option px-4 py-2 text-sm font-medium rounded-lg border transition-all duration-300 bg-gray-50 border-gray-300 text-gray-700 hover:bg-blue-50 hover:border-blue-400 hover:text-blue-700">
                        外壁調査
                    </button>
                    <button type="button" data-service="repair" class="service-option px-4 py-2 text-sm font-medium rounded-lg border transition-all duration-300 bg-gray-50 border-gray-300 text-gray-700 hover:bg-blue-50 hover:border-blue-400 hover:text-blue-700">
                        外壁補修
                    </button>
                    <button type="button" data-service="painting" class="service-option px-4 py-2 text-sm font-medium rounded-lg border transition-all duration-300 bg-gray-50 border-gray-300 text-gray-700 hover:bg-blue-50 hover:border-blue-400 hover:text-blue-700">
                        外壁塗装（部分）
                    </button>
                    <button type="button" data-service="bird_control" class="service-option px-4 py-2 text-sm font-medium rounded-lg border transition-all duration-300 bg-gray-50 border-gray-300 text-gray-700 hover:bg-blue-50 hover:border-blue-400 hover:text-blue-700">
                        鳥害対策
                    </button>
                    <button type="button" data-service="sign" class="service-option px-4 py-2 text-sm font-medium rounded-lg border transition-all duration-300 bg-gray-50 border-gray-300 text-gray-700 hover:bg-blue-50 hover:border-blue-400 hover:text-blue-700">
                        看板作業
                    </button>
                    <button type="button" data-service="leak_inspection" class="service-option px-4 py-2 text-sm font-medium rounded-lg border transition-all duration-300 bg-gray-50 border-gray-300 text-gray-700 hover:bg-blue-50 hover:border-blue-400 hover:text-blue-700">
                        雨漏り調査
                    </button>
                    <button type="button" data-service="other" class="service-option px-4 py-2 text-sm font-medium rounded-lg border transition-all duration-300 bg-gray-50 border-gray-300 text-gray-700 hover:bg-blue-50 hover:border-blue-400 hover:text-blue-700">
                        その他
                    </button>
                </div>
            </div>

            <div id="condition-dropdown" class="hidden mt-4 bg-white rounded-2xl p-4 shadow-lg">
                <h4 class="font-bold text-gray-800 mb-3">優先する条件を選択</h4>
                <div class="grid grid-cols-2 gap-3" id="sort-options">
                    <button type="button" data-sort="recommend" class="sort-option px-4 py-2 text-sm font-medium rounded-lg border transition-all duration-300 bg-blue-50 border-blue-400 text-blue-700">
                        おすすめ
                    </button>
                    <button type="button" data-sort="safe" class="sort-option px-4 py-2 text-sm font-medium rounded-lg border transition-all duration-300 bg-gray-50 border-gray-300 text-gray-700 hover:bg-blue-50 hover:border-blue-400 hover:text-blue-700">
                        安全性重視
                    </button>
                    <button type="button" data-sort="result" class="sort-option px-4 py-2 text-sm font-medium rounded-lg border transition-all duration-300 bg-gray-50 border-gray-300 text-gray-700 hover:bg-blue-50 hover:border-blue-400 hover:text-blue-700">
                        実績重視
                    </button>
                    <button type="button" data-sort="review" class="sort-option px-4 py-2 text-sm font-medium rounded-lg border transition-all duration-300 bg-gray-50 border-gray-300 text-gray-700 hover:bg-blue-50 hover:border-blue-400 hover:text-blue-700">
                        口コミ重視
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Results Title -->
        <div class="mb-4 md:mb-6">
            <h4 class="text-lg md:text-xl font-bold text-gray-800 mb-8">検索結果</h4>
        </div>
        
        <!-- Tabs for おすすめ and 口コミ positioned above cards -->
        <div class="kirikae-tab mb-0">
            <input id="recommend-tab" type="radio" name="tab-style" value="recommend" checked>
            <label class="tab-style" for="recommend-tab">おすすめ</label>

            <input id="review-tab" type="radio" name="tab-style" value="review">
            <label class="tab-style" for="review-tab">口コミ</label>
        </div>
        
        <div class="hidden bg-gray-100 rounded-lg p-1 min-w-max" id="homepage-tabs">
            <a href="?sort=recommend"
               class="tab-button px-4 md:px-8 py-3 md:py-4 text-sm md:text-xl font-medium rounded-md transition-colors whitespace-nowrap {{ ($sort ?? 'recommend') === 'recommend' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                おすすめ
            </a>
            <a href="?sort=safe"
               class="tab-button px-4 md:px-8 py-3 md:py-4 text-sm md:text-xl font-medium rounded-md transition-colors whitespace-nowrap {{ ($sort ?? 'recommend') === 'safe' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                安全
            </a>
            <a href="?sort=result"
               class="tab-button px-4 md:px-8 py-3 md:py-4 text-sm md:text-xl font-medium rounded-md transition-colors whitespace-nowrap {{ ($sort ?? 'recommend') === 'result' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                実績
            </a>
            <a href="?sort=review"
               class="tab-button px-4 md:px-8 py-3 md:py-4 text-sm md:text-xl font-medium rounded-md transition-colors whitespace-nowrap {{ ($sort ?? 'recommend') === 'review' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                口コミ
            </a>
        </div>
        
        <!-- Company Cards Container with white background connected to tabs -->
        <div class="bg-white rounded-t-none rounded-b-lg shadow-sm border border-t-0 border-gray-200 p-6 mb-8" style="margin-top: -1px;">
            <div class="space-y-6" id="companies-container">
                @foreach($companies as $company)
                    <x-company-card :company="$company" />
                @endforeach
            </div>
        </div>
        
        <!-- View More Button -->
        <div class="text-center">
          <a href="{{ route('companies.index') }}" 
             class="inline-flex items-center px-8 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
              もっと見る
              <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
          </a>
        </div>
      </div>

      <!-- 右：サイドバー -->
      <aside class="mt-10 lg:mt-0 space-y-6">
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

          <!-- 提携画像 -->
          <div class="bg-white rounded-lg shadow border border-gray-200">
              <div class="p-4">
                  <a href="{{ route('contact.create') }}" class="block hover:opacity-90 transition-opacity">
                      <img src="{{ asset('images/teikei.png') }}" alt="提携企業" class="w-full h-auto rounded">
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
                  @forelse($featuredArticles ?? [] as $item)
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
</section>

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

/* スクロールバーを非表示 */
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

/* ランキングスライダーのカスタムスタイル */
.ranking-slider-container {
    position: relative;
}

/* スライダーボタンのホバーエフェクト */
.ranking-slider-container button:hover {
    transform: translateY(-50%) scale(1.05);
}

/* スムーススクロールのためのスタイル */
.scroll-smooth {
    scroll-behavior: smooth;
}

/* モバイルでのスクロールインジケーター */
@media (max-width: 768px) {
    .ranking-slider-container::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 4px;
        background: linear-gradient(to right, transparent, #cbd5e1, transparent);
        border-radius: 2px;
    }
}

/* レスポンシブ広告対応 */
@media (max-width: 768px) {
    .ad-container {
        font-size: 14px;
    }
}

/* ランキングタイトルスタイル */
.ranking-header-container {
    position: relative;
    padding-bottom: .2em;
}

.heading-6 {
    display: inline-block;
    position: relative;
    padding: 0 4em;
    color: #333333;
}

.heading-6::before,
.heading-6::after {
    content: '';
    display: inline-block;
    position: absolute;
    top: 50%;
    width: 45px;
    height: 3px;
    background-color: #2589d0;
}

.heading-6::before {
    left: 0;
}

.heading-6::after {
    right: 0;
}

/* おすすめの専門業者タイトルスタイル */
.heading-21 {
    position: relative;
    padding: .5em .7em .4em;
    border-bottom: 3px solid #2589d0;
    color: #333333;
}

.heading-21::before,
.heading-21::after {
    position: absolute;
    left: 30px;
    bottom: -15px;
    width: 30px;
    height: 15px;
    clip-path: polygon(0 0, 100% 0, 50% 100%);
    content: '';
}

.heading-21::before {
    background-color: #2589d0;
}

.heading-21::after {
    bottom: -11px;
    background-color: #fff;
}

/* Kirikae tab styles */
.kirikae-tab {
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 0;
}

.kirikae-tab input[type="radio"] {
    display: none;
}

.kirikae-tab .tab-style {
    padding: 16px 32px;
    background-color: #f2f2f2;
    color: #999;
    font-size: 16px;
    font-weight: 500;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    margin-right: 4px;
    min-width: 120px;
    border-radius: 0;
}

.kirikae-tab .tab-style:hover {
    background-color: #e8e8e8;
    color: #666;
}

.kirikae-tab input[type="radio"]:checked + .tab-style {
    background-color: #fff;
    color: #333;
    border-bottom: 4px solid #2589d0;
    font-weight: 600;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const prefectureSelect = document.getElementById('prefecture-select');
    const serviceOptions = document.querySelectorAll('.service-option');
    const sortOptions = document.querySelectorAll('.sort-option');
    const mainSearchButton = document.getElementById('main-search-button');
    const keywordSearchButton = document.getElementById('keyword-search-button');
    const keywordSearch = document.getElementById('keyword-search');
    
    // Card elements
    const prefectureCard = document.getElementById('prefecture-card');
    const serviceCard = document.getElementById('service-card');
    const conditionCard = document.getElementById('condition-card');
    
    // Dropdown elements
    const prefectureDropdown = document.getElementById('prefecture-dropdown');
    const serviceDropdown = document.getElementById('service-dropdown');
    const conditionDropdown = document.getElementById('condition-dropdown');
    
    let currentSort = getCurrentSort();
    let currentService = getCurrentService();
    let selectedServices = [];
    
    // Card click handlers to toggle dropdowns
    prefectureCard.addEventListener('click', function() {
        toggleDropdown('prefecture');
    });
    
    serviceCard.addEventListener('click', function() {
        toggleDropdown('service');
    });
    
    conditionCard.addEventListener('click', function() {
        toggleDropdown('condition');
    });
    
    function toggleDropdown(type) {
        // Hide all dropdowns first
        prefectureDropdown.classList.add('hidden');
        serviceDropdown.classList.add('hidden');
        conditionDropdown.classList.add('hidden');
        
        // Show the selected dropdown
        if (type === 'prefecture') {
            prefectureDropdown.classList.remove('hidden');
        } else if (type === 'service') {
            serviceDropdown.classList.remove('hidden');
        } else if (type === 'condition') {
            conditionDropdown.classList.remove('hidden');
        }
    }
    
    // Prefecture selection handler
    prefectureSelect.addEventListener('change', function() {
        const selectedText = this.options[this.selectedIndex].text;
        const selectedPrefecture = document.getElementById('selected-prefecture');
        
        if (this.value) {
            selectedPrefecture.querySelector('span').textContent = selectedText;
            selectedPrefecture.classList.remove('hidden');
        } else {
            selectedPrefecture.classList.add('hidden');
        }
    });
    
    // Search button handlers
    mainSearchButton.addEventListener('click', function() {
        performSearch();
    });
    
    keywordSearchButton.addEventListener('click', function() {
        performSearch();
    });
    
    keywordSearch.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });
    
    function performSearch() {
        const prefecture = prefectureSelect.value;
        const keyword = keywordSearch.value.trim();
        
        let searchParams = new URLSearchParams();
        
        if (prefecture) searchParams.set('prefecture', prefecture);
        if (currentService) searchParams.set('service', currentService);
        if (currentSort) searchParams.set('sort', currentSort);
        if (keyword) searchParams.set('keyword', keyword);
        
        updateURL(searchParams);
    }
    
    // Hide dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#prefecture-card') && !e.target.closest('#prefecture-dropdown')) {
            prefectureDropdown.classList.add('hidden');
        }
        if (!e.target.closest('#service-card') && !e.target.closest('#service-dropdown')) {
            serviceDropdown.classList.add('hidden');
        }
        if (!e.target.closest('#condition-card') && !e.target.closest('#condition-dropdown')) {
            conditionDropdown.classList.add('hidden');
        }
    });
    
    // Service selection handler
    serviceOptions.forEach(option => {
        option.addEventListener('click', function() {
            const serviceType = this.getAttribute('data-service');
            
            if (selectedServices.includes(serviceType)) {
                selectedServices = selectedServices.filter(s => s !== serviceType);
                this.classList.remove('bg-blue-50', 'border-blue-400', 'text-blue-700');
                this.classList.add('bg-gray-50', 'border-gray-300', 'text-gray-700');
            } else {
                selectedServices.push(serviceType);
                this.classList.remove('bg-gray-50', 'border-gray-300', 'text-gray-700');
                this.classList.add('bg-blue-50', 'border-blue-400', 'text-blue-700');
            }
            
            currentService = selectedServices.length > 0 ? selectedServices.join(',') : '';
            
            // Update selected services display
            const selectedServicesDisplay = document.getElementById('selected-services');
            if (selectedServices.length > 0) {
                const serviceNames = selectedServices.map(service => {
                    const option = document.querySelector(`[data-service="${service}"]`);
                    return option ? option.textContent.trim() : service;
                });
                selectedServicesDisplay.querySelector('span').textContent = serviceNames.join(', ');
                selectedServicesDisplay.classList.remove('hidden');
            } else {
                selectedServicesDisplay.classList.add('hidden');
            }
        });
    });
    
    // Sort selection handler
    sortOptions.forEach(option => {
        option.addEventListener('click', function() {
            const sortType = this.getAttribute('data-sort');
            currentSort = sortType;
            
            updateSortActive(this);
            
            // Update selected condition display
            const selectedConditionDisplay = document.getElementById('selected-condition');
            const conditionText = this.textContent.trim();
            selectedConditionDisplay.querySelector('span').textContent = conditionText;
            selectedConditionDisplay.classList.remove('hidden');
        });
    });
    
    // サービスオプションのアクティブ状態を更新（複数選択対応のため削除）
    
    function updateSortActive(activeOption) {
        sortOptions.forEach(option => {
            option.classList.remove('bg-blue-50', 'border-blue-400', 'text-blue-700');
            option.classList.add('bg-gray-50', 'border-gray-300', 'text-gray-700');
        });
        
        activeOption.classList.remove('bg-gray-50', 'border-gray-300', 'text-gray-700');
        activeOption.classList.add('bg-blue-50', 'border-blue-400', 'text-blue-700');
    }
    
    // 現在のソートタイプを取得
    function getCurrentSort() {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('sort') || 'recommend';
    }
    
    // 現在のサービスタイプを取得
    function getCurrentService() {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('service') || '';
    }
    
    function updateURL(searchParams) {
        const currentPath = window.location.pathname;
        if (currentPath === '/' || currentPath === '/home') {
            const newUrl = '/companies?' + searchParams.toString();
            window.location.href = newUrl;
        } else {
            const newUrl = window.location.pathname + '?' + searchParams.toString();
            window.location.href = newUrl;
        }
    }
    
    // ページロード時に選択状態を復元
    const urlParams = new URLSearchParams(window.location.search);
    const currentPrefecture = urlParams.get('prefecture');
    if (currentPrefecture && prefectureSelect) {
        prefectureSelect.value = currentPrefecture;
        const selectedText = prefectureSelect.options[prefectureSelect.selectedIndex].text;
        const selectedPrefectureDisplay = document.getElementById('selected-prefecture');
        selectedPrefectureDisplay.querySelector('span').textContent = selectedText;
        selectedPrefectureDisplay.classList.remove('hidden');
    }
    
    // ページロード時にサービス選択を復元
    const serviceParam = urlParams.get('service');
    if (serviceParam) {
        const services = serviceParam.split(',');
        selectedServices = services;
        services.forEach(service => {
            const activeService = document.querySelector(`[data-service="${service}"]`);
            if (activeService) {
                activeService.classList.remove('bg-gray-50', 'border-gray-300', 'text-gray-700');
                activeService.classList.add('bg-blue-50', 'border-blue-400', 'text-blue-700');
            }
        });
        currentService = serviceParam;
        
        // Update selected services display
        const selectedServicesDisplay = document.getElementById('selected-services');
        if (services.length > 0) {
            const serviceNames = services.map(service => {
                const option = document.querySelector(`[data-service="${service}"]`);
                return option ? option.textContent.trim() : service;
            });
            selectedServicesDisplay.querySelector('span').textContent = serviceNames.join(', ');
            selectedServicesDisplay.classList.remove('hidden');
        }
    }
    
    // ページロード時にソート選択を復元
    const sortParam = urlParams.get('sort') || 'recommend';
    const activeSort = document.querySelector(`[data-sort="${sortParam}"]`);
    if (activeSort) {
        updateSortActive(activeSort);
        const selectedConditionDisplay = document.getElementById('selected-condition');
        const conditionText = activeSort.textContent.trim();
        selectedConditionDisplay.querySelector('span').textContent = conditionText;
        selectedConditionDisplay.classList.remove('hidden');
    }
    // Wishlist management for home page
    let wishlist = JSON.parse(localStorage.getItem('companyWishlist') || '[]');
    const wishlistFooter = document.getElementById('wishlist-footer');
    const wishlistCount = document.getElementById('wishlist-count');
    const clearWishlistBtn = document.getElementById('clear-wishlist');

    function initWishlist() {
        updateWishlistUI();
        bindWishlistEvents();
    }

    function updateWishlistUI() {
        // Update counter
        if (wishlistCount) {
            wishlistCount.textContent = wishlist.length;
        }
        
        // Show/hide footer
        if (wishlistFooter) {
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
        }
        
        // Update checkboxes
        document.querySelectorAll('.wishlist-toggle').forEach(toggle => {
            const companyId = parseInt(toggle.dataset.companyId);
            const checkbox = toggle.querySelector('.wishlist-checkbox');
            const checkmark = toggle.querySelector('.checkmark');
            const icon = toggle.querySelector('.wishlist-icon');
            
            const isInWishlist = wishlist.some(item => item.id === companyId);
            
            if (checkbox) checkbox.checked = isInWishlist;
            if (isInWishlist) {
                if (checkmark) checkmark.classList.remove('hidden');
                if (icon) {
                    icon.classList.add('bg-green-50', 'border-green-500');
                    icon.classList.remove('border-gray-400');
                }
            } else {
                if (checkmark) checkmark.classList.add('hidden');
                if (icon) {
                    icon.classList.remove('bg-green-50', 'border-green-500');
                    icon.classList.add('border-gray-400');
                }
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
        if (clearWishlistBtn) {
            clearWishlistBtn.addEventListener('click', function() {
                wishlist = [];
                localStorage.setItem('companyWishlist', JSON.stringify(wishlist));
                updateWishlistUI();
            });
        }
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

    // Initialize wishlist
    initWishlist();
    
    // Results tabs functionality with instant switching
    const resultsTabInputs = document.querySelectorAll('input[name="tab-style"]');
    
    resultsTabInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.checked) {
                const tabType = this.value;
                sortCompaniesByTab(tabType);
            }
        });
    });
    
    function sortCompaniesByTab(sortType) {
        const companiesContainer = document.getElementById('companies-container');
        if (!companiesContainer) return;
        
        const companies = Array.from(companiesContainer.children);
        
        companies.sort((a, b) => {
            if (sortType === 'recommend') {
                // Sort by average rating (higher first)
                const aRating = extractRating(a);
                const bRating = extractRating(b);
                if (bRating !== aRating) {
                    return bRating - aRating;
                }
                // If ratings are equal, sort by review count
                const aCount = extractReviewCount(a);
                const bCount = extractReviewCount(b);
                return bCount - aCount;
            } else if (sortType === 'review') {
                // Sort by review count first (more reviews first)
                const aCount = extractReviewCount(a);
                const bCount = extractReviewCount(b);
                if (bCount !== aCount) {
                    return bCount - aCount;
                }
                // If review counts are equal, sort by rating
                const aRating = extractRating(a);
                const bRating = extractRating(b);
                return bRating - aRating;
            }
            return 0;
        });
        
        // Re-append sorted companies
        companies.forEach(company => {
            companiesContainer.appendChild(company);
        });
        
        // Update URL without reload
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('sort', sortType);
        const newUrl = window.location.pathname + '?' + urlParams.toString();
        window.history.pushState({}, '', newUrl);
    }
    
    function extractRating(companyElement) {
        // Look for rating in the format "4.5" with class text-2xl font-bold
        const ratingElement = companyElement.querySelector('.text-2xl.font-bold.text-gray-900');
        if (ratingElement) {
            const rating = parseFloat(ratingElement.textContent.trim());
            return !isNaN(rating) ? rating : 0;
        }
        return 0;
    }
    
    function extractReviewCount(companyElement) {
        // Look for review count in parentheses like "(15件)"
        const reviewElement = companyElement.querySelector('a[href*="reviews"]');
        if (reviewElement) {
            const text = reviewElement.textContent;
            const match = text.match(/\((\d+)件\)/);
            if (match) {
                return parseInt(match[1]);
            }
        }
        return 0;
    }
    
    // Initialize tab state based on current sort parameter
    const currentTabSort = getCurrentSort();
    const activeTabInput = document.querySelector(`input[name="tab-style"][value="${currentTabSort === 'review' ? 'review' : 'recommend'}"]`);
    if (activeTabInput) {
        activeTabInput.checked = true;
    } else {
        // Default to recommend tab
        const defaultTab = document.querySelector(`input[name="tab-style"][value="recommend"]`);
        if (defaultTab) {
            defaultTab.checked = true;
        }
    }
    
    // ランキングカードスライダー機能
    const rankingCards = document.getElementById('ranking-cards');
    const slideLeftBtn = document.getElementById('slide-left');
    const slideRightBtn = document.getElementById('slide-right');
    
    if (slideLeftBtn && slideRightBtn && rankingCards) {
        const cardWidth = 340; // カードの幅 + マージン
        const gap = 24; // カード間のギャップ
        const scrollDistance = cardWidth + gap;
        
        slideLeftBtn.addEventListener('click', function() {
            rankingCards.scrollBy({
                left: -scrollDistance,
                behavior: 'smooth'
            });
        });
        
        slideRightBtn.addEventListener('click', function() {
            rankingCards.scrollBy({
                left: scrollDistance,
                behavior: 'smooth'
            });
        });
        
        // スクロール位置に応じてボタンの表示/非表示を制御
        function updateSlideButtons() {
            const scrollLeft = rankingCards.scrollLeft;
            const scrollWidth = rankingCards.scrollWidth;
            const clientWidth = rankingCards.clientWidth;
            const isAtStart = scrollLeft <= 10;
            const isAtEnd = scrollLeft >= scrollWidth - clientWidth - 10;
            
            // ボタンの表示/非表示をアニメーションで制御
            slideLeftBtn.style.opacity = isAtStart ? '0.4' : '1';
            slideLeftBtn.style.pointerEvents = isAtStart ? 'none' : 'auto';
            slideLeftBtn.style.transform = `translateY(-50%) scale(${isAtStart ? '0.9' : '1'})`;
            
            slideRightBtn.style.opacity = isAtEnd ? '0.4' : '1';
            slideRightBtn.style.pointerEvents = isAtEnd ? 'none' : 'auto';
            slideRightBtn.style.transform = `translateY(-50%) scale(${isAtEnd ? '0.9' : '1'})`;
        }
        
        // スクロールイベントリスナーを追加
        rankingCards.addEventListener('scroll', updateSlideButtons);
        window.addEventListener('resize', updateSlideButtons);
        
        // 初期設定
        updateSlideButtons();
        
        // モバイルではタッチスワイプでスクロールできるように設定
        let isDown = false;
        let startX;
        let scrollLeftStart;
        
        rankingCards.addEventListener('touchstart', (e) => {
            isDown = true;
            startX = e.touches[0].pageX - rankingCards.offsetLeft;
            scrollLeftStart = rankingCards.scrollLeft;
        });
        
        rankingCards.addEventListener('touchend', () => {
            isDown = false;
        });
        
        rankingCards.addEventListener('touchmove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.touches[0].pageX - rankingCards.offsetLeft;
            const walk = (x - startX) * 2;
            rankingCards.scrollLeft = scrollLeftStart - walk;
        });
    }
});
</script>