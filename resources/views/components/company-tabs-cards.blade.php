<section class="bg-blue-50">
  <div class="max-w-7xl mx-auto px-4 py-6 md:py-10">

    <!-- 人気ランキングから探すセクション -->
    <section style="margin-bottom: 100px;">
      <div class="text-center mb-8">
        <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-2">人気ランキングから探す</h2>
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
            <h3 class="font-bold text-lg text-black">窓ガラス清掃会社</h3>
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
                    <div class="font-bold text-lg text-gray-800 leading-tight mb-1 hover:text-blue-600 transition-colors duration-200">{{ $company['name'] }}</div>
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
                  <div class="font-bold text-lg text-gray-800 leading-tight mb-1">データ準備中</div>
                  <div class="flex items-center">
                    <div class="flex text-yellow-400 text-base">★★★★★</div>
                    <span class="text-sm text-gray-600 ml-2 font-medium">準備中</span>
                  </div>
                </div>
              </div>
            @endif
          </div>
          <div class="p-5">
            <a href="{{ route('companies.index', ['service' => 'window']) }}" class="block text-center text-white font-bold text-base bg-gradient-to-r from-blue-600 to-blue-700 py-4 px-6 rounded-lg shadow-lg hover:shadow-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200">
              窓ガラス清掃会社の<br>ランキングページへ
            </a>
          </div>
        </div>

        <!-- 外壁調査会社 -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 flex-shrink-0 border border-gray-100" style="width: 340px; min-width: 340px;">
          <div class="bg-gradient-to-r from-gray-200 to-gray-300 text-center py-6">
            <h3 class="font-bold text-lg text-black">外壁調査会社</h3>
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
                    <div class="font-bold text-lg text-gray-800 leading-tight mb-1 hover:text-blue-600 transition-colors duration-200">{{ $company['name'] }}</div>
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
                  <div class="font-bold text-lg text-gray-800 leading-tight mb-1">データ準備中</div>
                  <div class="flex items-center">
                    <div class="flex text-yellow-400 text-base">★★★★★</div>
                    <span class="text-sm text-gray-600 ml-2 font-medium">準備中</span>
                  </div>
                </div>
              </div>
            @endif
          </div>
          <div class="p-5">
            <a href="{{ route('companies.index', ['service' => 'inspection']) }}" class="block text-center text-white font-bold text-base bg-gradient-to-r from-blue-600 to-blue-700 py-4 px-6 rounded-lg shadow-lg hover:shadow-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200">
              外壁調査会社の<br>ランキングページへ
            </a>
          </div>
        </div>

        <!-- 外壁補修会社 -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 flex-shrink-0 border border-gray-100" style="width: 340px; min-width: 340px;">
          <div class="bg-gradient-to-r from-gray-200 to-gray-300 text-center py-6">
            <h3 class="font-bold text-lg text-black">外壁補修会社</h3>
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
                    <div class="font-bold text-lg text-gray-800 leading-tight mb-1 hover:text-blue-600 transition-colors duration-200">{{ $company['name'] }}</div>
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
                  <div class="font-bold text-lg text-gray-800 leading-tight mb-1">データ準備中</div>
                  <div class="flex items-center">
                    <div class="flex text-yellow-400 text-base">★★★★★</div>
                    <span class="text-sm text-gray-600 ml-2 font-medium">準備中</span>
                  </div>
                </div>
              </div>
            @endif
          </div>
          <div class="p-5">
            <a href="{{ route('companies.index', ['service' => 'repair']) }}" class="block text-center text-white font-bold text-base bg-gradient-to-r from-blue-600 to-blue-700 py-4 px-6 rounded-lg shadow-lg hover:shadow-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200">
              外壁補修会社の<br>ランキングページへ
            </a>
          </div>
        </div>

        <!-- 外壁塗装会社（部分） -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 flex-shrink-0 border border-gray-100" style="width: 340px; min-width: 340px;">
          <div class="bg-gradient-to-r from-gray-200 to-gray-300 text-center py-6">
            <h3 class="font-bold text-lg text-black">外壁塗装会社（部分）</h3>
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
                    <div class="font-bold text-lg text-gray-800 leading-tight mb-1 hover:text-blue-600 transition-colors duration-200">{{ $company['name'] }}</div>
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
                  <div class="font-bold text-lg text-gray-800 leading-tight mb-1">データ準備中</div>
                  <div class="flex items-center">
                    <div class="flex text-yellow-400 text-base">★★★★★</div>
                    <span class="text-sm text-gray-600 ml-2 font-medium">準備中</span>
                  </div>
                </div>
              </div>
            @endif
          </div>
          <div class="p-5">
            <a href="{{ route('companies.index', ['service' => 'painting']) }}" class="block text-center text-white font-bold text-base bg-gradient-to-r from-blue-600 to-blue-700 py-4 px-6 rounded-lg shadow-lg hover:shadow-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200">
              外壁塗装会社（部分）の<br>ランキングページへ
            </a>
          </div>
        </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 1) header（見出し+説明）: ここはgridの外 -->
    <header class="text-center mt-2 md:mt-4 mb-6 md:mb-8">
      <h2 class="text-xl md:text-2xl font-bold text-gray-900">おすすめの専門業者</h2>
      <p class="mt-2 text-gray-600 text-sm md:text-base">信頼できる高所ロープ作業会社をご紹介</p>
    </header>

    <!-- 2) grid（カード一覧 + サイドバー）: ここから左右同じ開始位置 -->
    <div class="lg:grid lg:grid-cols-3 lg:gap-8 items-start">

      <!-- 左：業者カード一覧（カードの上端が基準） -->
      <div class="lg:col-span-2 min-w-0">
        <!-- Search Form Section -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
            <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-4 text-center">業者を絞り込み検索</h3>
            
            <!-- Step 1: Prefecture -->
            <div class="mb-6">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">1</div>
                    <label for="prefecture-select" class="text-sm md:text-base font-semibold text-gray-700">都道府県を選択</label>
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
                    $currentPrefecture = $prefectureFilter ?? '';
                    @endphp
                    @foreach($prefectureMapping as $name => $slug)
                        <option value="{{ $slug }}" {{ $currentPrefecture === $slug ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Step 2: Service Type -->
            <div class="mb-6">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">2</div>
                    <span class="text-sm md:text-base font-semibold text-gray-700">サービス内容を選択</span>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3" id="service-options">
                    <button type="button" data-service="window" class="service-option px-4 py-3 text-sm md:text-base font-medium rounded-lg border-2 transition-all duration-200 bg-gray-50 border-gray-300 text-gray-700 hover:bg-gray-100">
                        窓ガラス清掃
                    </button>
                    <button type="button" data-service="inspection" class="service-option px-4 py-3 text-sm md:text-base font-medium rounded-lg border-2 transition-all duration-200 bg-gray-50 border-gray-300 text-gray-700 hover:bg-gray-100">
                        外壁調査
                    </button>
                    <button type="button" data-service="repair" class="service-option px-4 py-3 text-sm md:text-base font-medium rounded-lg border-2 transition-all duration-200 bg-gray-50 border-gray-300 text-gray-700 hover:bg-gray-100">
                        外壁補修
                    </button>
                    <button type="button" data-service="painting" class="service-option px-4 py-3 text-sm md:text-base font-medium rounded-lg border-2 transition-all duration-200 bg-gray-50 border-gray-300 text-gray-700 hover:bg-gray-100">
                        外壁塗装（部分）
                    </button>
                    <button type="button" data-service="bird" class="service-option px-4 py-3 text-sm md:text-base font-medium rounded-lg border-2 transition-all duration-200 bg-gray-50 border-gray-300 text-gray-700 hover:bg-gray-100">
                        鳥害対策
                    </button>
                    <button type="button" data-service="sign" class="service-option px-4 py-3 text-sm md:text-base font-medium rounded-lg border-2 transition-all duration-200 bg-gray-50 border-gray-300 text-gray-700 hover:bg-gray-100">
                        看板作業
                    </button>
                    <button type="button" data-service="leak" class="service-option px-4 py-3 text-sm md:text-base font-medium rounded-lg border-2 transition-all duration-200 bg-gray-50 border-gray-300 text-gray-700 hover:bg-gray-100">
                        雨漏り調査
                    </button>
                    <button type="button" data-service="other" class="service-option px-4 py-3 text-sm md:text-base font-medium rounded-lg border-2 transition-all duration-200 bg-gray-50 border-gray-300 text-gray-700 hover:bg-gray-100">
                        その他
                    </button>
                </div>
            </div>

            <!-- Step 3: Sort Type -->
            <div class="mb-6">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">3</div>
                    <span class="text-sm md:text-base font-semibold text-gray-700">優先する条件を選択</span>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3" id="sort-options">
                    <button type="button" data-sort="recommend" class="sort-option px-4 py-3 text-sm md:text-base font-medium rounded-lg border-2 transition-all duration-200 bg-blue-50 border-blue-500 text-blue-700">
                        おすすめ
                    </button>
                    <button type="button" data-sort="safe" class="sort-option px-4 py-3 text-sm md:text-base font-medium rounded-lg border-2 transition-all duration-200 bg-gray-50 border-gray-300 text-gray-700 hover:bg-gray-100">
                        安全性重視
                    </button>
                    <button type="button" data-sort="result" class="sort-option px-4 py-3 text-sm md:text-base font-medium rounded-lg border-2 transition-all duration-200 bg-gray-50 border-gray-300 text-gray-700 hover:bg-gray-100">
                        実績重視
                    </button>
                    <button type="button" data-sort="review" class="sort-option px-4 py-3 text-sm md:text-base font-medium rounded-lg border-2 transition-all duration-200 bg-gray-50 border-gray-300 text-gray-700 hover:bg-gray-100">
                        口コミ重視
                    </button>
                </div>
            </div>

            <!-- Step 4: Search Button -->
            <div class="text-center">
                <div class="flex items-center gap-2 justify-center mb-4">
                    <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">4</div>
                    <span class="text-sm md:text-base font-semibold text-gray-700">検索実行</span>
                </div>
                <button id="search-button" class="px-8 md:px-12 py-4 md:py-5 bg-orange-600 text-white font-bold text-lg md:text-xl rounded-lg hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 transition-colors shadow-lg">
                    条件で業者を検索
                </button>
            </div>
        </div>
        
        <!-- Results Title -->
        <div class="mb-4 md:mb-6">
            <h4 class="text-lg md:text-xl font-bold text-gray-800">検索結果</h4>
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
        </div>
        
        <div class="space-y-6 mb-8" id="companies-container">
          @foreach($companies as $company)
            <x-company-card :company="$company" />
          @endforeach
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const prefectureSelect = document.getElementById('prefecture-select');
    const serviceOptions = document.querySelectorAll('.service-option');
    const sortOptions = document.querySelectorAll('.sort-option');
    const searchButton = document.getElementById('search-button');
    let currentSort = getCurrentSort();
    let currentService = getCurrentService();
    
    // 検索ボタンクリック時の処理
    searchButton.addEventListener('click', function() {
        const prefecture = prefectureSelect.value;
        updateURL(prefecture, currentService, currentSort);
    });
    
    // サービス選択時の処理（複数選択可能）
    let selectedServices = [];
    serviceOptions.forEach(option => {
        option.addEventListener('click', function() {
            const serviceType = this.getAttribute('data-service');
            
            // サービスの選択状態をトグル
            if (selectedServices.includes(serviceType)) {
                // 既に選択されている場合は削除
                selectedServices = selectedServices.filter(s => s !== serviceType);
                this.classList.remove('bg-blue-50', 'border-blue-500', 'text-blue-700');
                this.classList.add('bg-gray-50', 'border-gray-300', 'text-gray-700', 'hover:bg-gray-100');
            } else {
                // 選択されていない場合は追加
                selectedServices.push(serviceType);
                this.classList.remove('bg-gray-50', 'border-gray-300', 'text-gray-700', 'hover:bg-gray-100');
                this.classList.add('bg-blue-50', 'border-blue-500', 'text-blue-700');
            }
            
            // 現在のサービスを更新（最初の選択項目を使用）
            currentService = selectedServices.length > 0 ? selectedServices.join(',') : '';
        });
    });
    
    // ソート選択時の処理
    sortOptions.forEach(option => {
        option.addEventListener('click', function() {
            const sortType = this.getAttribute('data-sort');
            currentSort = sortType;
            
            // ソートオプションのアクティブ状態を更新
            updateSortActive(this);
        });
    });
    
    // サービスオプションのアクティブ状態を更新（複数選択対応のため削除）
    
    // ソートオプションのアクティブ状態を更新
    function updateSortActive(activeOption) {
        sortOptions.forEach(option => {
            option.classList.remove('bg-blue-50', 'border-blue-500', 'text-blue-700');
            option.classList.add('bg-gray-50', 'border-gray-300', 'text-gray-700', 'hover:bg-gray-100');
        });
        
        activeOption.classList.remove('bg-gray-50', 'border-gray-300', 'text-gray-700', 'hover:bg-gray-100');
        activeOption.classList.add('bg-blue-50', 'border-blue-500', 'text-blue-700');
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
    
    // URLを更新してページ遷移
    function updateURL(prefecture, service, sort) {
        const params = new URLSearchParams();
        if (prefecture) {
            params.set('prefecture', prefecture);
        }
        if (service) {
            params.set('service', service);
        }
        if (sort) {
            params.set('sort', sort);
        }
        
        const newUrl = window.location.pathname + '?' + params.toString();
        window.location.href = newUrl;
    }
    
    // ページロード時に選択状態を復元
    const urlParams = new URLSearchParams(window.location.search);
    const currentPrefecture = urlParams.get('prefecture');
    if (currentPrefecture && prefectureSelect) {
        prefectureSelect.value = currentPrefecture;
    }
    
    // ページロード時にサービス選択を復元
    const serviceParam = urlParams.get('service');
    if (serviceParam) {
        const services = serviceParam.split(',');
        selectedServices = services;
        services.forEach(service => {
            const activeService = document.querySelector(`[data-service="${service}"]`);
            if (activeService) {
                activeService.classList.remove('bg-gray-50', 'border-gray-300', 'text-gray-700', 'hover:bg-gray-100');
                activeService.classList.add('bg-blue-50', 'border-blue-500', 'text-blue-700');
            }
        });
        currentService = serviceParam;
    }
    
    // ページロード時にソート選択を復元
    const sortParam = urlParams.get('sort') || 'recommend';
    const activeSort = document.querySelector(`[data-sort="${sortParam}"]`);
    if (activeSort) {
        updateSortActive(activeSort);
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