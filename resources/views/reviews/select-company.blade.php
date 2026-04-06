@extends('layouts.app')

@section('content')
<div class="bg-blue-50 min-h-screen">
    <div class="container mx-auto py-8 px-4">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">口コミを書く会社を選択</h1>
                <p class="text-gray-600">実際にご利用いただいた清掃業者の口コミを投稿してください</p>
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

            <!-- Companies 5-Column Grid -->
            @if($companies->count() > 0)
            <div class="grid grid-cols-3 gap-4 mb-8">
                @foreach($companies as $company)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                    <div class="p-4">
                        <!-- Company Name -->
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 line-clamp-2" title="{{ $company->name }}">
                            {{ $company->name }}
                        </h3>
                        
                        <!-- Rating Display -->
                        <div class="flex items-center mb-3">
                            <div class="flex items-center mr-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-3 h-3 {{ $i <= round($company->average_rating) ? 'text-yellow-400' : 'text-gray-300' }}" 
                                         fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-xs text-gray-600">
                                ({{ $company->reviews_count }})
                            </span>
                        </div>

                        <!-- Service Area -->
                        <div class="mb-4">
                            <p class="text-xs text-gray-600 line-clamp-2" title="{{ $company->areas_display }}">
                                {{ $company->areas_display }}
                            </p>
                        </div>

                        <!-- Action Button -->
                        <a href="{{ route('reviews.create', $company->slug) }}" 
                           class="w-full inline-flex justify-center items-center px-3 py-2 bg-blue-600 text-white text-xs font-medium rounded-md hover:bg-blue-700 transition-colors duration-200">
                            口コミを書く
                        </a>
                    </div>
                </div>
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