@extends('layouts.app')

@section('title', $article->title . ' - オヤズナ')

@section('content')
<div class="max-w-7xl mx-auto px-2 md:px-4 py-4 md:py-8 overflow-x-hidden">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('home') }}" class="hover:text-blue-600">ホーム</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ route('news.index') }}" class="hover:text-blue-600">ニュース・記事</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-900">{{ Str::limit($article->title, 30) }}</li>
        </ol>
    </nav>

    <!-- Main Content with Sidebar -->
    <div class="lg:grid lg:grid-cols-3 lg:gap-8">
        <!-- 左：記事本文 -->
        <div class="lg:col-span-2">
            <article class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-4 md:p-8 pb-0">
                    <!-- Article Meta -->
                    <div class="mb-4">
                        <time class="text-sm text-gray-500">{{ $article->published_at->format('Y年n月j日') }}</time>
                    </div>

                    <!-- Article Title -->
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6 leading-tight">{{ $article->title }}</h1>
                </div>

                @if($article->featured_image_url)
                    <div class="aspect-video overflow-hidden mx-4 md:mx-8 mb-6 rounded-lg">
                        <img src="{{ $article->featured_image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                    </div>
                @endif

                <div class="p-4 md:p-8 pt-0">
                    <!-- Supervisor Information -->
                    @if($article->supervisor_name)
                    <div class="mb-8 bg-gray-50 border border-gray-200 rounded-lg p-6">
                        <div class="flex items-start gap-4">
                            @if($article->supervisor_avatar)
                                <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-gray-200 flex-shrink-0">
                                    <img src="{{ asset('storage/' . $article->supervisor_avatar) }}" 
                                         alt="{{ $article->supervisor_name }}" 
                                         class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="w-20 h-20 rounded-full bg-blue-100 border-2 border-gray-200 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                            @endif
                            
                            <div class="flex-1">
                                <div class="text-xs text-gray-500 mb-1">監修者</div>
                                <h3 class="text-base font-medium text-gray-900 mb-1">{{ $article->supervisor_name }}</h3>
                                @if($article->supervisor_title)
                                    <p class="text-blue-600 font-normal text-xs mb-2">{{ $article->supervisor_title }}</p>
                                @endif
                                @if($article->supervisor_description)
                                    <div class="supervisor-description">
                                        <p class="text-gray-700 text-xs leading-relaxed supervisor-text-preview" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                            {!! nl2br(e($article->supervisor_description)) !!}
                                        </p>
                                        <p class="text-gray-700 text-xs leading-relaxed supervisor-text-full" style="display: none;">
                                            {!! nl2br(e($article->supervisor_description)) !!}
                                        </p>
                                        <button onclick="toggleSupervisorDescription(this)" class="text-blue-600 hover:text-blue-800 text-xs mt-2 flex items-center gap-1 supervisor-toggle">
                                            <span class="toggle-text">続きを見る</span>
                                            <svg class="w-3 h-3 toggle-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Table of Contents Toggle -->
                    <div id="toc-container" class="mb-8" style="display: none;">
                        <button id="toc-toggle" class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 16a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                                </svg>
                            </div>
                            <span>目次</span>
                            <span id="toc-button-text" class="bg-gray-600 text-white px-2 py-1 rounded text-sm">表示</span>
                        </button>
                        
                        <!-- Table of Contents -->
                        <div id="table-of-contents" class="bg-gray-50 border border-gray-200 rounded-lg p-4 mt-4" style="display: none;">
                            <ul id="toc-list" class="space-y-2 text-sm"></ul>
                        </div>
                    </div>

                    <!-- カスタムCSS出力 -->
                    @if($article->custom_css)
                        <style>
                            /* Article {{ $article->id }} Custom CSS */
                            {!! $article->scoped_custom_css !!}
                        </style>
                    @endif

                    <!-- テスト用強制CSS（一時的） -->
                    <style>
                        .article-content .check-point-box {
                            background: linear-gradient(135deg, #e8f5e8 0%, #f0fff0 100%) !important;
                            border: 2px solid #4caf50 !important;
                            border-radius: 12px !important;
                            padding: 20px !important;
                            margin: 30px 0 !important;
                            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.15) !important;
                            position: relative !important;
                        }

                        .article-content .check-point-header {
                            display: flex !important;
                            align-items: center !important;
                            margin-bottom: 15px !important;
                            padding-bottom: 10px !important;
                            border-bottom: 1px solid #c8e6c9 !important;
                        }

                        .article-content .check-icon {
                            background: #4caf50 !important;
                            color: white !important;
                            width: 30px !important;
                            height: 30px !important;
                            border-radius: 50% !important;
                            display: flex !important;
                            align-items: center !important;
                            justify-content: center !important;
                            font-weight: bold !important;
                            font-size: 18px !important;
                            margin-right: 12px !important;
                            flex-shrink: 0 !important;
                        }

                        .article-content .check-point-header h3 {
                            margin: 0 !important;
                            color: #2e7d32 !important;
                            font-size: 18px !important;
                            font-weight: 700 !important;
                            background: none !important;
                            border: none !important;
                            padding: 0 !important;
                            box-shadow: none !important;
                            border-left: none !important;
                        }

                        .article-content .check-list {
                            list-style: none !important;
                            padding: 0 !important;
                            margin: 0 !important;
                        }

                        .article-content .check-list li {
                            position: relative !important;
                            padding-left: 25px !important;
                            margin: 12px 0 !important;
                            line-height: 1.7 !important;
                            color: #2c2c2c !important;
                            font-size: 15px !important;
                        }

                        .article-content .check-list li::before {
                            content: "✓" !important;
                            position: absolute !important;
                            left: 0 !important;
                            top: 2px !important;
                            color: #4caf50 !important;
                            font-weight: bold !important;
                            font-size: 16px !important;
                            width: 18px !important;
                            height: 18px !important;
                            display: flex !important;
                            align-items: center !important;
                            justify-content: center !important;
                        }

                        /* targetlist_21のスタイル */
                        .article-content .targetlist_21 {
                            position: relative !important;
                            margin: 2em 0 !important;
                            padding: 10px 20px 0px 20px !important;
                            background: #f0f7ff !important;
                            border: 2px solid #5b8bd0 !important;
                        }

                        .article-content .targetlist_21 span,
                        .targetlist_21 span,
                        div.targetlist_21 span {
                            position: absolute !important;
                            top: -20px !important;
                            left: 15px !important;
                            background: #5b8bd0 !important;
                            padding: 12px 30px !important;
                            border-radius: 20px !important;
                            font-weight: bold !important;
                            font-size: 18px !important;
                            box-shadow: 1px 1px 2px rgba(0,0,0,.3) !important;
                            color: white !important;
                            min-height: 24px !important;
                            display: flex !important;
                            align-items: center !important;
                            z-index: 10 !important;
                        }

                        /* インラインスタイルも上書き */
                        .targetlist_21[style] span[style] {
                            position: absolute !important;
                            top: -20px !important;
                            left: 15px !important;
                            background: #5b8bd0 !important;
                            padding: 12px 30px !important;
                            border-radius: 20px !important;
                            font-weight: bold !important;
                            font-size: 18px !important;
                            box-shadow: 1px 1px 2px rgba(0,0,0,.3) !important;
                            color: white !important;
                            min-height: 24px !important;
                            display: flex !important;
                            align-items: center !important;
                            z-index: 10 !important;
                        }

                        .article-content .targetlist_21 ul {
                            list-style: none !important;
                            padding: 0 !important;
                            margin: 0 !important;
                        }

                        .article-content .targetlist_21 li {
                            padding: 0 !important;
                            margin: 8px 0 !important;
                            line-height: 1.6 !important;
                        }

                        .article-content .targetlist_21 li::before {
                            display: none !important;
                        }
                    </style>



                    <!-- Article Content -->
                    <div class="prose prose-lg max-w-none article-content article-content-{{ $article->id }} overflow-hidden">
                        {!! \App\Support\ContentShortcode::render($article->rendered_content) !!}
                    </div>
                </div>
            </article>

            <!-- Navigation -->
            <div class="mt-8 flex flex-col md:flex-row md:justify-between md:items-center space-y-4 md:space-y-0">
                <a href="{{ route('news.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    記事一覧に戻る
                </a>

                <!-- Social Share Buttons -->
                <div class="flex justify-center space-x-2">
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(request()->url()) }}" 
                       target="_blank" 
                       class="inline-flex items-center px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded text-sm transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"></path>
                        </svg>
                        ツイート
                    </a>
                    
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                       target="_blank" 
                       class="inline-flex items-center px-3 py-2 bg-blue-700 hover:bg-blue-800 text-white rounded text-sm transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M20 10C20 4.477 15.523 0 10 0S0 4.477 0 10c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V10h2.54V7.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V10h2.773l-.443 2.89h-2.33v6.988C16.343 19.128 20 14.991 20 10z" clip-rule="evenodd"></path>
                        </svg>
                        シェア
                    </a>
                </div>
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
                    <h4 class="text-lg font-bold mb-2">窓ガラス清掃を依頼したい？</h4>
                    <p class="text-sm mb-4">最適な業者をお探しします</p>
                    <a href="{{ route('quote.create') }}" class="flex items-center justify-center gap-2 bg-white text-orange-600 px-6 py-4 rounded-lg font-bold text-lg hover:bg-gray-50 hover:shadow-lg transition-all duration-200 transform hover:scale-105 border-2 border-orange-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.959 8.959 0 01-4.906-1.451c-.302-.163-.622-.35-.963-.589L4 20l1.729-3.131c-.27-.476-.547-.949-.826-1.448C3.639 13.644 3 11.904 3 12c0-4.418 3.582-8 8-8s8 3.582 8 8z"/>
                        </svg>
                        専門業者に相談する
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
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

            @if($featuredArticles->count() > 0)
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-bold mb-6 text-blue-600">
                    おすすめ記事
                </h2>

                <div class="space-y-6">
                    @foreach($featuredArticles as $item)
                        <a href="{{ $item['url'] }}"
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
                                    {{ $item['title'] }}
                                </div>
                            </div>

                        </a>
                    @endforeach
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

    <style>
        /* 記事内のh2スタイル */
        .article-content h2 {
            background: #dfefff;
            box-shadow: 0px 0px 0px 5px #dfefff;
            border: dashed 0.5px #96c2fe;
            padding: 0.6em 0.5em 0.4em;
            color: #454545;
            margin: 1.5em 0;
            position: relative;
            line-height: 1.4;
            display: block;
        }
        
        /* 記事内のh3スタイル */
        .article-content h3 {
            border-left: 4px solid #3b82f6;
            padding: 0.8em 1em;
            background: #f8fafc;
            color: #1f2937;
            margin: 1.5em 0;
            font-weight: 600;
        }
        
        /* 記事内の画像のクリック機能を無効化 */
        .article-content img {
            cursor: default !important;
            pointer-events: none !important;
        }
        
        /* 画像の境界線やボックスシャドウを無効化 */
        .article-content img {
            border: none !important;
            box-shadow: none !important;
            transition: none !important;
        }
        
        /* 画像のサイズ調整（PC用） */
        .article-content img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 1rem auto;
            object-fit: contain;
            width: auto;
        }
        
        /* スマホでの記事コンテナとパディング調整 */
        @media (max-width: 767px) {
            /* HTML全体の制御 */
            html, body {
                overflow-x: hidden !important;
                max-width: 100vw !important;
            }
            
            /* 記事コンテンツ内の全要素 */
            .article-content,
            .article-content *,
            .article-content img,
            .article-content div,
            .article-content p,
            .article-content figure {
                max-width: 85vw !important;
                overflow-x: hidden !important;
                word-wrap: break-word !important;
            }
            
            /* 画像の特別制御 */
            .article-content img {
                width: 85vw !important;
                height: auto !important;
                margin: 1rem auto !important;
                display: block !important;
                object-fit: contain !important;
                border: none !important;
            }
            
            /* 記事全体のコンテナ調整 */
            .max-w-7xl {
                max-width: 100vw !important;
                padding: 0 0.5rem !important;
                overflow-x: hidden !important;
            }
            
            .lg\\:col-span-2 {
                overflow-x: hidden !important;
                width: 100vw !important;
                max-width: 100vw !important;
            }
            
            /* 記事カード自体の調整 */
            .bg-white.rounded-lg.shadow-md {
                overflow: hidden !important;
                margin: 0 !important;
                width: 95vw !important;
                max-width: 95vw !important;
            }
            
            /* 記事コンテンツのパディング調整 */
            .bg-white.rounded-lg.shadow-md > div {
                padding: 0.75rem !important;
                overflow-x: hidden !important;
            }
            
            /* proseクラスの制御 */
            .prose {
                max-width: none !important;
                overflow-x: hidden !important;
            }
            
            /* テーブルや図表の制御 */
            table, figure, .figure {
                max-width: 85vw !important;
                overflow-x: auto !important;
                display: block !important;
            }
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

        /* おすすめ記事のline-clamp */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* 監修者説明文の切り替えアイコン */
        .supervisor-toggle .toggle-icon {
            transition: transform 0.3s ease;
        }

        /* 記事内の表スタイル */
        .article-content table {
            margin: 1.5em 0;
            border-collapse: collapse;
            width: 100%;
            max-width: 100%;
            background-color: white !important;
        }

        .article-content table th,
        .article-content table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #e5e7eb;
            background-color: white !important;
        }

        /* 最初の行（ヘッダー行）のみ薄いグレー */
        .article-content table tr:first-child th,
        .article-content table tr:first-child td,
        .article-content table thead tr th,
        .article-content table thead tr td {
            background-color: #f3f4f6 !important;
            font-weight: 600 !important;
            color: #374151 !important;
        }

        /* 他のすべての行を白背景に強制 */
        .article-content table tr:not(:first-child) th,
        .article-content table tr:not(:first-child) td,
        .article-content table tbody tr th,
        .article-content table tbody tr td {
            background-color: white !important;
            font-weight: normal !important;
        }

        /* 縞模様や他のスタイルを完全に無効化 */
        .article-content table tr:nth-child(even) th,
        .article-content table tr:nth-child(even) td,
        .article-content table tr:nth-child(odd) th,
        .article-content table tr:nth-child(odd) td,
        .article-content table tbody tr:nth-child(even) th,
        .article-content table tbody tr:nth-child(even) td,
        .article-content table tbody tr:nth-child(odd) th,
        .article-content table tbody tr:nth-child(odd) td {
            background-color: white !important;
        }

        /* 最初の行だけは再度グレーに */
        .article-content table tr:first-child:nth-child(1) th,
        .article-content table tr:first-child:nth-child(1) td {
            background-color: #f3f4f6 !important;
            font-weight: 600 !important;
        }

        /* 記事内のカスタムボタンスタイル - 大きなフルサイズボタン */
        .article-content a.bg-blue-500 {
            background-color: #3b82f6 !important;
            color: white !important;
            text-decoration: none !important;
            border-radius: 12px !important;
            font-weight: 700 !important;
            font-size: 18px !important;
            padding: 20px 40px !important;
            margin: 2rem 0 !important;
            text-align: center !important;
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3) !important;
            border: none !important;
            position: relative !important;
            overflow: hidden !important;
        }

        .article-content a.bg-blue-500:hover {
            background-color: #1d4ed8 !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4) !important;
        }

        .article-content a.bg-blue-500:active {
            transform: translateY(0) !important;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3) !important;
        }

        .article-content a.bg-green-500 {
            background-color: #10b981 !important;
            color: white !important;
        }

        .article-content a.bg-green-500:hover {
            background-color: #047857 !important;
        }

        .article-content a.bg-orange-500 {
            background-color: #f59e0b !important;
            color: white !important;
            text-decoration: none !important;
            border-radius: 12px !important;
            font-weight: 700 !important;
            font-size: 18px !important;
            padding: 20px 40px !important;
            margin: 2rem 0 !important;
            text-align: center !important;
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3) !important;
            border: none !important;
            position: relative !important;
            overflow: hidden !important;
        }

        .article-content a.bg-orange-500:hover {
            background-color: #d97706 !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.4) !important;
        }

        .article-content a.bg-orange-500:active {
            transform: translateY(0) !important;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3) !important;
        }

        .article-content a.bg-red-500 {
            background-color: #ef4444 !important;
            color: white !important;
        }

        .article-content a.bg-red-500:hover {
            background-color: #dc2626 !important;
        }

        .article-content a.bg-gray-500 {
            background-color: #6b7280 !important;
            color: white !important;
        }

        .article-content a.bg-gray-500:hover {
            background-color: #4b5563 !important;
        }

        /* レスポンシブ表対応 */
        @media (max-width: 767px) {
            .article-content table {
                font-size: 0.875rem;
            }
            
            .article-content table th,
            .article-content table td {
                padding: 8px 6px;
            }
        }

        /* マーカー機能のスタイル */
        .article-content mark,
        .article-content .marker {
            background-color: #fef08a;
            color: #1f2937;
            padding: 2px 0;
            border-radius: 2px;
        }


        /* 比較表スタイル（km-接頭辞） */
        .article-content .km-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .article-content .km-table th {
            background-color: #3b82f6;
            color: white;
            font-weight: 600;
            padding: 16px;
            text-align: left;
        }

        .article-content .km-table td {
            padding: 16px;
            border-bottom: 1px solid #e5e7eb;
        }

        .article-content .km-table td:first-child {
            background-color: #f8fafc;
            font-weight: 600;
            color: #1f2937;
            width: 25%;
        }

        .article-content .km-table tr:last-child td {
            border-bottom: none;
        }

        .article-content .km-table tbody tr:hover {
            background-color: #f1f5f9;
        }

        /* レスポンシブ対応（km-table） */
        @media (max-width: 767px) {
            .article-content .km-cta {
                padding: 10px 20px;
                font-size: 14px;
            }

            .article-content .km-table {
                font-size: 14px;
            }

            .article-content .km-table th,
            .article-content .km-table td {
                padding: 12px 8px;
            }

            .article-content .km-table td:first-child {
                width: 30%;
            }
        }
    </style>


<script src="{{ asset('js/frontend-table-fix.js') }}"></script>
<script>
// 記事内の画像のクリック機能を完全に無効化 + モバイル対応
document.addEventListener('DOMContentLoaded', function() {
    const articleImages = document.querySelectorAll('.article-content img');
    
    function resizeImages() {
        const isMobile = window.innerWidth <= 767;
        
        articleImages.forEach(function(img) {
            // 全てのイベントリスナーを削除
            img.onclick = null;
            img.removeAttribute('data-click-handler');
            img.removeAttribute('data-resize-enabled');
            
            // クリックイベントを完全に防止
            img.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                return false;
            }, true);
            
            // スタイルをリセット
            img.style.cursor = 'default';
            img.style.border = 'none';
            img.style.boxShadow = 'none';
            img.style.transition = 'none';
            
            // モバイルでの強制サイズ調整
            if (isMobile) {
                img.style.maxWidth = '85vw';
                img.style.width = '85vw';
                img.style.height = 'auto';
                img.style.display = 'block';
                img.style.margin = '1rem auto';
                img.style.objectFit = 'contain';
                img.style.setProperty('max-width', '85vw', 'important');
                img.style.setProperty('width', '85vw', 'important');
            } else {
                // PC用：通常のスタイルに戻す
                img.style.maxWidth = '100%';
                img.style.width = 'auto';
                img.style.height = 'auto';
                img.style.display = 'block';
                img.style.margin = '1rem auto';
                img.style.objectFit = 'contain';
                img.style.removeProperty('max-width');
                img.style.removeProperty('width');
            }
        });
    }
    
    // 初回実行
    resizeImages();
    
    // リサイズ時にも実行
    window.addEventListener('resize', resizeImages);
});

// 目次生成機能
document.addEventListener('DOMContentLoaded', function() {
    generateTableOfContents();
});

// 監修者説明文の表示切り替え機能
function toggleSupervisorDescription(button) {
    const container = button.closest('.supervisor-description');
    const preview = container.querySelector('.supervisor-text-preview');
    const full = container.querySelector('.supervisor-text-full');
    const toggleText = button.querySelector('.toggle-text');
    const toggleIcon = button.querySelector('.toggle-icon');
    
    if (preview.style.display === 'none') {
        // 全文表示から省略表示に戻す
        preview.style.display = '-webkit-box';
        full.style.display = 'none';
        toggleText.textContent = '続きを見る';
        toggleIcon.style.transform = 'rotate(0deg)';
    } else {
        // 省略表示から全文表示に切り替え
        preview.style.display = 'none';
        full.style.display = 'block';
        toggleText.textContent = '閉じる';
        toggleIcon.style.transform = 'rotate(180deg)';
    }
}

function generateTableOfContents() {
    const articleContent = document.querySelector('.article-content');
    const tocContainer = document.getElementById('toc-container');
    const tocList = document.getElementById('toc-list');
    const tocToggle = document.getElementById('toc-toggle');
    const tocButtonText = document.getElementById('toc-button-text');
    const tableOfContents = document.getElementById('table-of-contents');
    
    if (!articleContent || !tocContainer || !tocList) return;
    
    // h2, h3要素を取得
    const headings = articleContent.querySelectorAll('h2, h3');
    
    if (headings.length === 0) {
        return; // 見出しがない場合は目次を表示しない
    }
    
    let tocHTML = '';
    let counter = 1;
    
    headings.forEach((heading, index) => {
        // 見出しにIDを設定（既存のIDがない場合のみ）
        if (!heading.id) {
            heading.id = `heading-${index + 1}`;
        }
        
        const level = heading.tagName.toLowerCase();
        const text = heading.textContent.trim();
        
        if (level === 'h2') {
            tocHTML += `
                <li class="border-b border-gray-200 pb-2">
                    <a href="#${heading.id}" class="block text-blue-600 hover:text-blue-800 font-medium py-1 transition-colors">
                        ${counter}. ${text}
                    </a>
                </li>
            `;
            counter++;
        } else if (level === 'h3') {
            tocHTML += `
                <li class="ml-4">
                    <a href="#${heading.id}" class="block text-gray-600 hover:text-gray-800 py-1 transition-colors">
                        • ${text}
                    </a>
                </li>
            `;
        }
    });
    
    if (tocHTML) {
        tocList.innerHTML = tocHTML;
        tocContainer.style.display = 'block';
        
        // 目次の表示/非表示切り替え
        let isVisible = false;
        tocToggle.addEventListener('click', function() {
            isVisible = !isVisible;
            if (isVisible) {
                tableOfContents.style.display = 'block';
                tocButtonText.textContent = '非表示';
                tocButtonText.className = 'bg-red-600 text-white px-2 py-1 rounded text-sm';
            } else {
                tableOfContents.style.display = 'none';
                tocButtonText.textContent = '表示';
                tocButtonText.className = 'bg-gray-600 text-white px-2 py-1 rounded text-sm';
            }
        });
        
        // スムーススクロールを追加
        tocList.addEventListener('click', function(e) {
            if (e.target.tagName === 'A') {
                e.preventDefault();
                const targetId = e.target.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    }
}
</script>
@endsection