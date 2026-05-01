<!-- サイドバー -->
<aside class="space-y-6">
    <!-- サイドバー広告1 -->
    @php
        $siteSettings = app(\App\Models\SiteSetting::class)->getSettings();
    @endphp
    
    @if(!empty($siteSettings['sidebar_ad_1']))
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-4">
            <div class="text-xs text-gray-400 mb-2 text-center">広告</div>
            <div class="ad-container">
                {!! $siteSettings['sidebar_ad_1'] !!}
            </div>
        </div>
    </div>
    @endif
    
    <!-- お問い合わせフォーム -->
    <div class="bg-gray-500 rounded-lg shadow-lg text-white">
        <div class="p-6">
            <h4 class="text-xl font-bold mb-6 text-center">お急ぎの方へ</h4>
            
            <form action="{{ route('quote.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 gap-6">
                    <!-- 依頼者区分 -->
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">依頼者区分 <span class="text-red-300">*</span></label>
                        <div class="flex space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="requester_type" value="corp" class="mr-2" required>
                                <span class="text-white">法人</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="requester_type" value="personal" class="mr-2" required>
                                <span class="text-white">個人</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- ご担当者名 -->
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">ご担当者名 <span class="text-red-300">*</span></label>
                        <input type="text" 
                               name="contact_name" 
                               placeholder="例：田中太郎" 
                               required
                               class="w-full px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>
                    
                    <!-- メールアドレス -->
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">メールアドレス <span class="text-red-300">*</span></label>
                        <input type="email" 
                               name="email" 
                               placeholder="example@email.com" 
                               required
                               class="w-full px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>
                    
                    <!-- 電話番号 -->
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">電話番号</label>
                        <input type="tel" 
                               name="phone" 
                               placeholder="03-1234-5678" 
                               class="w-full px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>
                    
                    <!-- 都道府県 -->
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">都道府県 <span class="text-red-300">*</span></label>
                        <select name="prefecture" required class="w-full px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            <option value="">選択してください</option>
                            <option value="東京都">東京都</option>
                            <option value="神奈川県">神奈川県</option>
                            <option value="大阪府">大阪府</option>
                            <option value="愛知県">愛知県</option>
                            <option value="埼玉県">埼玉県</option>
                            <option value="千葉県">千葉県</option>
                            <option value="兵庫県">兵庫県</option>
                            <option value="福岡県">福岡県</option>
                        </select>
                    </div>
                    
                    <!-- サービス内容 -->
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">サービス内容 <span class="text-red-300">*</span></label>
                        <select name="service_type" required class="w-full px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            <option value="">選択してください</option>
                            <option value="window_cleaning">窓ガラス清掃</option>
                            <option value="wall_painting">外壁塗装</option>
                            <option value="wall_inspection">外壁調査・点検</option>
                            <option value="wall_repair">外壁補修</option>
                            <option value="bird_control">鳥害対策</option>
                            <option value="sign_work">看板作業</option>
                            <option value="leak_inspection">雨漏り調査</option>
                            <option value="other">その他</option>
                        </select>
                    </div>
                </div>
                
                <!-- 備考 -->
                <div>
                    <label class="block text-sm font-medium text-white mb-2">ご要望・詳細</label>
                    <textarea name="note" 
                              placeholder="建物の階数、作業内容の詳細、希望時期などお気軽にお書きください" 
                              rows="3"
                              class="w-full px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent resize-none"></textarea>
                </div>
                
                <button type="submit" 
                        class="w-full bg-orange-600 text-white px-6 py-4 rounded-md font-bold text-lg hover:bg-orange-700 transition-colors">
                    無料で見積もり依頼
                </button>
                
                <div class="text-xs text-gray-200 text-center">
                    <p>※送信後、オヤズナにて内容確認後、適切な業者へ共有いたします。</p>
                    <p>※その後、業者より直接ご連絡させていただきます。</p>
                </div>
            </form>
        </div>
    </div>
    
    <!-- サイドバー広告2 -->
    @if(!empty($siteSettings['sidebar_ad_2']))
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-4">
            <div class="text-xs text-gray-400 mb-2 text-center">広告</div>
            <div class="ad-container">
                {!! $siteSettings['sidebar_ad_2'] !!}
            </div>
        </div>
    </div>
    @endif
    
    <!-- おすすめ記事 -->
    @if(isset($featuredArticles) && count($featuredArticles) > 0)
        <x-recommended-articles :articles="$featuredArticles" />
    @endif
    
    <!-- サイドバー広告3 -->
    @if(!empty($siteSettings['sidebar_ad_3']))
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-4">
            <div class="text-xs text-gray-400 mb-2 text-center">広告</div>
            <div class="ad-container">
                {!! $siteSettings['sidebar_ad_3'] !!}
            </div>
        </div>
    </div>
    @endif
</aside>

<style>
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
</style>