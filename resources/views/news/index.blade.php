@extends('layouts.app')

@section('title', 'ニュース・記事一覧 - オヤズナ')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">ニュース・記事</h1>
        <p class="text-gray-600">高所ロープに関する最新のニュースや記事をお届けします。</p>
    </div>

    @if($paginatedItems->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($paginatedItems as $item)
                <article class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                    @if($item->featured_image_url)
                        <div class="aspect-video overflow-hidden rounded-t-lg">
                            <img src="{{ $item->featured_image_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        </div>
                    @endif
                    
                    <div class="p-6">
                        <div class="mb-2 flex items-center gap-2">
                            <time class="text-sm text-gray-500">{{ $item->published_at->format('Y年n月j日') }}</time>
                            @if($item->type === 'static_page')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    ガイド記事
                                </span>
                            @endif
                        </div>
                        
                        <h2 class="text-xl font-bold text-gray-900 mb-3 hover:text-blue-600 transition-colors">
                            @if($item->slug)
                                @if($item->type === 'static_page')
                                    @php
                                        $guideRoute = match($item->page_type) {
                                            'window-cleaning-price-guide' => 'guide.window-cleaning-price',
                                            'window-cleaning-contractor-guide' => 'guide.window-cleaning-contractor-selection',
                                            'exterior-wall-painting-price-guide' => 'guide.exterior-wall-painting-pricing',
                                            'exterior-wall-painting-contractor-guide' => 'guide.exterior-wall-painting-contractor-selection',
                                            default => null
                                        };
                                    @endphp
                                    @if($guideRoute)
                                        <a href="{{ route($guideRoute) }}" class="block">
                                            {{ $item->title }}
                                        </a>
                                    @else
                                        <span class="block">{{ $item->title }}</span>
                                    @endif
                                @else
                                    <a href="{{ route('news.show', $item->slug) }}" class="block">
                                        {{ $item->title }}
                                    </a>
                                @endif
                            @else
                                <span class="block">{{ $item->title }}</span>
                            @endif
                        </h2>
                        
                        
                        @if($item->slug)
                            @if($item->type === 'static_page')
                                @php
                                    $guideRoute = match($item->page_type) {
                                        'window-cleaning-price-guide' => 'guide.window-cleaning-price',
                                        'window-cleaning-contractor-guide' => 'guide.window-cleaning-contractor-selection',
                                        'exterior-wall-painting-price-guide' => 'guide.exterior-wall-painting-pricing',
                                        'exterior-wall-painting-contractor-guide' => 'guide.exterior-wall-painting-contractor-selection',
                                        default => null
                                    };
                                @endphp
                                @if($guideRoute)
                                    <a href="{{ route($guideRoute) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium text-sm">
                                        続きを読む
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                @else
                                    <span class="inline-flex items-center text-gray-400 font-medium text-sm">
                                        続きを読む (準備中)
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </span>
                                @endif
                            @else
                                <a href="{{ route('news.show', $item->slug) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium text-sm">
                                    続きを読む
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            @endif
                        @else
                            <span class="inline-flex items-center text-gray-400 font-medium text-sm">
                                続きを読む (準備中)
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </span>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center mt-8">
            <nav class="flex items-center space-x-2">
                {{-- Previous Page Link --}}
                @if($paginatedItems->onFirstPage())
                    <span class="w-12 h-12 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </span>
                @else
                    <a href="{{ $paginatedItems->previousPageUrl() }}" class="w-12 h-12 bg-white hover:bg-blue-50 text-blue-600 rounded-full flex items-center justify-center shadow-sm border">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach($paginatedItems->getUrlRange(1, $paginatedItems->lastPage()) as $page => $url)
                    @if($page == $paginatedItems->currentPage())
                        <span class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-lg">
                            {{ $page }}
                        </span>
                    @elseif($page <= 2 || $page > $paginatedItems->lastPage() - 2 || abs($page - $paginatedItems->currentPage()) <= 1)
                        <a href="{{ $url }}" class="w-12 h-12 bg-white hover:bg-blue-50 text-blue-600 rounded-full flex items-center justify-center font-bold text-lg shadow-sm border">
                            {{ $page }}
                        </a>
                    @elseif($page == 3 && $paginatedItems->currentPage() > 4)
                        <span class="w-12 h-12 bg-white text-blue-600 rounded-full flex items-center justify-center font-bold text-lg">
                            ...
                        </span>
                    @elseif($page == $paginatedItems->lastPage() - 2 && $paginatedItems->currentPage() < $paginatedItems->lastPage() - 3)
                        <span class="w-12 h-12 bg-white text-blue-600 rounded-full flex items-center justify-center font-bold text-lg">
                            ...
                        </span>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if($paginatedItems->hasMorePages())
                    <a href="{{ $paginatedItems->nextPageUrl() }}" class="w-12 h-12 bg-white hover:bg-blue-50 text-blue-600 rounded-full flex items-center justify-center shadow-sm border">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @else
                    <span class="w-12 h-12 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </span>
                @endif
            </nav>
        </div>
    @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">記事がまだありません</h3>
            <p class="text-gray-600">近日中に最新のニュースや記事を公開予定です。</p>
        </div>
    @endif
</div>
@endsection