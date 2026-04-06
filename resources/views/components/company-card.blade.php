@props(['company', 'showDetailButton' => true])

<div class="bg-white border border-gray-300 rounded-lg shadow-sm hover:shadow-md transition-all duration-300">
    <!-- Header Section -->
    <div class="px-6 py-5 border-b border-gray-200">
        <div class="flex justify-between items-start">
            <div class="flex-1">
                <h3 class="text-xl font-bold text-blue-600 leading-tight border-b-2 border-blue-600 pb-1 mb-3 inline-block">
                    {{ $company->name }}
                </h3>
                <div class="text-sm text-gray-600 space-y-1">
                    @if($company->address)
                    <div>所在地：{{ $company->address }}</div>
                    @endif
                    @if($company->established_date)
                    <div>創業：{{ $company->established_date }}</div>
                    @endif
                </div>
            </div>
            <!-- Wishlist Toggle -->
            <button class="wishlist-toggle p-2 text-gray-400 hover:text-blue-600 transition-colors duration-200" 
                    data-company-id="{{ $company->id }}" 
                    data-company-name="{{ $company->name }}"
                    title="気になるリストに追加">
                <div class="relative">
                    <input type="checkbox" class="wishlist-checkbox sr-only">
                    <svg class="w-5 h-5 wishlist-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                    </svg>
                    <svg class="w-5 h-5 wishlist-icon-filled text-blue-600 hidden" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                    </svg>
                </div>
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="px-6 py-4">
        <!-- Company Info Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Left Column -->
            <div class="space-y-4">
                <!-- Rating Section -->
                <div class="flex items-center gap-3">
                    <div class="flex items-center">
                        @if($company->reviews_count > 0)
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= round($company->average_rating) ? 'text-yellow-400' : 'text-gray-300' }}" 
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                            <span class="text-2xl font-bold text-gray-900 ml-2">{{ number_format($company->average_rating, 1) }}</span>
                            <a href="{{ route('companies.reviews', $company) }}" class="text-sm text-blue-600 ml-1 hover:text-blue-800 hover:underline">({{ $company->reviews_count }}件)</a>
                        @else
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                            <span class="text-lg font-medium text-gray-500 ml-2">評価待ち</span>
                        @endif
                    </div>
                </div>

                <!-- Basic Info -->
                <div class="space-y-2 text-sm">
                    <div class="flex">
                        <span class="font-medium text-gray-700 w-20">対応エリア</span>
                        <span class="text-gray-900">{{ $company->areas_display }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium text-gray-700 w-20">提供サービス</span>
                        <span class="text-gray-900">
                            {{ $company->service_categories_display ?: 'お問い合わせください' }}
                        </span>
                    </div>
                    <div class="flex">
                        <span class="font-medium text-gray-700 w-20">評価・スコア</span>
                        <span class="text-gray-900 flex flex-wrap gap-2">
                            @if($company->recommend_score > 0)
                                <span class="flex items-center">
                                    おすすめ:
                                    @for($i = 1; $i <= $company->recommend_score; $i++)
                                        <svg class="w-3 h-3 text-yellow-400 ml-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </span>
                            @endif
                            @if($company->safety_score > 0)
                                <span class="flex items-center">
                                    安全:
                                    @for($i = 1; $i <= $company->safety_score; $i++)
                                        <svg class="w-3 h-3 text-yellow-400 ml-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </span>
                            @endif
                            @if($company->performance_score > 0)
                                <span class="flex items-center">
                                    実績:
                                    @for($i = 1; $i <= $company->performance_score; $i++)
                                        <svg class="w-3 h-3 text-yellow-400 ml-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </span>
                            @endif
                            @if($company->recommend_score == 0 && $company->safety_score == 0 && $company->performance_score == 0)
                                未設定
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Right Column - Company Image -->
            <div class="space-y-4">
                <div class="bg-gray-100 rounded-lg h-40 flex items-center justify-center">
                    @if($company->logo_url)
                        <img src="{{ $company->logo_url }}" alt="{{ $company->name }}のロゴ" class="max-h-32 max-w-full object-contain">
                    @else
                        <div class="text-center">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span class="text-sm text-gray-500">{{ $company->name }}</span>
                        </div>
                    @endif
                </div>
                
                <!-- Project Gallery -->
                @if($company->project_images && count($company->project_images) > 0)
                <div class="grid grid-cols-3 gap-2">
                    @foreach(array_slice($company->project_images, 0, 3) as $image)
                    <div class="bg-gray-200 rounded aspect-square">
                        <img src="{{ $image }}" alt="施工事例" class="w-full h-full object-cover rounded">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- Company Description and Features -->
        @if($company->achievements_summary || $company->description)
        <div class="mb-6">
            @if($company->achievements_summary)
            <p class="text-sm text-gray-700 mb-4">{{ $company->achievements_summary }}</p>
            @elseif($company->description)
            <p class="text-sm text-gray-700 mb-4">{{ Str::limit($company->description, 150) }}</p>
            @endif
            
            <!-- Fixed Company Tags List -->
            <div class="flex flex-wrap gap-2">
                @php
                    $fixedTags = [
                        '見積もり無料',
                        'ロープアクセス対応', 
                        'ブランコ対応',
                        '高所作業車',
                        'ゴンドラ対応',
                        '定期作業',
                        '口コミ評判',
                        '事例',
                        '損害賠償保険加入',
                        '労災保険加入',
                        '有資格者在籍',
                        '法人対応',
                        '土日対応',
                        '夜間対応',
                        '即日対応',
                        'オンライン相談',
                        'メール対応',
                        'LINE対応'
                    ];
                    $companyTags = $company->tags;
                    if (is_string($companyTags)) {
                        $companyTags = json_decode($companyTags, true) ?: explode(',', $companyTags);
                    }
                    $companyTags = is_array($companyTags) ? $companyTags : [];
                @endphp
                @foreach($fixedTags as $tag)
                    @php
                        $isActive = in_array($tag, $companyTags);
                        $tagClass = $isActive 
                            ? 'px-2 py-1 text-xs border border-blue-200 text-blue-700 bg-blue-50 rounded'
                            : 'px-2 py-1 text-xs border border-gray-300 text-gray-600 bg-gray-100 rounded';
                    @endphp
                    <span class="{{ $tagClass }}">{{ $tag }}</span>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('quote.create') }}" class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-orange-500 text-white text-sm font-bold rounded hover:bg-orange-600 transition-all duration-200">
                専門業者に相談する
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            
            @if($showDetailButton)
            <a href="{{ route('companies.show', $company->slug) }}" 
               class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-white text-orange-600 text-sm font-bold rounded border-2 border-orange-500 hover:bg-orange-50 transition-all duration-200">
                詳細を見る
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            @endif
        </div>
    </div>
</div>


<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

