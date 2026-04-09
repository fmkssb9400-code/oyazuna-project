<article class="w-full rounded-2xl border border-gray-200 bg-white shadow-sm hover:shadow-md transition-shadow duration-200 p-6">
    <div class="grid gap-8 lg:grid-cols-2">
        <!-- Left Column -->
        <div class="space-y-4">
            <!-- Company Name -->
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/building_kaisya_small.png') }}" alt="会社アイコン" class="w-8 h-8 object-contain flex-shrink-0">
                <h3 class="text-xl font-bold text-gray-900 break-words leading-tight">{{ $company['name'] }}</h3>
            </div>
            
            <!-- Rating -->
            <div class="flex items-center gap-2">
                <div class="flex items-center">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($company['review_score'] / 20))
                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @else
                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endif
                    @endfor
                </div>
                <span class="text-lg font-bold text-gray-900">{{ number_format($company['review_score'] / 20, 1) }}</span>
                <span class="text-sm text-blue-600 hover:underline cursor-pointer">({{ $company['review_count'] }}件)口コミを見る</span>
            </div>
            
            <!-- Badges -->
            <div class="flex flex-wrap gap-2">
                @if($company['rope_support'])
                    <span class="inline-block px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded-full">ロープアクセス対応</span>
                @endif
                @if($company['branco_supported'])
                    <span class="inline-block px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">ブランコ対応</span>
                @endif
                @if($company['aerial_platform_supported'])
                    <span class="inline-block px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">高所作業車</span>
                @endif
                @if($company['gondola_supported'])
                    <span class="inline-block px-2 py-1 text-xs bg-purple-100 text-purple-700 rounded-full">ゴンドラ対応</span>
                @endif
                @if($company['emergency_support'] !== '不可')
                    <span class="inline-block px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded-full">緊急：{{ $company['emergency_support'] }}</span>
                @endif
            </div>
            
            <!-- Service Area -->
            <div>
                <div class="font-medium text-gray-700 mb-1">対応エリア</div>
                <p class="text-sm text-gray-600 whitespace-normal break-words">{{ $company['service_areas'] }}</p>
            </div>
            
            <!-- Performance -->
            <div>
                <div class="font-medium text-gray-700 mb-1">実績</div>
                <p class="text-sm text-gray-600 whitespace-normal break-words">{{ $company['performance_summary'] }}</p>
            </div>
            
            <!-- Description -->
            <div>
                <div class="font-medium text-gray-700 mb-1">会社紹介</div>
                <p class="text-sm text-gray-600 whitespace-normal break-words">{{ $company['description'] }}</p>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-4">
            <!-- Safety Information -->
            <div>
                <div class="font-medium text-gray-700 mb-2">安全情報</div>
                <div class="space-y-1">
                    @foreach($company['security_points'] as $point)
                        <div class="flex items-start text-xs text-gray-600">
                            <svg class="w-3 h-3 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="whitespace-normal break-words">{{ $point }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Strength Tags -->
            <div>
                <div class="font-medium text-gray-700 mb-2">強み・特徴</div>
                <div class="flex flex-wrap gap-1">
                    @foreach($company['strength_tags'] as $tag)
                        <span class="inline-block px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded-full">{{ $tag }}</span>
                    @endforeach
                </div>
            </div>
            
            <!-- Buttons -->
            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                <a href="{{ route('companies.show', $company['slug']) }}" 
                   class="bg-blue-600 text-white text-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                    詳細を見る
                </a>
                <a href="{{ $company['website_url'] }}" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   class="bg-gray-600 text-white text-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors">
                    公式サイト
                </a>
            </div>
        </div>
    </div>
</article>