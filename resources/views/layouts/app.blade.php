<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'オヤズナ | 高所ロープ作業の見積もり・相場データベース【高所の窓ガラス清掃・外壁塗装・外壁補修など】')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="description" content="@yield('description', '高所ロープ作業の専門業者を口コミと実績で比較できるサイトです。窓ガラス清掃、外壁補修・塗装、鳥害対策などの高所作業に対応。安心・信頼できる業者選びをサポートします。')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('head')
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"">
    
    <style>
        /* スマホでナビロゴを非表示 */
        @media (max-width: 767px) {
            .nav-logo {
                display: none !important;
            }
        }
        
        /* TinyEditor記事用のスタイル */
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 600;
            text-align: center;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-blue {
            background-color: #2563eb;
            color: white;
        }
        
        .btn-blue:hover {
            background-color: #1d4ed8;
            color: white;
            text-decoration: none;
        }
        
        .btn-orange {
            background-color: #ea580c;
            color: white;
        }
        
        .btn-orange:hover {
            background-color: #dc2626;
            color: white;
            text-decoration: none;
        }
        
        /* カスタムボタン用のTailwindクラス */
        .bg-orange-500 {
            background-color: #f97316 !important;
        }
        
        .bg-orange-600 {
            background-color: #ea580c !important;
        }
        
        .bg-blue-600 {
            background-color: #2563eb !important;
        }
        
        .bg-blue-700 {
            background-color: #1d4ed8 !important;
        }
        
        .hover\\:bg-orange-600:hover {
            background-color: #ea580c !important;
        }
        
        .hover\\:bg-blue-700:hover {
            background-color: #1d4ed8 !important;
        }
        
        .inline-block {
            display: inline-block;
        }
        
        .px-4 {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .py-2 {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }
        
        .rounded-lg {
            border-radius: 0.5rem;
        }
        
        .font-bold {
            font-weight: 700;
        }
        
        .text-white {
            color: white !important;
        }
        
        .btn-green {
            background-color: #16a34a;
            color: white;
        }
        
        .btn-green:hover {
            background-color: #15803d;
            color: white;
            text-decoration: none;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin: 1rem 0;
            border-left: 4px solid;
        }
        
        .alert-warning {
            background-color: #fef3c7;
            border-left-color: #f59e0b;
            color: #92400e;
        }
        
        .alert-info {
            background-color: #dbeafe;
            border-left-color: #3b82f6;
            color: #1e40af;
        }
        
        .glowing-button {
            position: relative;
            overflow: hidden;
        }
        
        .glowing-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.6), transparent);
            animation: flowing-light 3s infinite;
        }
        
        @keyframes flowing-light {
            0% {
                left: -100%;
            }
            100% {
                left: 100%;
            }
        }
    </style>
</head>
<body class="bg-blue-50">
    <nav class="sticky top-0 z-50 shadow-sm border-b overflow-visible bg-[linear-gradient(to_right,#66ccff_0%,#66ccff_17%,white_24%,white_100%)]">
        <!-- スマホ版ヘッダー：ロゴ（左）＋ハンバーガーメニュー（右） -->
        <div class="md:hidden flex items-center justify-between gap-2 py-2 px-4 border-b bg-[#66ccff]">
            <a href="{{ url('/') }}" class="shrink-0">
                <img
                    src="{{ asset('images/cremoba_logo.png') }}"
                    alt="オヤズナ"
                    class="w-auto object-contain"
                    style="height: 35px !important; max-height: 35px !important; width: auto !important;"
                />
            </a>
            <button
                type="button"
                onclick="document.getElementById('mobile-menu-panel').classList.toggle('hidden'); document.getElementById('mobile-menu-icon-open').classList.toggle('hidden'); document.getElementById('mobile-menu-icon-close').classList.toggle('hidden');"
                class="p-2 text-blue-950 shrink-0"
                aria-label="メニューを開く"
                aria-controls="mobile-menu-panel"
            >
                <svg id="mobile-menu-icon-open" xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 6h18M3 12h18M3 18h18" />
                </svg>
                <svg id="mobile-menu-icon-close" xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>
        </div>

        <!-- PC版ナビゲーション -->
        <div class="hidden md:flex h-20 overflow-visible w-full">
            <!-- ロゴ部分 -->
            <div class="flex items-center overflow-visible" style="padding-left: 80px;">
                <a href="{{ url('/') }}" class="nav-logo flex items-center gap-2">
                    <img
                        src="{{ asset('oyazunaicon.png') }}"
                        alt="オヤズナ"
                        class="w-auto object-contain shrink-0 block"
                        style="display: block !important; height: 45px; max-height: 45px;"
                    />
                </a>
            </div>

            <!-- 中央のナビゲーションメニュー -->
            <div class="flex items-center flex-1 justify-center space-x-6 h-20">
                <a href="{{ route('companies.index') }}" class="text-blue-950 hover:opacity-80 transition-opacity font-bold text-xl flex items-center h-full">掲載社数<span class="text-[#1d4ed8]" style="font-size: 1.25em;">{{ $companyCount ?? 0 }}</span>社</a>
                <a href="{{ route('news.index') }}" class="text-blue-950 hover:opacity-80 transition-opacity font-bold text-xl flex items-center h-full">ニュース・記事</a>
                <a href="{{ route('reviews.index') }}" class="text-blue-950 hover:opacity-80 transition-opacity font-bold text-xl flex items-center h-full">口コミを書く</a>
                <a href="{{ route('quote-data.create') }}" class="text-blue-950 hover:opacity-80 transition-opacity font-bold text-xl flex items-center h-full">見積もりデータを登録</a>
            </div>
            
            <!-- 現調依頼するボタン -->
            <a href="{{ route('quote.create') }}" class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 transition-colors text-white font-bold text-lg px-10 h-full glowing-button">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" />
                    <path d="M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
                無料で現調依頼する
            </a>
        </div>

        <!-- スマホ版：ハンバーガーメニューの開閉パネル -->
        <div id="mobile-menu-panel" class="hidden md:hidden border-b bg-white">
            <div class="flex flex-col divide-y divide-gray-100 px-4">
                <a href="{{ route('companies.index') }}" class="py-3 text-blue-950 hover:opacity-80 transition-opacity text-sm font-semibold">掲載社数<span class="text-[#1d4ed8]" style="font-size: 1.15em;">{{ $companyCount ?? 0 }}</span>社</a>
                <a href="{{ route('news.index') }}" class="py-3 text-blue-950 hover:opacity-80 transition-opacity text-sm font-semibold">ニュース・記事</a>
                <a href="{{ route('reviews.index') }}" class="py-3 text-blue-950 hover:opacity-80 transition-opacity text-sm font-semibold">口コミを書く</a>
                <a href="{{ route('quote-data.create') }}" class="py-3 text-blue-950 hover:opacity-80 transition-opacity text-sm font-semibold">見積もりデータを登録</a>
            </div>
        </div>
    </nav>

    {{-- ヘッダー下のボタンセクション - 現調依頼するボタンはPC・スマホともヘッダー内に移動したため不要に。
    見積もりデータを見るボタンは遷移先エラーのため非表示のまま。
    <div class="bg-white shadow-sm border-b">
        <div class="w-full">
            <div class="grid grid-cols-1 gap-0">
                <a href="{{ route('quote-data.index') }}" class="bg-green-600 text-white px-6 py-3 font-bold text-lg hover:bg-green-700 transition-colors text-center flex items-center justify-center">
                    見積もりデータを見る
                </a>
            </div>
        </div>
    </div>
    --}}

    <main class="pb-16 md:pb-0">
        @yield('content')
    </main>

    <footer class="mt-20 pb-16 md:pb-0" style="background-color: #2563eb; color: white;">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <!-- フッター項目 -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-8 mb-8">
                <!-- サービス内容 -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">サービス内容</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('companies.index', ['service' => 'window']) }}" class="text-white hover:text-blue-200 transition-colors">窓ガラス清掃</a></li>
                        <li><a href="{{ route('companies.index', ['service' => 'exterior']) }}" class="text-white hover:text-blue-200 transition-colors">外壁清掃</a></li>
                        <li><a href="{{ route('companies.index', ['service' => 'inspection']) }}" class="text-white hover:text-blue-200 transition-colors">外壁調査</a></li>
                        <li><a href="{{ route('companies.index', ['service' => 'sign']) }}" class="text-white hover:text-blue-200 transition-colors">看板清掃</a></li>
                        <li><a href="{{ route('companies.index', ['service' => 'other']) }}" class="text-white hover:text-blue-200 transition-colors">その他</a></li>
                        <li><a href="{{ route('hub.category', 'window-cleaning') }}" class="text-white hover:text-blue-200 transition-colors">窓ガラス清掃業者を比較</a></li>
                        <li><a href="{{ route('hub.category', 'wall-repair') }}" class="text-white hover:text-blue-200 transition-colors">外壁補修業者一覧</a></li>
                    </ul>
                </div>

                <!-- 条件で絞り込む -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">条件で絞り込む</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('hub.category', 'commercial-facility') }}" class="text-white hover:text-blue-200 transition-colors">商業施設対応</a></li>
                        <li><a href="{{ route('hub.category', 'high-rise') }}" class="text-white hover:text-blue-200 transition-colors">高層ビル対応</a></li>
                        <li><a href="{{ route('hub.category', 'gondola') }}" class="text-white hover:text-blue-200 transition-colors">ゴンドラ対応</a></li>
                        <li><a href="{{ route('hub.category', 'night-work') }}" class="text-white hover:text-blue-200 transition-colors">夜間対応</a></li>
                        <li><a href="{{ route('hub.category', 'hotel') }}" class="text-white hover:text-blue-200 transition-colors">ホテル対応</a></li>
                        <li><a href="{{ route('hub.category', 'scaffold-work') }}" class="text-white hover:text-blue-200 transition-colors">足場対応</a></li>
                        <li><a href="{{ route('hub.category', 'weekend') }}" class="text-white hover:text-blue-200 transition-colors">土日対応</a></li>
                        <li><a href="{{ route('hub.category', 'emergency') }}" class="text-white hover:text-blue-200 transition-colors">緊急・即日対応</a></li>
                        <li><a href="{{ route('hub.category', 'after-service') }}" class="text-white hover:text-blue-200 transition-colors">アフターサービス充実</a></li>
                        <li><a href="{{ route('hub.category', 'medical-facility') }}" class="text-white hover:text-blue-200 transition-colors">医療施設対応</a></li>
                        <li><a href="{{ route('hub.category', '24-hour') }}" class="text-white hover:text-blue-200 transition-colors">24時間対応</a></li>
                        <li><a href="{{ route('hub.category', 'iso-certified') }}" class="text-white hover:text-blue-200 transition-colors">ISO認証取得</a></li>
                        <li><a href="{{ route('hub.category', 'eco-friendly') }}" class="text-white hover:text-blue-200 transition-colors">環境配慮</a></li>
                    </ul>
                </div>

                <!-- 都道府県から探す -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">都道府県から探す</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('area.show', 'tokyo') }}" class="text-white hover:text-blue-200 transition-colors">東京都</a></li>
                        <li><a href="{{ route('area.show', 'osaka') }}" class="text-white hover:text-blue-200 transition-colors">大阪府</a></li>
                        <li><a href="{{ route('area.show', 'kanagawa') }}" class="text-white hover:text-blue-200 transition-colors">神奈川県</a></li>
                        <li><a href="{{ route('area.show', 'aichi') }}" class="text-white hover:text-blue-200 transition-colors">愛知県</a></li>
                        <li><a href="{{ route('area.show', 'saitama') }}" class="text-white hover:text-blue-200 transition-colors">埼玉県</a></li>
                        <li><a href="{{ route('area.show', 'chiba') }}" class="text-white hover:text-blue-200 transition-colors">千葉県</a></li>
                        <li><a href="{{ route('area.show', 'fukuoka') }}" class="text-white hover:text-blue-200 transition-colors">福岡県</a></li>
                        <li><a href="{{ route('area.show', 'mie') }}" class="text-white hover:text-blue-200 transition-colors">三重県</a></li>
                        <li><a href="{{ route('area.show', 'gifu') }}" class="text-white hover:text-blue-200 transition-colors">岐阜県</a></li>
                        <li><a href="{{ route('area.show', 'shizuoka') }}" class="text-white hover:text-blue-200 transition-colors">静岡県</a></li>
                        <li><a href="{{ route('area.show', 'ibaraki') }}" class="text-white hover:text-blue-200 transition-colors">茨城県</a></li>
                        <li><a href="{{ route('area.show', 'hyogo') }}" class="text-white hover:text-blue-200 transition-colors">兵庫県</a></li>
                        <li><a href="{{ route('companies.index') }}" class="text-white hover:text-blue-200 transition-colors">すべての都道府県</a></li>
                    </ul>
                </div>

                <!-- サポート -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">サポート</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('quote.create') }}" class="text-white hover:text-blue-200 transition-colors">お見積もり相談</a></li>
                        <li><a href="{{ route('reviews.index') }}" class="text-white hover:text-blue-200 transition-colors">口コミを書く</a></li>
                        <li><a href="{{ route('quote-data.create') }}" class="text-white hover:text-blue-200 transition-colors">見積もりデータを登録</a></li>
                        <li><a href="#" class="text-white hover:text-blue-200 transition-colors">よくある質問</a></li>
                        <li><a href="{{ route('contact.create') }}" class="text-white hover:text-blue-200 transition-colors">お問い合わせ</a></li>
                        <li><a href="#" class="text-white hover:text-blue-200 transition-colors">ヘルプ</a></li>
                    </ul>
                </div>

                <!-- 運営情報 -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">運営情報</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-white hover:text-blue-200 transition-colors">会社概要</a></li>
                        <li><a href="#" class="text-white hover:text-blue-200 transition-colors">プライバシーポリシー</a></li>
                        <li><a href="#" class="text-white hover:text-blue-200 transition-colors">利用規約</a></li>
                        <li><a href="#" class="text-white hover:text-blue-200 transition-colors">免責事項</a></li>
                        <li><a href="#" class="text-white hover:text-blue-200 transition-colors">サイトマップ</a></li>
                    </ul>
                </div>
            </div>

            <!-- コピーライト -->
            <div class="border-t pt-8 text-center" style="border-color: #3b82f6;">
                <p class="text-white">&copy; 2026 オヤズナ. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- スマホ版：画面下部固定の現調依頼CTA（黒帯の中に青ボタンが浮く形） -->
    <div class="md:hidden fixed bottom-0 inset-x-0 z-50 flex justify-center bg-black/50 px-3 py-2" style="position: fixed !important;">
        <a
            href="{{ route('quote.create') }}"
            class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 transition-colors text-white font-bold text-base px-6 py-2 glowing-button"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" />
                <path d="M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
            </svg>
            無料で現調依頼する
        </a>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    
    @yield('scripts')
</body>
</html>