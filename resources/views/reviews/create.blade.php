@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto py-8 px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Breadcrumb -->
            <nav class="flex mb-6 text-sm">
                <a href="{{ route('reviews.select-company') }}" class="text-blue-600 hover:text-blue-800">会社選択</a>
                <span class="mx-2 text-gray-500">></span>
                <span class="text-gray-700">口コミ投稿</span>
            </nav>

            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $company->name }}の口コミを投稿</h1>
                <p class="text-gray-600">実際にご利用いただいたサービスの口コミをお聞かせください</p>
            </div>

            <!-- Company Info Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">{{ $company->name }}</h3>
                        <p class="text-gray-600">{{ $company->areas_display }}</p>
                        @if($company->average_rating > 0)
                        <div class="flex items-center mt-1">
                            <div class="flex items-center mr-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= round($company->average_rating) ? 'text-yellow-400' : 'text-gray-300' }}" 
                                         fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-sm text-gray-600">{{ number_format($company->average_rating, 1) }} ({{ $company->reviews_count }}件)</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Review Form -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <form action="{{ route('reviews.store', $company->slug) }}" method="POST">
                    @csrf
                    
                    <!-- Hidden Rating Inputs -->
                    <input type="hidden" name="service_quality" id="service_quality_input" value="">
                    <input type="hidden" name="staff_response" id="staff_response_input" value="">
                    <input type="hidden" name="value_for_money" id="value_for_money_input" value="">
                    <input type="hidden" name="would_use_again" id="would_use_again_input" value="">

                    <!-- Detailed Ratings (Required) -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">詳細評価 <span class="text-red-500">*</span></h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">サービス品質 <span class="text-red-500">*</span></label>
                                <div class="flex items-center space-x-1" data-rating-field="service_quality">
                                    @for($i = 1; $i <= 5; $i++)
                                    <button type="button" 
                                            class="rating-star w-6 h-6 text-gray-300 hover:text-yellow-400"
                                            data-rating="{{ $i }}">
                                        <svg fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </button>
                                    @endfor
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">スタッフ対応 <span class="text-red-500">*</span></label>
                                <div class="flex items-center space-x-1" data-rating-field="staff_response">
                                    @for($i = 1; $i <= 5; $i++)
                                    <button type="button" 
                                            class="rating-star w-6 h-6 text-gray-300 hover:text-yellow-400"
                                            data-rating="{{ $i }}">
                                        <svg fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </button>
                                    @endfor
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">料金・コスパ <span class="text-red-500">*</span></label>
                                <div class="flex items-center space-x-1" data-rating-field="value_for_money">
                                    @for($i = 1; $i <= 5; $i++)
                                    <button type="button" 
                                            class="rating-star w-6 h-6 text-gray-300 hover:text-yellow-400"
                                            data-rating="{{ $i }}">
                                        <svg fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </button>
                                    @endfor
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">また利用したいか <span class="text-red-500">*</span></label>
                                <div class="flex items-center space-x-1" data-rating-field="would_use_again">
                                    @for($i = 1; $i <= 5; $i++)
                                    <button type="button" 
                                            class="rating-star w-6 h-6 text-gray-300 hover:text-yellow-400"
                                            data-rating="{{ $i }}">
                                        <svg fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </button>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Reviewer Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Company Name -->
                        <div>
                            <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">
                                会社名 <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="company_name" 
                                   name="company_name" 
                                   value="{{ old('company_name') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('company_name') border-red-300 @enderror"
                                   placeholder="会社名を入力してください">
                            @error('company_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Reviewer Name -->
                        <div>
                            <label for="reviewer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                担当者名 <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="reviewer_name" 
                                   name="reviewer_name" 
                                   value="{{ old('reviewer_name') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('reviewer_name') border-red-300 @enderror"
                                   placeholder="担当者名を入力してください">
                            @error('reviewer_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Service Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Service Category -->
                        <div>
                            <label for="service_category" class="block text-sm font-medium text-gray-700 mb-2">
                                利用したサービス <span class="text-red-500">*</span>
                            </label>
                            <select id="service_category" 
                                    name="service_category"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('service_category') border-red-300 @enderror">
                                <option value="">選択してください</option>
                                <option value="window_cleaning" {{ old('service_category') === 'window_cleaning' ? 'selected' : '' }}>窓ガラス清掃</option>
                                <option value="exterior_cleaning" {{ old('service_category') === 'exterior_cleaning' ? 'selected' : '' }}>外壁清掃</option>
                                <option value="high_rise_cleaning" {{ old('service_category') === 'high_rise_cleaning' ? 'selected' : '' }}>高所清掃</option>
                                <option value="maintenance" {{ old('service_category') === 'maintenance' ? 'selected' : '' }}>設備メンテナンス</option>
                                <option value="other" {{ old('service_category') === 'other' ? 'selected' : '' }}>その他</option>
                            </select>
                            @error('service_category')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Building Type -->
                        <div>
                            <label for="building_type" class="block text-sm font-medium text-gray-700 mb-2">
                                建物種別
                            </label>
                            <select id="building_type" 
                                    name="building_type"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">選択してください</option>
                                <option value="office" {{ old('building_type') === 'office' ? 'selected' : '' }}>オフィスビル</option>
                                <option value="apartment" {{ old('building_type') === 'apartment' ? 'selected' : '' }}>マンション</option>
                                <option value="factory" {{ old('building_type') === 'factory' ? 'selected' : '' }}>工場・倉庫</option>
                                <option value="commercial" {{ old('building_type') === 'commercial' ? 'selected' : '' }}>商業施設</option>
                                <option value="hospital" {{ old('building_type') === 'hospital' ? 'selected' : '' }}>病院・施設</option>
                                <option value="school" {{ old('building_type') === 'school' ? 'selected' : '' }}>学校</option>
                                <option value="house" {{ old('building_type') === 'house' ? 'selected' : '' }}>一般住宅</option>
                                <option value="other" {{ old('building_type') === 'other' ? 'selected' : '' }}>その他</option>
                            </select>
                        </div>
                    </div>

                    <!-- Project Scale -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="project_scale" class="block text-sm font-medium text-gray-700 mb-2">
                                作業規模
                            </label>
                            <select id="project_scale" 
                                    name="project_scale"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">選択してください</option>
                                <option value="small" {{ old('project_scale') === 'small' ? 'selected' : '' }}>小規模（〜5階建て）</option>
                                <option value="medium" {{ old('project_scale') === 'medium' ? 'selected' : '' }}>中規模（6〜15階建て）</option>
                                <option value="large" {{ old('project_scale') === 'large' ? 'selected' : '' }}>大規模（16階建て以上）</option>
                            </select>
                        </div>

                        <!-- Continue Request -->
                        <div>
                            <label for="continue_request" class="block text-sm font-medium text-gray-700 mb-2">
                                継続してサービスを依頼したいか
                            </label>
                            <select id="continue_request" 
                                    name="continue_request"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">選択してください</option>
                                <option value="definitely_yes" {{ old('continue_request') === 'definitely_yes' ? 'selected' : '' }}>ぜひ依頼したい</option>
                                <option value="probably_yes" {{ old('continue_request') === 'probably_yes' ? 'selected' : '' }}>おそらく依頼すると思う</option>
                                <option value="maybe" {{ old('continue_request') === 'maybe' ? 'selected' : '' }}>どちらともいえない</option>
                                <option value="probably_no" {{ old('continue_request') === 'probably_no' ? 'selected' : '' }}>おそらく依頼しないと思う</option>
                                <option value="definitely_no" {{ old('continue_request') === 'definitely_no' ? 'selected' : '' }}>依頼しない</option>
                            </select>
                        </div>
                    </div>

                    <!-- Usage Period -->
                    <div class="mb-6">
                        <label for="usage_period" class="block text-sm font-medium text-gray-700 mb-2">
                            利用時期
                        </label>
                        <select id="usage_period" 
                                name="usage_period"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent md:w-1/2">
                            <option value="">選択してください</option>
                            <option value="within_1month" {{ old('usage_period') === 'within_1month' ? 'selected' : '' }}>1ヶ月以内</option>
                            <option value="within_3months" {{ old('usage_period') === 'within_3months' ? 'selected' : '' }}>3ヶ月以内</option>
                            <option value="within_6months" {{ old('usage_period') === 'within_6months' ? 'selected' : '' }}>6ヶ月以内</option>
                            <option value="within_1year" {{ old('usage_period') === 'within_1year' ? 'selected' : '' }}>1年以内</option>
                            <option value="over_1year" {{ old('usage_period') === 'over_1year' ? 'selected' : '' }}>1年以上前</option>
                        </select>
                    </div>

                    <!-- Review Content - Good Points -->
                    <div class="mb-6">
                        <label for="good_points" class="block text-sm font-medium text-gray-700 mb-2">
                            良かった点 <span class="text-red-500">*</span>
                        </label>
                        <textarea id="good_points" 
                                  name="good_points" 
                                  rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('good_points') border-red-300 @enderror"
                                  placeholder="サービスの良かった点や評価できる点をお書きください。スタッフの対応、技術力、仕上がり、料金など具体的にお聞かせください。">{{ old('good_points') }}</textarea>
                        <div class="flex justify-between items-center mt-2">
                            @error('good_points')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @else
                                <p class="text-sm text-gray-500">最低10文字以上でお書きください</p>
                            @enderror
                            <p class="text-sm text-gray-400" id="good-points-count">0文字</p>
                        </div>
                    </div>

                    <!-- Review Content - Improvement Points -->
                    <div class="mb-6">
                        <label for="improvement_points" class="block text-sm font-medium text-gray-700 mb-2">
                            改善点・気になった点（任意）
                        </label>
                        <textarea id="improvement_points" 
                                  name="improvement_points" 
                                  rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('improvement_points') border-red-300 @enderror"
                                  placeholder="改善してほしい点や気になった点があればお書きください。業者の方への建設的なアドバイスとして活用させていただきます。">{{ old('improvement_points') }}</textarea>
                        <div class="flex justify-between items-center mt-2">
                            @error('improvement_points')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @else
                                <p class="text-sm text-gray-500"></p>
                            @enderror
                            <p class="text-sm text-gray-400" id="improvement-points-count">0文字</p>
                        </div>
                    </div>

                    <!-- Terms and Privacy -->
                    <div class="mb-6">
                        <label class="flex items-start">
                            <input type="checkbox" class="mt-1 mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" required>
                            <span class="text-sm text-gray-600">
                                <a href="#" class="text-blue-600 hover:text-blue-800 underline">利用規約</a>
                                および
                                <a href="#" class="text-blue-600 hover:text-blue-800 underline">プライバシーポリシー</a>
                                に同意します
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-center">
                        <button type="submit" 
                                class="px-8 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                            口コミを投稿する
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Character Counter for Good Points
    const goodPointsTextarea = document.getElementById('good_points');
    const goodPointsCharCount = document.getElementById('good-points-count');
    
    if (goodPointsTextarea && goodPointsCharCount) {
        goodPointsTextarea.addEventListener('input', function() {
            const count = this.value.length;
            goodPointsCharCount.textContent = count + '文字';
        });
    }

    // Character Counter for Improvement Points
    const improvementPointsTextarea = document.getElementById('improvement_points');
    const improvementPointsCharCount = document.getElementById('improvement-points-count');
    
    if (improvementPointsTextarea && improvementPointsCharCount) {
        improvementPointsTextarea.addEventListener('input', function() {
            const count = this.value.length;
            improvementPointsCharCount.textContent = count + '文字';
        });
    }
    
    // Rating stars functionality
    document.querySelectorAll('.rating-star').forEach(star => {
        star.addEventListener('click', function() {
            const ratingContainer = this.closest('[data-rating-field]');
            const ratingField = ratingContainer.dataset.ratingField;
            const rating = parseInt(this.dataset.rating);
            const siblings = ratingContainer.querySelectorAll('.rating-star');
            
            // Update hidden input
            const hiddenInput = document.getElementById(ratingField + '_input');
            if (hiddenInput) {
                hiddenInput.value = rating;
            }
            
            // Update visual stars
            siblings.forEach((s, i) => {
                if (i < rating) {
                    s.classList.remove('text-gray-300');
                    s.classList.add('text-yellow-400');
                } else {
                    s.classList.remove('text-yellow-400');
                    s.classList.add('text-gray-300');
                }
            });
        });
        
        // Hover effects
        star.addEventListener('mouseenter', function() {
            const ratingContainer = this.closest('[data-rating-field]');
            const rating = parseInt(this.dataset.rating);
            const siblings = ratingContainer.querySelectorAll('.rating-star');
            
            siblings.forEach((s, i) => {
                if (i < rating) {
                    s.classList.add('text-yellow-300');
                }
            });
        });
        
        star.addEventListener('mouseleave', function() {
            const ratingContainer = this.closest('[data-rating-field]');
            const siblings = ratingContainer.querySelectorAll('.rating-star');
            
            siblings.forEach(s => {
                s.classList.remove('text-yellow-300');
            });
        });
    });
    
    // Form validation before submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const requiredRatings = ['service_quality', 'staff_response', 'value_for_money', 'would_use_again'];
        let hasError = false;
        
        // Remove existing error messages
        document.querySelectorAll('.rating-error').forEach(el => el.remove());
        
        requiredRatings.forEach(fieldName => {
            const input = document.getElementById(fieldName + '_input');
            const container = document.querySelector(`[data-rating-field="${fieldName}"]`);
            
            if (!input.value || input.value === '') {
                hasError = true;
                
                // Add error message
                const errorDiv = document.createElement('div');
                errorDiv.className = 'rating-error text-red-500 text-xs mt-1';
                errorDiv.textContent = 'この評価は必須です';
                container.parentNode.appendChild(errorDiv);
                
                // Highlight the rating container
                container.classList.add('border', 'border-red-300', 'rounded', 'p-2');
            } else {
                // Remove highlight if rating is selected
                container.classList.remove('border', 'border-red-300', 'rounded', 'p-2');
            }
        });
        
        if (hasError) {
            e.preventDefault();
            // Scroll to first error
            const firstError = document.querySelector('.rating-error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
});
</script>
@endsection