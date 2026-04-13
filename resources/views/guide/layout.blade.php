@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-2 md:px-4 py-4 md:py-8 overflow-x-hidden">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('home') }}" class="hover:text-blue-600">ホーム</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ route('home') }}" class="hover:text-blue-600">ガイド</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-900">
                @if($page && $page->title)
                    {{ Str::limit($page->title, 30) }}
                @else
                    {{ Str::limit($__env->yieldContent('title'), 30) }}
                @endif
            </li>
        </ol>
    </nav>

    <!-- Main Content without Sidebar -->
    <div class="max-w-4xl mx-auto">
        <article class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 md:p-8 pb-0">
                <!-- Article Meta -->
                <div class="mb-4">
                    <time class="text-sm text-gray-500">{{ now()->format('Y年n月j日') }}更新</time>
                </div>

                <!-- Article Title -->
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6 leading-tight">
                    @if($page && $page->title)
                        {{ $page->title }}
                    @else
                        @yield('title')
                    @endif
                </h1>
                
                @if($page && $page->description)
                    <div class="mb-6">
                        <p class="text-lg text-gray-600">{{ $page->description }}</p>
                    </div>
                @elseif(trim($__env->yieldContent('description')))
                    <div class="mb-6">
                        <p class="text-lg text-gray-600">@yield('description')</p>
                    </div>
                @endif
            </div>

            @if($page && $page->featured_image)
                <div class="aspect-video overflow-hidden mx-4 md:mx-8 mb-6 rounded-lg">
                    <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}" class="w-full h-full object-cover">
                </div>
            @endif

            <div class="p-4 md:p-8 pt-0">
                <!-- Supervisor Information -->
                @if($page && $page->supervisor_name)
                <div class="mb-8 bg-gray-50 border border-gray-200 rounded-lg p-6">
                    <div class="flex items-start gap-4">
                        @if($page->supervisor_avatar)
                            <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-gray-200 flex-shrink-0">
                                <img src="{{ asset('storage/' . $page->supervisor_avatar) }}" 
                                     alt="{{ $page->supervisor_name }}" 
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
                            <h3 class="text-base font-medium text-gray-900 mb-1">{{ $page->supervisor_name }}</h3>
                            @if($page->supervisor_title)
                                <p class="text-blue-600 font-normal text-xs mb-2">{{ $page->supervisor_title }}</p>
                            @endif
                            @if($page->supervisor_description)
                                <div class="supervisor-description">
                                    <p class="text-gray-700 text-xs leading-relaxed supervisor-text-preview" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                        {!! nl2br(e($page->supervisor_description)) !!}
                                    </p>
                                    <p class="text-gray-700 text-xs leading-relaxed supervisor-text-full" style="display: none;">
                                        {!! nl2br(e($page->supervisor_description)) !!}
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
                @if($page && $page->custom_css)
                    <style>
                        /* Page {{ $page->id }} Custom CSS */
                        {!! $page->scoped_custom_css !!}
                    </style>
                @endif

                <!-- Article Content -->
                <div class="prose prose-lg max-w-none article-content article-content-{{ $page->id ?? 'default' }} page-content page-content-{{ $page->id ?? 'default' }} overflow-hidden">
                    @if($page)
                        @if($page->content)
                            @php
                                // Use the page's rendered_content attribute which properly processes custom HTML blocks
                                $content = $page->rendered_content;
                                
                                // Add image processing for all static pages
                                // Debug: add visible marker
                                $staticPageTypes = ['window-cleaning-price-guide', 'window-cleaning-contractor-guide', 'exterior-wall-painting-price-guide', 'exterior-wall-painting-contractor-guide'];
                                
                                if(in_array($page->page_type, $staticPageTypes) || in_array($page->slug, ['window-cleaning-price', 'guide-company-selection', 'wall-painting-price']) || $page->slug === 'guide-company-selection') {
                                    $content = '<!-- PROCESSING STATIC PAGE: ' . $page->slug . ' | ' . $page->page_type . ' -->' . $content;
                                    
                                    // Fix first image with escaped quotes
                                    $content = str_replace(
                                        '<p><img src=\"http://127.0.0.1:8001/storage/articles/k7yOQOdlWcMpjd05ArrpT7SrN7xr0fUjfc7ncZla.png\" alt=\"\"></p>',
                                        '<p><img src="http://127.0.0.1:8001/storage/articles/k7yOQOdlWcMpjd05ArrpT7SrN7xr0fUjfc7ncZla.png" alt="" style="max-width: 100%; height: auto;"></p>',
                                        $content
                                    );
                                    
                                    // Fix first image with width/height attributes (escaped quotes) - direct from contractor-selection page
                                    $content = str_replace(
                                        '<img src=\"http://127.0.0.1:8001/storage/articles/k7yOQOdlWcMpjd05ArrpT7SrN7xr0fUjfc7ncZla.png\" alt=\"\" width=\"223\" height=\"210\">',
                                        '<img src="http://127.0.0.1:8001/storage/articles/k7yOQOdlWcMpjd05ArrpT7SrN7xr0fUjfc7ncZla.png" alt="" style="max-width: 100%; height: auto;">',
                                        $content
                                    );
                                    
                                    // Fix the safety management image with width/height attributes
                                    $content = str_replace(
                                        '<img src="/storage/articles/FcgLaQhA3tmQsGsuulpXUXcmz74QhwQVss6m5C6V.png" alt="" width="223" height="210">',
                                        '<img src="/storage/articles/FcgLaQhA3tmQsGsuulpXUXcmz74QhwQVss6m5C6V.png" alt="" style="max-width: 300px; height: auto;">',
                                        $content
                                    );
                                    
                                    // Fix first image with regular quotes
                                    $content = str_replace(
                                        '<p><img src="http://127.0.0.1:8001/storage/articles/k7yOQOdlWcMpjd05ArrpT7SrN7xr0fUjfc7ncZla.png" alt=""></p>',
                                        '<p><img src="http://127.0.0.1:8001/storage/articles/k7yOQOdlWcMpjd05ArrpT7SrN7xr0fUjfc7ncZla.png" alt="" style="max-width: 100%; height: auto;"></p>',
                                        $content
                                    );
                                    
                                    // Fix second image with escaped quotes in strong tags
                                    $content = str_replace(
                                        '<p><strong><img src=\"http://127.0.0.1:8001/storage/articles/MZ7nlzJ5VIpUnq6RzIRC5STfFq5kgHbHVQSHuPjE.png\" alt=\"\"></strong></p>',
                                        '<p><strong><img src="http://127.0.0.1:8001/storage/articles/MZ7nlzJ5VIpUnq6RzIRC5STfFq5kgHbHVQSHuPjE.png" alt="" style="max-width: 100%; height: auto;"></strong></p>',
                                        $content
                                    );
                                    
                                    // Fix second image with regular quotes in strong tags
                                    $content = str_replace(
                                        '<p><strong><img src="http://127.0.0.1:8001/storage/articles/MZ7nlzJ5VIpUnq6RzIRC5STfFq5kgHbHVQSHuPjE.png" alt=""></strong></p>',
                                        '<p><strong><img src="http://127.0.0.1:8001/storage/articles/MZ7nlzJ5VIpUnq6RzIRC5STfFq5kgHbHVQSHuPjE.png" alt="" style="max-width: 100%; height: auto;"></strong></p>',
                                        $content
                                    );
                                    
                                    // Fix any other images that might be in different formats with comprehensive regex
                                    $content = preg_replace(
                                        '/(<img[^>]*src=["\\\'])(\/storage\/articles\/[^"\\\']*\.(?:png|jpg|jpeg|gif))(["\\\'][^>]*>)/i',
                                        '$1http://127.0.0.1:8001$2$3',
                                        $content
                                    );
                                    
                                    // Apply ContentShortcode processing for comprehensive image handling
                                    $content = \App\Support\ContentShortcode::convertImageUrls($content);
                                    
                                    // Remove hardcoded image styling - let ContentShortcode and CSS handle it
                                    // This was overriding admin-set image dimensions with fixed styles
                                    // Images will now respect admin-set width/height attributes
                                }
                            @endphp
                            {!! $content !!}
                        @else
                            <div class="text-center py-12">
                                <div class="text-gray-500 mb-4">
                                    <svg class="mx-auto h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">コンテンツを準備中です</h3>
                                <p class="text-gray-600">管理者がコンテンツを編集中です。しばらくお待ちください。</p>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-500 mb-4">
                                <svg class="mx-auto h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">ページが見つかりません</h3>
                            <p class="text-gray-600">このガイドページはまだ作成されていません。</p>
                        </div>
                    @endif
                </div>
            </div>
        </article>

        <!-- Navigation -->
        <div class="mt-8 flex flex-col md:flex-row md:justify-between md:items-center space-y-4 md:space-y-0">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                ホームに戻る
            </a>

            <!-- Social Share Buttons -->
            <div class="flex justify-center space-x-2">
                <a href="https://twitter.com/intent/tweet?text={{ urlencode($__env->yieldContent('title')) }}&url={{ urlencode(request()->url()) }}" 
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
</div>

<style>
    /* 記事内のh2スタイル */
    .article-content h2 {
        background: #dfefff;
        box-shadow: 0px 0px 0px 5px #dfefff;
        border: dashed 0.5px #96c2fe;
        padding: 1em 0.5em 0.8em;
        color: #454545;
        margin: 1.5em 0;
        position: relative;
        line-height: 1.6;
        display: block;
        min-height: 2em;
        box-sizing: border-box;
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
    
    /* 記事内のh4スタイル */
    .article-content h4 {
        border-left: 2px solid #6b7280;
        padding: 0.6em 0.8em;
        background: #f9fafb;
        color: #374151;
        margin: 1.2em 0;
        font-weight: 600;
    }
    
    /* 記事内の画像のスタイル - admin設定の幅・高さを尊重 */
    .article-content img {
        cursor: default !important;
        pointer-events: none !important;
        border: none !important;
        box-shadow: none !important;
        transition: none !important;
        display: block;
        margin: 1rem auto;
        object-fit: contain;
    }
    
    /* admin で幅・高さが指定されていない画像にのみデフォルト制約を適用 */
    .article-content img:not([width]):not([height]):not([style*="width"]):not([style*="height"]) {
        max-width: 100%;
        height: auto;
        width: auto;
    }
    
    /* スマホでの記事コンテナとパディング調整 */
    @media (max-width: 767px) {
        /* HTML全体の制御 */
        html, body {
            overflow-x: hidden !important;
            max-width: 100vw !important;
        }
        
        /* 記事コンテンツ内の全要素（ニュース記事と同じスタイル適用） */
        .article-content,
        .article-content *,
        .article-content img,
        .article-content div,
        .article-content p,
        .article-content figure,
        .article-content span,
        .article-content strong,
        .article-content em,
        .article-content li,
        .article-content ul,
        .article-content ol,
        .article-content br,
        .page-content,
        .page-content *,
        .page-content img,
        .page-content div,
        .page-content p,
        .page-content figure,
        .page-content span,
        .page-content strong,
        .page-content em,
        .page-content li,
        .page-content ul,
        .page-content ol,
        .page-content br {
            max-width: 85vw !important;
            overflow-x: hidden !important;
            word-wrap: break-word !important;
            word-break: break-word !important;
            overflow-wrap: break-word !important;
            white-space: normal !important;
            box-sizing: border-box !important;
        }
        
        /* h2スタイルボックスの調整 */
        .article-content h2,
        .page-content h2 {
            max-width: 85vw !important;
            width: 85vw !important;
            margin: 1rem auto !important;
            box-sizing: border-box !important;
            padding: 1.2em 0.5em 1em !important;
            min-height: 3em !important;
            line-height: 1.6 !important;
            overflow: visible !important;
            font-size: 1rem !important;
        }
        
        /* カスタムHTMLブロック・TinyMCE生成コンテンツの制御 */
        .article-content .mce-content-body,
        .article-content [data-mce-*],
        .article-content .html-block,
        .article-content .custom-html,
        .page-content .mce-content-body,
        .page-content [data-mce-*],
        .page-content .html-block,
        .page-content .custom-html {
            max-width: 85vw !important;
            width: 85vw !important;
            margin: 0 auto !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
        }
        
        /* インラインスタイルで幅が指定された要素の強制調整 */
        .article-content *[style*="width"]:not(img),
        .page-content *[style*="width"]:not(img) {
            max-width: 85vw !important;
            width: 85vw !important;
            margin: 0 auto !important;
        }
        
        /* divやセクション要素の調整 */
        .article-content div,
        .article-content section,
        .article-content article,
        .page-content div,
        .page-content section,
        .page-content article {
            max-width: 85vw !important;
            width: auto !important;
            margin-left: auto !important;
            margin-right: auto !important;
            box-sizing: border-box !important;
        }
        
        /* カスタムHTMLブロック（targetlist_20など）の制御 */
        .article-content .targetlist_20,
        .page-content .targetlist_20,
        .article-content .targetlist_21,
        .page-content .targetlist_21,
        .article-content .custom-html-block,
        .page-content .custom-html-block {
            max-width: 85vw !important;
            width: 85vw !important;
            margin: 1.5rem auto 1rem auto !important;
            box-sizing: border-box !important;
            padding: 35px 15px 15px 15px !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            position: relative !important;
            overflow: visible !important;
        }
        
        /* targetlistのspan要素（タイトル部分）の調整 */
        .article-content .targetlist_20 span,
        .page-content .targetlist_20 span,
        .article-content .targetlist_21 span,
        .page-content .targetlist_21 span {
            position: absolute !important;
            top: -20px !important;
            left: 15px !important;
            max-width: calc(85vw - 60px) !important;
            white-space: normal !important;
            word-wrap: break-word !important;
            padding: 10px 20px !important;
            font-size: 0.9rem !important;
            line-height: 1.4 !important;
            background: #5b8bd0 !important;
            color: white !important;
            border-radius: 15px !important;
            box-shadow: 1px 1px 2px rgba(0,0,0,.3) !important;
            z-index: 10 !important;
            min-height: 30px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            overflow: visible !important;
        }
        
        /* targetlist内のdiv要素の調整 */
        .article-content .targetlist_20 div,
        .page-content .targetlist_20 div,
        .article-content .targetlist_21 div,
        .page-content .targetlist_21 div {
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        /* 画像の特別制御 - admin設定の幅を尊重しつつモバイル対応 */
        .article-content img,
        .page-content img {
            margin: 1rem auto !important;
            display: block !important;
            object-fit: contain !important;
            border: none !important;
        }
        
        /* admin で幅が設定されていない画像にのみモバイル制約を適用 */
        .article-content img:not([width]):not([style*="width"]),
        .page-content img:not([width]):not([style*="width"]) {
            width: 85vw !important;
            height: auto !important;
        }
        
        /* admin で幅が設定された画像はその幅を尊重（ただしモバイルでは最大幅制限） */
        .article-content img[width],
        .article-content img[style*="width"],
        .page-content img[width],
        .page-content img[style*="width"] {
            max-width: 85vw !important;
        }
        
        /* 記事全体のコンテナ調整 */
        .max-w-7xl {
            max-width: 100vw !important;
            padding: 0 0.5rem !important;
            overflow-x: hidden !important;
        }
        
        .max-w-4xl {
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
        
        /* 長いテキストや計算式の改行制御のみ */
        .article-content p,
        .page-content p,
        .article-content div,
        .page-content div,
        .article-content span,
        .page-content span {
            word-break: break-word !important;
            overflow-wrap: break-word !important;
            hyphens: auto !important;
        }
        
        /* よく使われるカスタムHTMLパターンの対応 */
        .article-content .targetlist_1,
        .article-content .targetlist_2,
        .article-content .targetlist_3,
        .article-content .targetlist_4,
        .article-content .targetlist_5,
        .article-content .targetlist_10,
        .article-content .targetlist_11,
        .article-content .targetlist_12,
        .article-content .targetlist_13,
        .article-content .targetlist_14,
        .article-content .targetlist_15,
        .article-content .targetlist_16,
        .article-content .targetlist_17,
        .article-content .targetlist_18,
        .article-content .targetlist_19,
        .article-content .targetlist_22,
        .article-content .targetlist_23,
        .article-content .targetlist_24,
        .article-content .targetlist_25,
        .page-content .targetlist_1,
        .page-content .targetlist_2,
        .page-content .targetlist_3,
        .page-content .targetlist_4,
        .page-content .targetlist_5,
        .page-content .targetlist_10,
        .page-content .targetlist_11,
        .page-content .targetlist_12,
        .page-content .targetlist_13,
        .page-content .targetlist_14,
        .page-content .targetlist_15,
        .page-content .targetlist_16,
        .page-content .targetlist_17,
        .page-content .targetlist_18,
        .page-content .targetlist_19,
        .page-content .targetlist_22,
        .page-content .targetlist_23,
        .page-content .targetlist_24,
        .page-content .targetlist_25 {
            max-width: 85vw !important;
            width: 85vw !important;
            margin: 1.5rem auto 1rem auto !important;
            box-sizing: border-box !important;
            padding: 35px 15px 15px 15px !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            position: relative !important;
            overflow: visible !important;
        }
        
        /* 対応するspan要素（タイトル部分） */
        .article-content .targetlist_1 span,
        .article-content .targetlist_2 span,
        .article-content .targetlist_3 span,
        .article-content .targetlist_4 span,
        .article-content .targetlist_5 span,
        .article-content .targetlist_10 span,
        .article-content .targetlist_11 span,
        .article-content .targetlist_12 span,
        .article-content .targetlist_13 span,
        .article-content .targetlist_14 span,
        .article-content .targetlist_15 span,
        .article-content .targetlist_16 span,
        .article-content .targetlist_17 span,
        .article-content .targetlist_18 span,
        .article-content .targetlist_19 span,
        .article-content .targetlist_22 span,
        .article-content .targetlist_23 span,
        .article-content .targetlist_24 span,
        .article-content .targetlist_25 span,
        .page-content .targetlist_1 span,
        .page-content .targetlist_2 span,
        .page-content .targetlist_3 span,
        .page-content .targetlist_4 span,
        .page-content .targetlist_5 span,
        .page-content .targetlist_10 span,
        .page-content .targetlist_11 span,
        .page-content .targetlist_12 span,
        .page-content .targetlist_13 span,
        .page-content .targetlist_14 span,
        .page-content .targetlist_15 span,
        .page-content .targetlist_16 span,
        .page-content .targetlist_17 span,
        .page-content .targetlist_18 span,
        .page-content .targetlist_19 span,
        .page-content .targetlist_22 span,
        .page-content .targetlist_23 span,
        .page-content .targetlist_24 span,
        .page-content .targetlist_25 span {
            position: absolute !important;
            top: -20px !important;
            left: 15px !important;
            max-width: calc(85vw - 60px) !important;
            white-space: normal !important;
            word-wrap: break-word !important;
            padding: 10px 20px !important;
            font-size: 0.9rem !important;
            line-height: 1.4 !important;
            background: #5b8bd0 !important;
            color: white !important;
            border-radius: 15px !important;
            box-shadow: 1px 1px 2px rgba(0,0,0,.3) !important;
            z-index: 10 !important;
            min-height: 30px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            overflow: visible !important;
        }
        
        /* 汎用的なボックススタイル対応 */
        .article-content [class*="box-"],
        .article-content [class*="card-"],
        .article-content [class*="alert-"],
        .article-content [class*="notice-"],
        .page-content [class*="box-"],
        .page-content [class*="card-"],
        .page-content [class*="alert-"],
        .page-content [class*="notice-"] {
            max-width: 85vw !important;
            width: 85vw !important;
            margin: 1rem auto !important;
            box-sizing: border-box !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
        }
        
        /* インラインでwidth指定された要素の強制調整 */
        .article-content [style*="width"]:not(img):not(table),
        .page-content [style*="width"]:not(img):not(table) {
            max-width: 85vw !important;
            width: auto !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }
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

    /* 監修者説明文の切り替えアイコン */
    .supervisor-toggle .toggle-icon {
        transition: transform 0.3s ease;
    }
</style>

<script>
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
    
    // h2, h3, h4要素を取得
    const headings = articleContent.querySelectorAll('h2, h3, h4');
    
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
        } else if (level === 'h4') {
            tocHTML += `
                <li class="ml-8">
                    <a href="#${heading.id}" class="block text-gray-500 hover:text-gray-700 py-1 transition-colors text-sm">
                        - ${text}
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