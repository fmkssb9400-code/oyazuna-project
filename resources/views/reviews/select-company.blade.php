@extends('layouts.app')

@section('content')
<div class="bg-blue-50 min-h-screen">
    <div class="container mx-auto py-8 px-4">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">口コミを書く会社を選択</h1>
                <p class="text-gray-600">実際にご利用いただいた高所ロープ業者の口コミを投稿してください</p>
            </div>

            <!-- Search Form -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                <form action="{{ route('reviews.select-company') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" 
                               name="search" 
                               value="{{ $search }}"
                               placeholder="会社名で検索..." 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        検索
                    </button>
                </form>
            </div>

            <!-- Companies 3-Column Grid -->
            @if($companies->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6 mb-8">
                @foreach($companies as $company)
                <a href="{{ route('reviews.create', $company->slug) }}" 
                   class="bg-white rounded-lg border-2 border-blue-300 hover:border-blue-400 hover:shadow-md transition-all duration-200 block">
                    <div class="p-4 md:p-6">
                        <div class="flex items-start gap-4">
                            <!-- Company Logo/Initial Badge -->
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                @if($company->logo_path)
                                    <img src="{{ $company->logo_url }}" 
                                         alt="{{ $company->name }}" 
                                         class="w-full h-full object-cover rounded-lg">
                                @else
                                    <span class="text-lg font-bold text-gray-700">
                                        {{ mb_substr($company->name, 0, 1) }}{{ mb_substr($company->name, 1, 1) }}
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <!-- Company Name -->
                                <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2" title="{{ $company->name }}">
                                    {{ $company->name }}
                                </h3>
                                
                                <!-- Service Type -->
                                <p class="text-sm text-gray-600 mb-4">
                                    高所ロープ作業
                                </p>
                            </div>
                        </div>
                        
                        <!-- Review Count -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <span class="text-sm text-gray-600">口コミ件数</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $company->reviews_count }}件</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $companies->withQueryString()->links() }}
            </div>
            @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 mb-4 text-gray-400">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-medium text-gray-900 mb-2">検索結果が見つかりません</h3>
                <p class="text-gray-600 mb-6">別のキーワードで検索してみてください</p>
                <a href="{{ route('reviews.select-company') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    すべての会社を表示
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection