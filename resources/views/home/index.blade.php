@extends('layouts.app')

@section('title', 'オヤズナ | 高所ロープ作業特化の一括見積もりサイト【窓ガラス清掃・外壁塗装・外壁補修など】')

@section('description', '高所ロープ作業特化の一括見積もりサイトです。窓ガラス清掃、外壁補修・塗装、鳥害対策などの高所作業に対応。安全基準・実績を確認して、安心して依頼できる会社が見つかります。')

@section('head')
    <script type="application/ld+json">
        {!! json_encode([
            '@@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'Organization',
                    '@id' => 'https://oyazuna.com/#organization',
                    'name' => 'オヤズナ',
                    'url' => 'https://oyazuna.com',
                    'logo' => asset('オヤズナ (1).png'),
                    'description' => '高所ロープ作業（無足場工法）特化の一括見積もりサイト。窓ガラス清掃・外壁塗装・外壁補修・外壁調査・鳥害対策・看板作業・雨漏り調査に対応する専門業者を比較できます。',
                ],
                [
                    '@type' => 'WebSite',
                    '@id' => 'https://oyazuna.com/#website',
                    'url' => 'https://oyazuna.com',
                    'name' => 'オヤズナ',
                    'publisher' => ['@id' => 'https://oyazuna.com/#organization'],
                ],
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endsection

@section('content')
<style>
/* ヒーローセクション専用スタイル */
.hero-section {
  display: block;
  line-height: 0;
  position: relative;
  z-index: 1;
}

.hero-main-image {
  width: 100%;
  height: auto;
  display: block;
}

.hero-logo-overlay {
  position: absolute;
  left: 27%;
  bottom: 5%;
  width: 22.75%;
  transform: translateX(-50%);
}

.hero-content {
  width: 100%;
  max-width: 1200px;
  margin: 0 auto;
  padding: 40px 16px 40px 0;
  display: flex;
  align-items: center;
  gap: 40px;
  min-width: 0;
  position: relative;
}

.hero-text {
  flex: 0 1 55%;
  min-width: 0;
  text-align: left;
  position: relative;
  transform: translateY(-40px);
}

.hero-text h1 {
  font-size: 56px;
  font-weight: 900 !important;
  color: #1f2a44;
  margin-bottom: 0;
  line-height: 1.2;
  word-break: keep-all;
  overflow-wrap: break-word;
  letter-spacing: -0.3px;
  font-stretch: condensed;
  text-align: center;
}

.hero-text .highlight {
  color: #ea580c;
  font-weight: 900 !important;
  letter-spacing: -0.3px;
  font-stretch: condensed;
  white-space: nowrap;
}

.hero-text .subtitle {
  font-size: 0.9em;
  color: #1f2a44;
  font-weight: 900 !important;
  letter-spacing: -0.3px;
  font-stretch: condensed;
  white-space: nowrap;
}

.hero-logo {
  width: 270px;
  height: auto;
  display: block;
  position: absolute;
  top: 100%;
  left: 50%;
  transform: translateX(-50%);
  margin-top: 20px;
}

.hero-text p {
  font-size: 18px;
  color: #374151;
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
  margin-top: 40px;
  padding: 0 20px 60px;
}

.area-search-card {
  max-width: 1280px;
  margin: 0 auto;
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

  .hero-logo {
    width: 225px;
    margin-left: auto;
    margin-right: auto;
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
    margin-top: 0;
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
    margin-top: 0;
    padding: 0 14px 40px;
  }

  .area-search-card {
    padding: 24px 18px 28px;
    border-radius: 0;
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

/* エリア検索タイトルスタイル */
.heading-16 {
    display: flex;
    justify-content: center;
    align-items: center;
    color: #333333;
}

.heading-16::before,
.heading-16::after {
    width: 3px;
    height: 40px;
    background-color: #2589d0;
    content: '';
}

.heading-16::before {
    transform: rotate(-35deg);
    margin-right: 30px;
}

.heading-16 {
    display: flex;
    justify-content: center;
    align-items: center;
    color: #333333;
}

.heading-16::before,
.heading-16::after {
    width: 3px;
    height: 40px;
    background-color: #2589d0;
    content: '';
}

.heading-16::before {
    transform: rotate(-35deg);
    margin-right: 30px;
}

.heading-16::after {
    transform: rotate(35deg);
    margin-left: 30px;
}
</style>

<!-- ヒーローセクション -->
<div class="hero-section">
  <h1 class="sr-only">高所ロープ作業に特化した専門業者へ無料で一括見積もり</h1>
  <img src="{{ asset('herosection5.png') }}" alt="高所ロープ作業に特化した専門業者へ無料で一括見積もり" class="hero-main-image">
  <img src="{{ asset('オヤズナ (1).png') }}" alt="オヤズナ" class="hero-logo-overlay">
</div>

<!-- サービスから探すセクション（エリア検索は一旦非表示、footer/サイトマップに集約。「条件から探す」とデザインを揃える） -->
<section class="area-search-overlap">
    <div class="area-search-card">
        <div class="area-search-header">
            <h2 class="heading-16 text-base md:text-lg font-bold">
                サービスから探す
            </h2>
            <div class="area-search-badge">
                掲載社数{{ $companyCount ?? 0 }}社 {{ now()->format('n月j日') }}更新
            </div>
        </div>

        <div class="area-search-body">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- 窓ガラス清掃 -->
            <a href="{{ route('hub.category', 'window-cleaning') }}" class="block bg-white border border-gray-200 p-4 hover:shadow-lg transition-shadow duration-200 hover:border-blue-300">
                <div class="flex items-center mb-3">
                    <div class="w-12 h-12 flex items-center justify-center mr-3">
                        <img src="{{ asset('images/window.png') }}" alt="窓ガラス清掃" class="w-8 h-8">
                    </div>
                    <h3 class="text-base md:text-lg font-bold text-blue-600">窓ガラス清掃</h3>
                </div>
                <p class="text-sm text-gray-600">高所窓ガラス清掃 定期清掃<br>スポット清掃 メンテナンス</p>
            </a>

            <!-- 外壁清掃 -->
            <a href="{{ route('hub.category', 'exterior-cleaning') }}" class="block bg-white border border-gray-200 p-4 hover:shadow-lg transition-shadow duration-200 hover:border-blue-300">
                <div class="flex items-center mb-3">
                    <div class="w-12 h-12 flex items-center justify-center mr-3">
                        <img src="{{ asset('images/waiper.png') }}" alt="外壁清掃" class="w-8 h-8">
                    </div>
                    <h3 class="text-base md:text-lg font-bold text-blue-600">外壁清掃</h3>
                </div>
                <p class="text-sm text-gray-600">足場不要の外壁洗浄<br>ロープアクセス・ゴンドラ対応</p>
            </a>

            <!-- 外壁塗装 -->
            <a href="{{ route('hub.category', 'exterior-painting') }}" class="block bg-white border border-gray-200 p-4 hover:shadow-lg transition-shadow duration-200 hover:border-blue-300">
                <div class="flex items-center mb-3">
                    <div class="w-12 h-12 flex items-center justify-center mr-3">
                        <img src="{{ asset('images/penki.png') }}" alt="外壁塗装" class="w-8 h-8">
                    </div>
                    <h3 class="text-base md:text-lg font-bold text-blue-600">外壁塗装</h3>
                </div>
                <p class="text-sm text-gray-600">部分塗装 タッチアップ<br>色合わせ 局所塗替え</p>
            </a>

            <!-- 外壁調査 -->
            <a href="{{ route('hub.category', 'exterior-inspection') }}" class="block bg-white border border-gray-200 p-4 hover:shadow-lg transition-shadow duration-200 hover:border-blue-300">
                <div class="flex items-center mb-3">
                    <div class="w-12 h-12 flex items-center justify-center mr-3">
                        <img src="{{ asset('images/hekiga.png') }}" alt="外壁調査" class="w-8 h-8">
                    </div>
                    <h3 class="text-base md:text-lg font-bold text-blue-600">外壁調査</h3>
                </div>
                <p class="text-sm text-gray-600">外壁点検 劣化調査<br>診断レポート 安全確認</p>
            </a>

            <!-- 外壁補修 -->
            <a href="{{ route('hub.category', 'wall-repair') }}" class="block bg-white border border-gray-200 p-4 hover:shadow-lg transition-shadow duration-200 hover:border-blue-300">
                <div class="flex items-center mb-3">
                    <div class="w-12 h-12 flex items-center justify-center mr-3">
                        <img src="{{ asset('images/hekiga.png') }}" alt="外壁補修" class="w-8 h-8">
                    </div>
                    <h3 class="text-base md:text-lg font-bold text-blue-600">外壁補修</h3>
                </div>
                <p class="text-sm text-gray-600">ひび割れ補修 剥離補修<br>シーリング 部分工事</p>
            </a>

            <!-- 看板作業 -->
            <a href="{{ route('hub.category', 'signboard') }}" class="block bg-white border border-gray-200 p-4 hover:shadow-lg transition-shadow duration-200 hover:border-blue-300">
                <div class="flex items-center mb-3">
                    <div class="w-12 h-12 flex items-center justify-center mr-3">
                        <img src="{{ asset('images/koukoku_building.png') }}" alt="看板作業" class="w-8 h-8">
                    </div>
                    <h3 class="text-base md:text-lg font-bold text-blue-600">看板作業</h3>
                </div>
                <p class="text-sm text-gray-600">看板設置 看板撤去<br>メンテナンス 修理工事</p>
            </a>

            <!-- 鳥害対策 -->
            <a href="{{ route('hub.category', 'bird-control') }}" class="block bg-white border border-gray-200 p-4 hover:shadow-lg transition-shadow duration-200 hover:border-blue-300">
                <div class="flex items-center mb-3">
                    <div class="w-12 h-12 flex items-center justify-center mr-3">
                        <img src="{{ asset('images/bird_toriyoke.png') }}" alt="鳥害対策" class="w-8 h-8">
                    </div>
                    <h3 class="text-base md:text-lg font-bold text-blue-600">鳥害対策</h3>
                </div>
                <p class="text-sm text-gray-600">防鳥ネット設置 忌避剤散布<br>巣の除去 対策工事</p>
            </a>

            <!-- すべてのサービス -->
            <a href="{{ route('companies.index') }}" class="block bg-white border border-gray-200 p-4 hover:shadow-lg transition-shadow duration-200 hover:border-blue-300">
                <div class="flex items-center mb-3">
                    <div class="w-12 h-12 bg-gray-100 flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base md:text-lg font-bold text-blue-600">すべてのサービス</h3>
                </div>
                <p class="text-sm text-gray-600">全カテゴリ・全業者から<br>まとめて比較する</p>
            </a>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('sitemap.page') }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                都道府県から業者を探す方はこちら &rsaquo;
            </a>
        </div>
        </div>
    </div>
</section>


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
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-16">
            <!-- カード1: 窓ガラス清掃の相場・費用目安を解説 -->
            <a href="/guide/window-cleaning-price" class="block bg-white border border-gray-300 shadow-sm hover:shadow-md transition-all duration-300 group">
                <!-- 画像エリア -->
                <div class="h-40 overflow-hidden">
                    <img src="{{ asset('images/useful_guide1.webp') }}" alt="窓ガラス清掃の相場・費用目安を解説" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                <!-- テキストエリア -->
                <div class="p-6">
                    <h3 class="text-base font-bold text-blue-600 leading-tight border-b-2 border-blue-600 pb-1 mb-3 inline-block">
                        窓ガラス清掃の相場・費用目安を解説
                    </h3>
                    <p class="text-xs text-gray-700 leading-relaxed">
                        窓ガラス清掃の料金相場や費用に影響する要因について解説。戸建て住宅からオフィスビルまでの価格目安をご紹介。
                    </p>
                </div>
            </a>

            <!-- カード2: 窓ガラス清掃業者の選び方を解説 -->
            <a href="{{ route('guide.window-cleaning-contractor-selection') }}" class="block bg-white border border-gray-300 shadow-sm hover:shadow-md transition-all duration-300 group">
                <!-- 画像エリア -->
                <div class="h-40 overflow-hidden">
                    <img src="{{ asset('images/useful_guide2.webp') }}" alt="窓ガラス清掃業者の選び方を解説" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                <!-- テキストエリア -->
                <div class="p-6">
                    <h3 class="text-base font-bold text-blue-600 leading-tight border-b-2 border-blue-600 pb-1 mb-3 inline-block">
                        窓ガラス清掃業者の選び方を解説
                    </h3>
                    <p class="text-xs text-gray-700 leading-relaxed">
                        技術力、安全性、料金、サービス内容など、信頼できる業者を見極めるための具体的なチェック項目をご紹介。
                    </p>
                </div>
            </a>

            <!-- カード3: 外壁塗装の料金相場・費用目安を解説 -->
            <a href="{{ route('guide.exterior-wall-painting-pricing') }}" class="block bg-white border border-gray-300 shadow-sm hover:shadow-md transition-all duration-300 group">
                <!-- 画像エリア -->
                <div class="h-40 overflow-hidden">
                    <img src="{{ asset('images/useful_guide3.webp') }}" alt="外壁塗装の料金相場・費用目安を解説" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                <!-- テキストエリア -->
                <div class="p-6">
                    <h3 class="text-base font-bold text-blue-600 leading-tight border-b-2 border-blue-600 pb-1 mb-3 inline-block">
                        外壁塗装の料金相場・費用目安を解説
                    </h3>
                    <p class="text-xs text-gray-700 leading-relaxed">
                        外壁塗装の費用相場を、面積や塗料別に詳しく解説。適正価格を把握して、後悔しない業者選びに役立てましょう。
                    </p>
                </div>
            </a>

            <!-- カード4: 外壁塗装業者の選び方を解説 -->
            <a href="{{ route('guide.exterior-wall-painting-contractor-selection') }}" class="block bg-white border border-gray-300 shadow-sm hover:shadow-md transition-all duration-300 group">
                <!-- 画像エリア -->
                <div class="h-40 overflow-hidden">
                    <img src="{{ asset('images/useful_guide4.webp') }}" alt="外壁塗装業者の選び方を解説" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                <!-- テキストエリア -->
                <div class="p-6">
                    <h3 class="text-base font-bold text-blue-600 leading-tight border-b-2 border-blue-600 pb-1 mb-3 inline-block">
                        外壁塗装業者の選び方を解説
                    </h3>
                    <p class="text-xs text-gray-700 leading-relaxed">
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
        <div style="margin-bottom: 80px;">
            <h2 class="text-xl md:text-2xl font-bold heading-16">
                高所ロープ作業の見積もり情報をもとに、適正価格の比較をサポートします。
            </h2>
        </div>

        <!-- 3つのカード -->
        <div class="grid md:grid-cols-3 gap-6 md:gap-8">
            <!-- カード1: 高所ロープ作業会社を比較できる -->
            <div class="bg-white p-8 text-center shadow-lg">
                <div class="mb-8">
                    <div class="w-20 h-20 mx-auto bg-blue-100 flex items-center justify-center mb-6">
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
            <div class="bg-white p-8 text-center shadow-lg">
                <div class="mb-8">
                    <div class="w-20 h-20 mx-auto bg-blue-100 flex items-center justify-center mb-6">
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
            <div class="bg-white p-8 text-center shadow-lg">
                <div class="mb-8">
                    <div class="w-20 h-20 mx-auto bg-blue-100 flex items-center justify-center mb-6">
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

<!-- お急ぎの方へ - Contact Form Section -->
<section class="bg-gray-50 py-12 md:py-16">
    <div class="max-w-6xl mx-auto px-4">
        <div class="w-full">
            <div class="bg-gray-500 shadow-lg text-white">
                <div class="p-6">
                    <h4 class="text-xl font-bold mb-6 text-center">お問い合わせ</h4>
                    
                    <form action="{{ route('quote.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- 依頼者区分 -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">依頼者区分 <span class="text-red-300">*</span></label>
                                <div class="flex space-x-4">
                                    <label class="flex items-center">
                                        <input type="radio" name="requester_type" value="corp" class="mr-2" required>
                                        <span class="text-white">法人</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="requester_type" value="personal" class="mr-2" required>
                                        <span class="text-white">個人</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- ご担当者名 -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">ご担当者名 <span class="text-red-300">*</span></label>
                                <input type="text" 
                                       name="contact_name" 
                                       placeholder="例：田中太郎" 
                                       required
                                       class="w-full px-3 py-2 text-gray-700 bg-white border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            </div>
                            
                            <!-- メールアドレス -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">メールアドレス <span class="text-red-300">*</span></label>
                                <input type="email" 
                                       name="email" 
                                       placeholder="example@email.com" 
                                       required
                                       class="w-full px-3 py-2 text-gray-700 bg-white border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            </div>
                            
                            <!-- 電話番号 -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">電話番号</label>
                                <input type="tel" 
                                       name="phone" 
                                       placeholder="03-1234-5678" 
                                       class="w-full px-3 py-2 text-gray-700 bg-white border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            </div>
                            
                            <!-- 都道府県 -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">都道府県 <span class="text-red-300">*</span></label>
                                <select name="prefecture" required class="w-full px-3 py-2 text-gray-700 bg-white border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                    <option value="">選択してください</option>
                                    <option value="東京都">東京都</option>
                                    <option value="神奈川県">神奈川県</option>
                                    <option value="大阪府">大阪府</option>
                                    <option value="愛知県">愛知県</option>
                                    <option value="埼玉県">埼玉県</option>
                                    <option value="千葉県">千葉県</option>
                                    <option value="兵庫県">兵庫県</option>
                                    <option value="福岡県">福岡県</option>
                                </select>
                            </div>
                            
                            <!-- サービス内容 -->
                            <div>
                                <label class="block text-sm font-medium text-white mb-2">サービス内容 <span class="text-red-300">*</span></label>
                                <select name="service_type" required class="w-full px-3 py-2 text-gray-700 bg-white border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                    <option value="">選択してください</option>
                                    <option value="window_cleaning">窓ガラス清掃</option>
                                    <option value="wall_painting">外壁塗装</option>
                                    <option value="wall_inspection">外壁調査・点検</option>
                                    <option value="wall_repair">外壁補修</option>
                                    <option value="bird_control">鳥害対策</option>
                                    <option value="sign_work">看板作業</option>
                                    <option value="leak_inspection">雨漏り調査</option>
                                    <option value="other">その他</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- 備考 -->
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">ご要望・詳細</label>
                            <textarea name="note" 
                                      placeholder="建物の階数、作業内容の詳細、希望時期などお気軽にお書きください" 
                                      rows="4"
                                      class="w-full px-3 py-2 text-gray-700 bg-white border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent resize-none"></textarea>
                        </div>
                        
                        <button type="submit" 
                                class="glowing-button w-full bg-orange-600 text-white px-6 py-4 font-bold text-lg hover:bg-orange-700 transition-colors">
                            無料で見積もり依頼
                        </button>
                        
                        <div class="text-xs text-gray-200 text-center">
                            <p>※送信後、オヤズナにて内容確認後、適切な業者へ共有いたします。</p>
                            <p>※その後、業者より直接ご連絡させていただきます。</p>
                        </div>
                    </form>
                </div>
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
                   class="bg-orange-600 text-white px-4 py-3 sm:px-6 sm:py-4 font-bold text-sm sm:text-base hover:bg-orange-700 transition-all duration-200 flex items-center gap-2 shadow-lg hover:shadow-xl transform hover:scale-105 whitespace-nowrap"
                   style="background: linear-gradient(to right, #ea580c, #dc2626) !important; color: white !important;">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.959 8.959 0 01-4.906-1.451c-.302-.163-.622-.35-.963-.589L4 20l1.729-3.131c-.27-.476-.547-.949-.826-1.448C3.639 13.644 3 11.904 3 12c0-4.418 3.582-8 8-8s8 3.582 8 8z"/>
                    </svg>
                    現調依頼する
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- PC版：画面下部固定の現調依頼CTA（ハブページと同デザイン。「ランキングから探す」セクションから表示開始） -->
<div id="home-fixed-cta" class="hidden md:flex fixed bottom-0 inset-x-0 z-40 justify-center bg-black/50 px-3 py-4 transform translate-y-full transition-transform duration-300">
    <a href="{{ route('quote.create') }}"
       class="inline-flex items-center justify-center gap-3 bg-blue-600 hover:bg-blue-700 transition-colors text-white font-bold text-xl px-12 py-4 shadow-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" />
            <path d="M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
        </svg>
        かんたん入力で現調依頼
    </a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctaBar = document.getElementById('home-fixed-cta');
    const rankingSection = document.getElementById('ranking-section');
    if (!ctaBar || !rankingSection) return;

    const toggleCta = () => {
        const reached = rankingSection.getBoundingClientRect().top <= 0;
        ctaBar.classList.toggle('translate-y-full', !reached);
    };

    toggleCta();
    window.addEventListener('scroll', toggleCta, { passive: true });
});
</script>

@endsection

