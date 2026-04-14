<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'オヤズナ｜高所ロープ業者の口コミ・比較・見積もりサイト【窓ガラス清掃・外壁補修・塗装】')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=BIZ+UDGothic:wght@400;700&family=M+PLUS+Rounded+1c:wght@100;300;400;500;700;800;900&family=Inter:wght@300;400;500;600;700&family=M+PLUS+1+Code:wght@100;200;300;400;500;600;700&family=Zen+Kaku+Gothic+New:wght@300;400;500;700;900&family=Zen+Maru+Gothic:wght@300;400;500;700;900&family=Kosugi+Maru&family=Shippori+Antique+B1&family=Sawarabi+Gothic&family=M+PLUS+1p:wght@100;300;400;500;700;800;900&family=Klee+One:wght@400;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="description" content="@yield('description', '高所ロープ作業の専門業者を口コミと実績で比較できるサイトです。窓ガラス清掃、外壁補修・塗装、鳥害対策などの高所作業に対応。安心・信頼できる業者選びをサポートします。')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
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
    <nav class="bg-white shadow-sm border-b overflow-visible">
        <!-- Mobile Logo - スマホで上部に表示 -->
        <div class="md:hidden text-center py-3 border-b max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ url('/') }}">
                <img
                    src="{{ asset('images/cremoba_logo.png') }}"
                    alt="オヤズナ"
                    class="h-8 w-auto object-contain mx-auto"
                />
            </a>
        </div>

        <!-- PC版ナビゲーション -->
        <div class="hidden md:flex h-20 overflow-visible w-full">
            <!-- ロゴ部分 -->
            <div class="flex items-center overflow-visible" style="padding-left: 80px;">
                <a href="{{ url('/') }}" class="nav-logo flex items-center gap-2">
                    <img
                        src="{{ asset('images/cremoba_logo.png') }}"
                        alt="オヤズナ"
                        class="h-12 w-auto object-contain shrink-0 block"
                        style="display: block !important;"
                    />
                </a>
            </div>
            
            <!-- 中央のナビゲーションメニュー -->
            <div class="flex items-center flex-1 justify-center space-x-6 h-20">
                <a href="{{ route('companies.index') }}" class="text-gray-700 hover:text-blue-600 font-bold text-xl flex items-center h-full">業者一覧</a>
                <a href="{{ route('news.index') }}" class="text-gray-700 hover:text-blue-600 font-bold text-xl flex items-center h-full">ニュース・記事</a>
                <a href="{{ route('reviews.index') }}" class="text-gray-700 hover:text-blue-600 font-bold text-xl flex items-center h-full">口コミを書く</a>
                <span class="bg-blue-100 text-blue-800 px-5 py-3 rounded-full text-lg font-bold flex items-center">
                    掲載社数{{ $companyCount ?? 0 }}社
                </span>
            </div>
            
            <!-- 右端のボタン -->
            <a href="{{ route('quote.create') }}" class="bg-orange-600 text-white px-8 font-bold text-xl hover:bg-orange-700 transition-colors flex items-center justify-center h-20 flex-shrink-0 glowing-button">
                専門業者に相談する
            </a>
        </div>

        <!-- スマホ版ナビゲーション -->
        <div class="md:hidden max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- 上段：メニューリンク -->
            <div class="flex justify-center items-center space-x-4 py-3">
                <a href="{{ route('companies.index') }}" class="text-gray-700 hover:text-blue-600 text-sm font-semibold">業者一覧</a>
                <a href="{{ route('news.index') }}" class="text-gray-700 hover:text-blue-600 text-sm font-semibold">ニュース・記事</a>
                <a href="{{ route('reviews.index') }}" class="text-gray-700 hover:text-blue-600 text-sm font-semibold">口コミを書く</a>
                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-semibold">
                    掲載社数{{ $companyCount ?? 0 }}社
                </span>
            </div>
            <!-- 下段：専門業者ボタン -->
            <div class="flex justify-center pb-3">
                <a href="{{ route('quote.create') }}" class="bg-orange-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-orange-700 transition-colors text-sm">
                    専門業者に相談する
                </a>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="mt-20" style="background-color: #2563eb; color: white;">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <!-- フッター項目 -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <!-- サービス内容 -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">サービス内容</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('companies.index', ['service' => 'window']) }}" class="text-white hover:text-blue-200 transition-colors">窓ガラス清掃</a></li>
                        <li><a href="{{ route('companies.index', ['service' => 'exterior']) }}" class="text-white hover:text-blue-200 transition-colors">外壁清掃</a></li>
                        <li><a href="{{ route('companies.index', ['service' => 'inspection']) }}" class="text-white hover:text-blue-200 transition-colors">外壁調査</a></li>
                        <li><a href="{{ route('companies.index', ['service' => 'sign']) }}" class="text-white hover:text-blue-200 transition-colors">看板清掃</a></li>
                        <li><a href="{{ route('companies.index', ['service' => 'other']) }}" class="text-white hover:text-blue-200 transition-colors">その他</a></li>
                    </ul>
                </div>

                <!-- 都道府県から探す -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">都道府県から探す</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('companies.index', ['prefecture' => 'tokyo']) }}" class="text-white hover:text-blue-200 transition-colors">東京都</a></li>
                        <li><a href="{{ route('companies.index', ['prefecture' => 'osaka']) }}" class="text-white hover:text-blue-200 transition-colors">大阪府</a></li>
                        <li><a href="{{ route('companies.index', ['prefecture' => 'kanagawa']) }}" class="text-white hover:text-blue-200 transition-colors">神奈川県</a></li>
                        <li><a href="{{ route('companies.index', ['prefecture' => 'aichi']) }}" class="text-white hover:text-blue-200 transition-colors">愛知県</a></li>
                        <li><a href="{{ route('companies.index') }}" class="text-white hover:text-blue-200 transition-colors">すべての都道府県</a></li>
                    </ul>
                </div>

                <!-- サポート -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">サポート</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('quote.create') }}" class="text-white hover:text-blue-200 transition-colors">お見積もり相談</a></li>
                        <li><a href="{{ route('reviews.index') }}" class="text-white hover:text-blue-200 transition-colors">口コミを書く</a></li>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    
    @yield('scripts')
</body>
</html>