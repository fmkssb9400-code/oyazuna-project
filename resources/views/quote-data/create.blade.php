@extends('layouts.app')

@section('title', '見積もりデータを投稿 - オヤズナ | 高所ロープ作業の見積もり・相場データベース')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6 md:py-8">
    <h1 class="text-2xl md:text-3xl font-bold mb-4">見積もりデータを投稿</h1>
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 md:mb-8">
        <p class="text-gray-700 mb-3">
            高所ロープ作業の見積書をアップロードすると、<br>
            見積もり相場データとして集計・分析されます。
        </p>
        <div class="text-sm text-blue-700">
            <p class="mb-1">※会社名・担当者名などは公開されません。</p>
            <p>※公開時は匿名化したうえで掲載されます。</p>
        </div>
    </div>
    
    <form action="{{ route('quote-data.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 md:space-y-8">
        @csrf
        
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <ul class="list-disc list-inside text-red-600">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <!-- 必須項目 -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-6 border-b pb-2">必須項目</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- 作業内容 -->
                <div>
                    <label for="work_type" class="block text-sm font-medium text-gray-700 mb-3">作業内容 <span class="text-red-500">*</span></label>
                    <select id="work_type" name="work_type" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">選択してください</option>
                        @foreach(\App\Models\QuoteSubmission::WORK_TYPES as $key => $label)
                            <option value="{{ $key }}" {{ old('work_type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- 都道府県 -->
                <div>
                    <label for="prefecture" class="block text-sm font-medium text-gray-700 mb-3">都道府県 <span class="text-red-500">*</span></label>
                    <select id="prefecture" name="prefecture" required class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">選択してください</option>
                        @foreach($prefectures as $prefecture)
                            <option value="{{ $prefecture->name }}" {{ old('prefecture') === $prefecture->name ? 'selected' : '' }}>{{ $prefecture->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- 見積書画像アップロード -->
            <div class="mb-6">
                <label for="quote_images" class="block text-sm font-medium text-gray-700 mb-3">見積書画像アップロード（複数枚対応） <span class="text-red-500">*</span></label>
                
                <!-- カスタムファイル選択ボタン -->
                <div class="relative">
                    <input type="file" id="quote_images" name="quote_images[]" multiple accept="image/*" required
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 hover:bg-blue-50 transition-colors">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-600">
                            <span class="font-semibold text-blue-600">ファイルを選択</span> またはドラッグ&ドロップ
                        </p>
                        <p class="text-xs text-gray-500">JPEG、PNG、GIF（最大10MB、複数選択可）</p>
                    </div>
                </div>
                
                <!-- プレビューエリア -->
                <div id="image-preview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
            </div>
            
            <!-- 一言コメント -->
            <div>
                <label for="comment" class="block text-sm font-medium text-gray-700 mb-3">一言コメント <span class="text-red-500">*</span></label>
                <textarea id="comment" name="comment" rows="4" required maxlength="1000"
                          class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                          placeholder="見積書について、どのような作業内容か、気になる点などを簡潔にお書きください（1000文字以内）">{{ old('comment') }}</textarea>
                <p class="text-sm text-gray-500 mt-1">残り<span id="comment-count">1000</span>文字</p>
            </div>
        </div>
        
        <!-- 任意項目 -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-6 border-b pb-2">任意項目</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- 建物階数 -->
                <div>
                    <label for="building_floors" class="block text-sm font-medium text-gray-700 mb-3">建物階数</label>
                    <input type="number" id="building_floors" name="building_floors" value="{{ old('building_floors') }}" 
                           class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           placeholder="例：5" min="1">
                </div>
                
                <!-- 依頼したか -->
                <div>
                    <label for="order_status" class="block text-sm font-medium text-gray-700 mb-3">依頼したか</label>
                    <select id="order_status" name="order_status" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">選択してください</option>
                        @foreach(\App\Models\QuoteSubmission::ORDER_STATUSES as $key => $label)
                            <option value="{{ $key }}" {{ old('order_status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- 見積もり時期 -->
                <div>
                    <label for="quote_date" class="block text-sm font-medium text-gray-700 mb-3">見積もり時期</label>
                    <input type="date" id="quote_date" name="quote_date" value="{{ old('quote_date') }}" 
                           class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>
        
        <!-- 送信ボタン -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <button type="submit" class="w-full bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                見積もりデータを投稿する
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 画像プレビュー機能
    const imageInput = document.getElementById('quote_images');
    const imagePreview = document.getElementById('image-preview');
    
    imageInput.addEventListener('change', function() {
        imagePreview.innerHTML = '';
        
        if (this.files) {
            Array.from(this.files).forEach((file, index) => {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative';
                    
                    div.innerHTML = `
                        <img src="${e.target.result}" 
                             alt="見積書プレビュー ${index + 1}" 
                             class="w-full h-24 object-cover rounded border border-gray-300">
                        <div class="text-xs text-center mt-1 text-gray-600">
                            ${file.name}
                        </div>
                    `;
                    
                    imagePreview.appendChild(div);
                };
                
                reader.readAsDataURL(file);
            });
        }
    });
    
    // コメント文字数カウント
    const commentTextarea = document.getElementById('comment');
    const commentCount = document.getElementById('comment-count');
    
    commentTextarea.addEventListener('input', function() {
        const remaining = 1000 - this.value.length;
        commentCount.textContent = remaining;
        
        if (remaining < 100) {
            commentCount.className = 'text-red-500 font-semibold';
        } else {
            commentCount.className = '';
        }
    });
});
</script>
@endsection