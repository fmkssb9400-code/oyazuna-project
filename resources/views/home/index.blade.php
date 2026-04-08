@extends('layouts.app')

@section('title', 'オヤズナ - 高所窓ガラス清掃業者比較・一括見積もり')

@section('content')
<style>
/* ヒーローセクション専用スタイル */
.hero-section {
  background: linear-gradient(135deg, rgba(96, 178, 181, 0.3) 0%, rgba(78, 205, 196, 0.3) 100%), url('{{ asset('images/top-img.png') }}');
  background-size: cover;
  background-position: center;
  min-height: 500px;
  display: flex;
  align-items: center;
  position: relative;
  z-index: 1;
}

.hero-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 32px 16px;
  display: flex;
  align-items: center;
  gap: 40px;
}

.hero-text {
  flex: 1 1 55%;
  min-width: 0;
}

.hero-text h1 {
  font-size: 56px;
  font-weight: 900 !important;
  color: white;
  margin-bottom: 24px;
  line-height: 1.2;
  word-break: keep-all;
  overflow-wrap: break-word;
  letter-spacing: -0.3px;
  font-stretch: condensed;
}

.hero-text .highlight {
  color: #fde047;
  font-weight: 900 !important;
  letter-spacing: -0.3px;
  font-stretch: condensed;
  white-space: nowrap;
}

.hero-text .subtitle {
  font-size: 0.9em;
  color: white;
  font-weight: 900 !important;
  letter-spacing: -0.3px;
  font-stretch: condensed;
  white-space: nowrap;
}

.hero-text p {
  font-size: 18px;
  color: white;
  opacity: 0.9;
  margin-bottom: 24px;
  line-height: 1.8;
}

.hero-image {
  flex: 0 0 40%;
  position: relative;
  display: flex;
  justify-content: center;
  align-items: flex-end;
}

.hero-image img {
  max-width: 300px;
  width: 100%;
  height: auto;
  display: block;
}

.no1-badge {
  position: absolute;
  top: 0px;
  right: -20px;
  width: 80px;
  height: 80px;
  background: #fde047;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
  border: 3px solid #facc15;
}

.no1-badge-text {
  text-align: center;
  color: #374151;
}

.no1-badge-text .top {
  font-size: 10px;
  font-weight: bold;
}

.no1-badge-text .main {
  font-size: 18px;
  font-weight: bold;
  line-height: 1;
}

.no1-badge-text .note {
  font-size: 8px;
}

/* エリア検索カード - ヒーローに重なるデザイン */
.area-search-overlap {
  position: relative;
  z-index: 5;
  margin-top: -90px;
  padding: 0 20px 60px;
}

.area-search-card {
  max-width: 1280px;
  margin: 0 auto;
  background: #ffffff;
  border-radius: 24px;
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.10);
  padding: 36px 40px 40px;
}

.area-search-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  flex-wrap: wrap;
  margin-bottom: 28px;
}

.area-search-header h2 {
  margin: 0;
  font-size: 28px;
  font-weight: 800;
  line-height: 1.4;
  color: #1f2a44;
  display: flex;
  align-items: center;
  gap: 12px;
}

.area-search-icon {
  width: 40px;
  height: 40px;
  background: #3b82f6;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.area-search-badge {
  display: inline-flex;
  align-items: center;
  padding: 10px 18px;
  border-radius: 9999px;
  background: #f6e7d2;
  color: #b85b25;
  font-size: 16px;
  font-weight: 700;
  white-space: nowrap;
}

.area-search-body {
  margin-top: 10px;
}

/* ヒーローセクション レスポンシブ対応 */
@media (max-width: 1024px) {
  .hero-content {
    gap: 24px;
  }

  .hero-text h1 {
    font-size: 56px;
  }

  .hero-text p {
    font-size: 16px;
  }

  .hero-image img {
    max-width: 280px;
  }
}

@media (max-width: 768px) {
  .hero-section {
    min-height: 400px;
    padding: 40px 0 80px;
  }

  .hero-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    gap: 20px;
    padding: 0 20px;
    text-align: left;
  }

  .hero-text {
    width: 100%;
    max-width: 100%;
    text-align: center;
  }

  .hero-text h1 {
    font-size: 40px;
    line-height: 1.3;
    margin-bottom: 16px;
    word-break: keep-all;
    overflow-wrap: break-word;
  }

  .hero-text p {
    font-size: 15px;
    line-height: 1.8;
  }

  .hero-image {
    width: 100%;
    justify-content: center;
    margin-right: 0;
  }

  .hero-image img {
    max-width: 240px;
    width: 70%;
  }

  .no1-badge {
    position: absolute;
    top: -5px;
    right: -10px;
    width: 60px;
    height: 60px;
  }

  .no1-badge-text .top {
    font-size: 8px;
  }

  .no1-badge-text .main {
    font-size: 14px;
  }

  .no1-badge-text .note {
    font-size: 6px;
  }
}

@media (max-width: 480px) {
  .hero-section {
    min-height: 350px;
    padding: 28px 0 64px;
  }

  .hero-content {
    gap: 16px;
    padding: 0 16px;
  }

  .hero-text h1 {
    font-size: 32px;
    line-height: 1.35;
  }

  .hero-text p {
    font-size: 14px;
    line-height: 1.75;
  }

  .hero-image img {
    max-width: 200px;
    width: 75%;
  }

  .no1-badge {
    width: 50px;
    height: 50px;
    top: -5px;
    right: -5px;
  }

  .no1-badge-text .top {
    font-size: 7px;
  }

  .no1-badge-text .main {
    font-size: 12px;
  }

  .no1-badge-text .note {
    font-size: 5px;
  }
}

/* エリア検索カード レスポンシブ対応 */
@media (max-width: 1024px) {
  .area-search-overlap {
    margin-top: -70px;
  }

  .area-search-card {
    padding: 32px 28px 36px;
  }

  .area-search-header h2 {
    font-size: 24px;
  }
}

@media (max-width: 768px) {
  .area-search-overlap {
    margin-top: -40px;
    padding: 0 14px 40px;
  }

  .area-search-card {
    padding: 24px 18px 28px;
    border-radius: 18px;
  }

  .area-search-header {
    align-items: flex-start;
    flex-direction: column;
    margin-bottom: 20px;
  }

  .area-search-header h2 {
    font-size: 22px;
  }

  .area-search-badge {
    font-size: 14px;
    padding: 8px 14px;
  }

  .area-search-icon {
    width: 32px;
    height: 32px;
  }

  /* 都道府県カードはPCと同じ横並びを維持 */
  .area-search-body .flex {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }

  .area-search-body .flex-1 {
    min-width: 120px;
    flex-shrink: 0;
  }
}
</style>

<!-- ヒーローセクション -->
<div class="hero-section">
  <div class="hero-content">
    <div class="hero-text">
      <h1 style="font-size: 48px;"><span style="color: #FFD700;">高所ロープ業者の</span><br>口コミ・比較サイト</h1>
      <p>オヤズナは、高所ロープ作業に対応した業者を比較し、<br>安心して依頼できる会社が見つかるサイトです。</p>
    </div>
    <div class="hero-image">
      <img src="{{ asset('images/waiper.png') }}?v={{ time() }}" alt="オヤズナキャラクター">
      <!-- No.1バッジ -->
      <div class="no1-badge">
        <div class="no1-badge-text">
          <div class="top">ご利用者数</div>
          <div class="main">No.1</div>
          <div class="note">※</div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- 高所ロープ会社をエリアから探すセクション -->
<section class="area-search-overlap">
    <div class="area-search-card">
        <div class="area-search-header">
            <h2>
                <div class="area-search-icon">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                高所ロープ業者をエリアから探す
            </h2>
            <div class="area-search-badge">
                掲載社数{{ $companyCount ?? 0 }}社 {{ now()->format('n月j日') }}更新
            </div>
        </div>
        
        <div class="area-search-body">

        <!-- 都道府県グリッド -->
        <div class="flex gap-4 mb-8">
            <!-- 東京都 -->
            <a href="{{ route('companies.index', ['prefecture' => 'tokyo']) }}" class="relative group flex-1">
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 overflow-hidden">
                    <div class="h-24 bg-cover bg-center" style="background-image: url('{{ asset('images/tokyo.png') }}')"></div>
                    <div class="p-3 text-center">
                        <div class="font-semibold text-gray-800">東京都</div>
                    </div>
                </div>
            </a>

            <!-- 大阪府 -->
            <a href="{{ route('companies.index', ['prefecture' => 'osaka']) }}" class="relative group flex-1">
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 overflow-hidden">
                    <div class="h-24 bg-cover bg-center" style="background-image: url('{{ asset('images/osaka.png') }}')"></div>
                    <div class="p-3 text-center">
                        <div class="font-semibold text-gray-800">大阪府</div>
                    </div>
                </div>
            </a>

            <!-- 北海道 -->
            <a href="{{ route('companies.index', ['prefecture' => 'hokkaido']) }}" class="relative group flex-1">
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 overflow-hidden">
                    <div class="h-24 bg-cover bg-center" style="background-image: url('{{ asset('images/hokkaido.png') }}')"></div>
                    <div class="p-3 text-center">
                        <div class="font-semibold text-gray-800">北海道</div>
                    </div>
                </div>
            </a>

            <!-- 福岡県 -->
            <a href="{{ route('companies.index', ['prefecture' => 'fukuoka']) }}" class="relative group flex-1">
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 overflow-hidden">
                    <div class="h-24 bg-cover bg-center" style="background-image: url('{{ asset('images/fukuoka.png') }}')"></div>
                    <div class="p-3 text-center">
                        <div class="font-semibold text-gray-800">福岡県</div>
                    </div>
                </div>
            </a>

            <!-- 京都府 -->
            <a href="{{ route('companies.index', ['prefecture' => 'kyoto']) }}" class="relative group flex-1">
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 overflow-hidden">
                    <div class="h-24 bg-cover bg-center" style="background-image: url('{{ asset('images/kyoto.png') }}')"></div>
                    <div class="p-3 text-center">
                        <div class="font-semibold text-gray-800">京都府</div>
                    </div>
                </div>
            </a>

            <!-- 愛知県 -->
            <a href="{{ route('companies.index', ['prefecture' => 'aichi']) }}" class="relative group flex-1">
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 overflow-hidden">
                    <div class="h-24 bg-cover bg-center" style="background-image: url('{{ asset('images/aichi.png') }}')"></div>
                    <div class="p-3 text-center">
                        <div class="font-semibold text-gray-800">愛知県</div>
                    </div>
                </div>
            </a>
        </div>


        <!-- 地方別エリアリンク -->
        <div class="mt-8 space-y-4 text-sm">
            <!-- 北海道・東北 -->
            <div class="flex flex-wrap items-center gap-2">
                <div class="font-bold text-gray-800 min-w-24">北海道・東北</div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('companies.index', ['prefecture' => 'hokkaido']) }}" class="text-blue-600 hover:text-blue-800">北海道</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'aomori']) }}" class="text-blue-600 hover:text-blue-800">青森</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'iwate']) }}" class="text-blue-600 hover:text-blue-800">岩手</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'miyagi']) }}" class="text-blue-600 hover:text-blue-800">宮城</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'akita']) }}" class="text-blue-600 hover:text-blue-800">秋田</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'yamagata']) }}" class="text-blue-600 hover:text-blue-800">山形</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'fukushima']) }}" class="text-blue-600 hover:text-blue-800">福島</a>
                </div>
            </div>

            <!-- 北陸・甲信越 -->
            <div class="flex flex-wrap items-center gap-2">
                <div class="font-bold text-gray-800 min-w-24">北陸・甲信越</div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('companies.index', ['prefecture' => 'yamanashi']) }}" class="text-blue-600 hover:text-blue-800">山梨</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'nagano']) }}" class="text-blue-600 hover:text-blue-800">長野</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'niigata']) }}" class="text-blue-600 hover:text-blue-800">新潟</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'toyama']) }}" class="text-blue-600 hover:text-blue-800">富山</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'ishikawa']) }}" class="text-blue-600 hover:text-blue-800">石川</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'fukui']) }}" class="text-blue-600 hover:text-blue-800">福井</a>
                </div>
            </div>

            <!-- 関東 -->
            <div class="flex flex-wrap items-center gap-2">
                <div class="font-bold text-gray-800 min-w-24">関東</div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('companies.index', ['prefecture' => 'tokyo']) }}" class="text-blue-600 hover:text-blue-800">東京</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'kanagawa']) }}" class="text-blue-600 hover:text-blue-800">神奈川</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'chiba']) }}" class="text-blue-600 hover:text-blue-800">千葉</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'saitama']) }}" class="text-blue-600 hover:text-blue-800">埼玉</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'ibaraki']) }}" class="text-blue-600 hover:text-blue-800">茨城</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'tochigi']) }}" class="text-blue-600 hover:text-blue-800">栃木</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'gunma']) }}" class="text-blue-600 hover:text-blue-800">群馬</a>
                </div>
            </div>

            <!-- 東海 -->
            <div class="flex flex-wrap items-center gap-2">
                <div class="font-bold text-gray-800 min-w-24">東海</div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('companies.index', ['prefecture' => 'aichi']) }}" class="text-blue-600 hover:text-blue-800">愛知</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'shizuoka']) }}" class="text-blue-600 hover:text-blue-800">静岡</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'gifu']) }}" class="text-blue-600 hover:text-blue-800">岐阜</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'mie']) }}" class="text-blue-600 hover:text-blue-800">三重</a>
                </div>
            </div>

            <!-- 中国 -->
            <div class="flex flex-wrap items-center gap-2">
                <div class="font-bold text-gray-800 min-w-24">中国</div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('companies.index', ['prefecture' => 'okayama']) }}" class="text-blue-600 hover:text-blue-800">岡山</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'hiroshima']) }}" class="text-blue-600 hover:text-blue-800">広島</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'tottori']) }}" class="text-blue-600 hover:text-blue-800">鳥取</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'shimane']) }}" class="text-blue-600 hover:text-blue-800">島根</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'yamaguchi']) }}" class="text-blue-600 hover:text-blue-800">山口</a>
                </div>
            </div>

            <!-- 関西 -->
            <div class="flex flex-wrap items-center gap-2">
                <div class="font-bold text-gray-800 min-w-24">関西</div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('companies.index', ['prefecture' => 'osaka']) }}" class="text-blue-600 hover:text-blue-800">大阪</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'hyogo']) }}" class="text-blue-600 hover:text-blue-800">兵庫</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'kyoto']) }}" class="text-blue-600 hover:text-blue-800">京都</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'shiga']) }}" class="text-blue-600 hover:text-blue-800">滋賀</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'nara']) }}" class="text-blue-600 hover:text-blue-800">奈良</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'wakayama']) }}" class="text-blue-600 hover:text-blue-800">和歌山</a>
                </div>
            </div>

            <!-- 四国 -->
            <div class="flex flex-wrap items-center gap-2">
                <div class="font-bold text-gray-800 min-w-24">四国</div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('companies.index', ['prefecture' => 'tokushima']) }}" class="text-blue-600 hover:text-blue-800">徳島</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'kagawa']) }}" class="text-blue-600 hover:text-blue-800">香川</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'ehime']) }}" class="text-blue-600 hover:text-blue-800">愛媛</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'kochi']) }}" class="text-blue-600 hover:text-blue-800">高知</a>
                </div>
            </div>

            <!-- 九州・沖縄 -->
            <div class="flex flex-wrap items-center gap-2">
                <div class="font-bold text-gray-800 min-w-24">九州・沖縄</div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('companies.index', ['prefecture' => 'fukuoka']) }}" class="text-blue-600 hover:text-blue-800">福岡</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'saga']) }}" class="text-blue-600 hover:text-blue-800">佐賀</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'nagasaki']) }}" class="text-blue-600 hover:text-blue-800">長崎</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'kumamoto']) }}" class="text-blue-600 hover:text-blue-800">熊本</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'oita']) }}" class="text-blue-600 hover:text-blue-800">大分</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'miyazaki']) }}" class="text-blue-600 hover:text-blue-800">宮崎</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'kagoshima']) }}" class="text-blue-600 hover:text-blue-800">鹿児島</a>
                    <a href="{{ route('companies.index', ['prefecture' => 'okinawa']) }}" class="text-blue-600 hover:text-blue-800">沖縄</a>
                </div>
            </div>
        </div>
        </div>
    </div>
</section>


<!-- サービスカテゴリーメニューセクション -->
<div class="bg-gray-50 py-12 md:py-16">
    <div class="max-w-6xl mx-auto px-4">
        <!-- サービスカテゴリーグリッド -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12">
            <!-- 窓ガラス清掃 -->
            <a href="{{ route('companies.index', ['service' => 'window']) }}" class="block bg-white border border-gray-200 p-4 hover:shadow-lg transition-shadow duration-200 hover:border-blue-300">
                <div class="flex items-center mb-3">
                    <div class="w-12 h-12 flex items-center justify-center mr-3">
                        <img src="{{ asset('images/window.png') }}" alt="窓ガラス清掃" class="w-8 h-8">
                    </div>
                    <h3 class="text-lg font-bold text-blue-600">窓ガラス清掃</h3>
                </div>
                <p class="text-sm text-gray-600">高所窓ガラス清掃 定期清掃<br>スポット清掃 メンテナンス</p>
            </a>

            <!-- 外壁調査 -->
            <a href="{{ route('companies.index', ['service' => 'inspection']) }}" class="block bg-white border border-gray-200 p-4 hover:shadow-lg transition-shadow duration-200 hover:border-blue-300">
                <div class="flex items-center mb-3">
                    <div class="w-12 h-12 flex items-center justify-center mr-3">
                        <img src="{{ asset('images/hekiga.png') }}" alt="外壁調査" class="w-8 h-8">
                    </div>
                    <h3 class="text-lg font-bold text-blue-600">外壁調査</h3>
                </div>
                <p class="text-sm text-gray-600">外壁点検 劣化調査<br>診断レポート 安全確認</p>
            </a>

            <!-- 外壁補修 -->
            <a href="{{ route('companies.index', ['service' => 'repair']) }}" class="block bg-white border border-gray-200 p-4 hover:shadow-lg transition-shadow duration-200 hover:border-blue-300">
                <div class="flex items-center mb-3">
                    <div class="w-12 h-12 flex items-center justify-center mr-3">
                        <img src="{{ asset('images/hekiga.png') }}" alt="外壁補修" class="w-8 h-8">
                    </div>
                    <h3 class="text-lg font-bold text-blue-600">外壁補修</h3>
                </div>
                <p class="text-sm text-gray-600">ひび割れ補修 剥離補修<br>シーリング 部分工事</p>
            </a>

            <!-- 外壁塗装（部分） -->
            <a href="{{ route('companies.index', ['service' => 'painting']) }}" class="block bg-white border border-gray-200 p-4 hover:shadow-lg transition-shadow duration-200 hover:border-blue-300">
                <div class="flex items-center mb-3">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                        <img src="{{ asset('images/penki.png') }}" alt="外壁塗装" class="w-8 h-8">
                    </div>
                    <h3 class="text-lg font-bold text-blue-600">外壁塗装（部分）</h3>
                </div>
                <p class="text-sm text-gray-600">部分塗装 タッチアップ<br>色合わせ 局所塗替え</p>
            </a>

            <!-- 鳥害対策 -->
            <a href="{{ route('companies.index', ['service' => 'bird_control']) }}" class="block bg-white border border-gray-200 p-4 hover:shadow-lg transition-shadow duration-200 hover:border-blue-300">
                <div class="flex items-center mb-3">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                        <img src="{{ asset('images/bird_toriyoke.png') }}" alt="鳥害対策" class="w-8 h-8">
                    </div>
                    <h3 class="text-lg font-bold text-blue-600">鳥害対策</h3>
                </div>
                <p class="text-sm text-gray-600">防鳥ネット設置 忌避剤散布<br>巣の除去 対策工事</p>
            </a>

            <!-- 看板作業 -->
            <a href="{{ route('companies.index', ['service' => 'sign']) }}" class="block bg-white border border-gray-200 p-4 hover:shadow-lg transition-shadow duration-200 hover:border-blue-300">
                <div class="flex items-center mb-3">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                        <img src="{{ asset('images/koukoku_building.png') }}" alt="看板作業" class="w-8 h-8">
                    </div>
                    <h3 class="text-lg font-bold text-blue-600">看板作業</h3>
                </div>
                <p class="text-sm text-gray-600">看板設置 看板撤去<br>メンテナンス 修理工事</p>
            </a>

            <!-- 雨漏り調査 -->
            <a href="{{ route('companies.index', ['service' => 'leak_inspection']) }}" class="block bg-white border border-gray-200 p-4 hover:shadow-lg transition-shadow duration-200 hover:border-blue-300">
                <div class="flex items-center mb-3">
                    <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center mr-3">
                        <img src="{{ asset('images/water.png') }}" alt="雨漏り調査" class="w-8 h-8">
                    </div>
                    <h3 class="text-lg font-bold text-blue-600">雨漏り調査</h3>
                </div>
                <p class="text-sm text-gray-600">原因調査 散水試験<br>診断 応急処置</p>
            </a>

            <!-- その他 -->
            <a href="{{ route('companies.index', ['service' => 'other']) }}" class="block bg-white border border-gray-200 p-4 hover:shadow-lg transition-shadow duration-200 hover:border-blue-300">
                <div class="flex items-center mb-3">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-blue-600">その他</h3>
                </div>
                <p class="text-sm text-gray-600">特殊作業 緊急対応<br>カスタム工事 その他業務</p>
            </a>
        </div>
    </div>
</div>




@include('components.company-tabs-cards', ['companies' => $companies, 'activeSort' => 'recommend', 'featuredArticles' => $featuredArticles])

<!-- 高所ロープ作業の基礎ガイドセクション -->
<section class="bg-white py-12 md:py-16">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-left mb-12">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 flex items-center">
                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                高所ロープ作業の基礎ガイド
            </h2>
        </div>

        <!-- 4つのカード -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-16">
            <!-- カード1: 窓ガラス清掃の相場・費用目安を解説 -->
            <a href="/guide/window-cleaning-price" class="block bg-white shadow-lg hover:shadow-xl transition-shadow duration-200 group">
                <!-- 画像エリア -->
                <div class="h-40 overflow-hidden">
                    <img src="{{ asset('images/useful_guide1.png') }}" alt="窓ガラス清掃の相場・費用目安を解説" class="w-full h-full object-cover">
                </div>
                <!-- テキストエリア -->
                <div class="p-6">
                    <h3 class="text-lg font-bold text-blue-600 mb-3">
                        窓ガラス清掃の相場・費用目安を解説
                    </h3>
                    <p class="text-sm text-gray-700 leading-relaxed">
                        窓ガラス清掃の料金相場や費用に影響する要因について解説。戸建て住宅からオフィスビルまでの価格目安をご紹介。
                    </p>
                </div>
            </a>

            <!-- カード2: 窓ガラス清掃業者の選び方を解説 -->
            <a href="{{ route('guide.window-cleaning-contractor-selection') }}" class="block bg-white shadow-lg hover:shadow-xl transition-shadow duration-200 group">
                <!-- 画像エリア -->
                <div class="h-40 overflow-hidden">
                    <img src="{{ asset('images/useful_guide2.png') }}" alt="窓ガラス清掃業者の選び方を解説" class="w-full h-full object-cover">
                </div>
                <!-- テキストエリア -->
                <div class="p-6">
                    <h3 class="text-lg font-bold text-blue-600 mb-3">
                        窓ガラス清掃業者の選び方を解説
                    </h3>
                    <p class="text-sm text-gray-700 leading-relaxed">
                        技術力、安全性、料金、サービス内容など、信頼できる業者を見極めるための具体的なチェック項目をご紹介。
                    </p>
                </div>
            </a>

            <!-- カード3: 外壁塗装の料金相場・費用目安を解説 -->
            <a href="{{ route('guide.exterior-wall-painting-pricing') }}" class="block bg-white shadow-lg hover:shadow-xl transition-shadow duration-200 group">
                <!-- 画像エリア -->
                <div class="h-40 overflow-hidden">
                    <img src="{{ asset('images/useful_guide3.png') }}" alt="外壁塗装の料金相場・費用目安を解説" class="w-full h-full object-cover">
                </div>
                <!-- テキストエリア -->
                <div class="p-6">
                    <h3 class="text-lg font-bold text-blue-600 mb-3">
                        外壁塗装の料金相場・費用目安を解説
                    </h3>
                    <p class="text-sm text-gray-700 leading-relaxed">
                        外壁塗装の費用相場を、面積や塗料別に詳しく解説。適正価格を把握して、後悔しない業者選びに役立てましょう。
                    </p>
                </div>
            </a>

            <!-- カード4: 外壁塗装業者の選び方を解説 -->
            <a href="{{ route('guide.exterior-wall-painting-contractor-selection') }}" class="block bg-white shadow-lg hover:shadow-xl transition-shadow duration-200 group">
                <!-- 画像エリア -->
                <div class="h-40 overflow-hidden">
                    <img src="{{ asset('images/useful_guide4.png') }}" alt="外壁塗装業者の選び方を解説" class="w-full h-full object-cover">
                </div>
                <!-- テキストエリア -->
                <div class="p-6">
                    <h3 class="text-lg font-bold text-blue-600 mb-3">
                        外壁塗装業者の選び方を解説
                    </h3>
                    <p class="text-sm text-gray-700 leading-relaxed">
                        技術力、安全性、料金、サービス内容など、信頼できる業者を見極めるための具体的なチェック項目をご紹介。
                    </p>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- オヤズナとは？セクション -->
<section class="bg-gray-100 py-12 md:py-16">
    <div class="max-w-6xl mx-auto px-4">
        <!-- メインタイトル -->
        <div class="text-center" style="margin-bottom: 80px;">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">
                当サイトは、高所ロープ業者を探せるサイトです。
            </h2>
        </div>

        <!-- 3つのカード -->
        <div class="grid md:grid-cols-3 gap-6 md:gap-8">
            <!-- カード1: 高所ロープ作業会社を比較できる -->
            <div class="bg-white rounded-2xl p-8 text-center shadow-lg">
                <div class="mb-8">
                    <div class="w-20 h-20 mx-auto bg-blue-100 rounded-2xl flex items-center justify-center mb-6">
                        <img src="{{ asset('images/company_1.png') }}" alt="会社比較" class="w-10 h-10">
                    </div>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-6">
                    高所ロープ作業会社を<br>
                    <span class="text-blue-600">比較</span>できる
                </h3>
                <p class="text-sm text-gray-600 leading-relaxed">
                    オヤズナでは、対応エリア・施工内容・実績などの情報をもとに、<br>
                    高所ロープ作業に対応する会社を比較できます。
                </p>
            </div>

            <!-- カード2: オンラインで相談・見積もり依頼 -->
            <div class="bg-white rounded-2xl p-8 text-center shadow-lg">
                <div class="mb-8">
                    <div class="w-20 h-20 mx-auto bg-blue-100 rounded-2xl flex items-center justify-center mb-6">
                        <img src="{{ asset('images/dentaku.png') }}" alt="見積もり依頼" class="w-10 h-10">
                    </div>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-6">
                    <span class="text-blue-600">オンライン</span>で相談・<br>
                    見積もり依頼
                </h3>
                <p class="text-sm text-gray-600 leading-relaxed">
                    高所ロープ作業について、<br>
                    オンラインから簡単に相談や見積もり依頼ができます。
                </p>
            </div>

            <!-- カード3: 相談・見積もり依頼は無料 -->
            <div class="bg-white rounded-2xl p-8 text-center shadow-lg">
                <div class="mb-8">
                    <div class="w-20 h-20 mx-auto bg-blue-100 rounded-2xl flex items-center justify-center mb-6">
                        <img src="{{ asset('images/chat.png') }}" alt="無料相談" class="w-10 h-10">
                    </div>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-6">
                    相談・見積もり依頼は<br>
                    <span class="text-blue-600">無料</span>
                </h3>
                <p class="text-sm text-gray-600 leading-relaxed">
                    複数の会社情報を比較しながら、<br>
                    最適な依頼先探しをサポートします。
                </p>
            </div>
        </div>
    </div>
</section>

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

