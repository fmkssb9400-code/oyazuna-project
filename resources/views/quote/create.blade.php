@extends('layouts.app')

@section('title', '高所ロープ専門業者に相談する - オヤズナ')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6 md:py-8">
    <h1 class="text-2xl md:text-3xl font-bold mb-6 md:mb-8">高所ロープ専門業者に相談する</h1>
    
    <!-- Wishlist Companies Display -->
    <div id="wishlist-companies-section" class="bg-orange-50 border border-orange-200 rounded-lg p-3 md:p-4 mb-6 md:mb-8" style="display: none;">
        <h3 class="text-base md:text-lg font-semibold text-orange-800 mb-3">ご選択中の専門業者：<span id="company-count">0</span>社</h3>
        <div id="wishlist-companies-list" class="space-y-2">
            <!-- Companies will be populated by JavaScript -->
        </div>
        <div class="text-sm text-orange-700 mt-3">
            <p class="mb-1">ご相談内容は一旦オヤズナにて確認させていただきます。</p>
            <p class="mb-1">内容を精査のうえ、適切な専門業者へ共有いたします。</p>
            <p>その後、業者より直接ご連絡させていただきます。</p>
        </div>
    </div>
    
    
    <form action="{{ route('quote.store') }}" method="POST" class="bg-white rounded-lg shadow p-4 md:p-8 space-y-4 md:space-y-6" id="quote-form">
        @csrf
        
        <!-- Hidden field for wishlist companies -->
        <input type="hidden" name="wishlist_companies" id="wishlist_companies_input" value="">
        
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded p-4">
                <ul class="list-disc list-inside text-red-600">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <!-- Client Type -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">依頼者区分 <span class="text-red-500">*</span></label>
            <div class="flex space-x-4">
                <label class="flex items-center">
                    <input type="radio" name="requester_type" value="corp" class="mr-2" {{ old('requester_type') === 'corp' ? 'checked' : '' }}>
                    法人
                </label>
                <label class="flex items-center">
                    <input type="radio" name="requester_type" value="personal" class="mr-2" {{ old('requester_type') === 'personal' ? 'checked' : '' }}>
                    個人
                </label>
            </div>
        </div>
        
        <!-- Company Name (if corp) -->
        <div id="company_name_field" class="hidden">
            <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">会社名 <span class="text-red-500">*</span></label>
            <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}"
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="contact_name" class="block text-sm font-medium text-gray-700 mb-2">ご担当者名 <span class="text-red-500">*</span></label>
                <input type="text" name="contact_name" id="contact_name" value="{{ old('contact_name') }}" required
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="例：田中太郎">
            </div>
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">メールアドレス <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">電話番号</label>
                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div class="mt-4 md:mt-6">
            <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 md:mb-4">建物情報</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                <div>
                    <label for="prefecture" class="block text-sm font-medium text-gray-700 mb-2">都道府県 <span class="text-red-500">*</span></label>
                    <select name="prefecture" id="prefecture" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">選択してください</option>
                        @foreach($prefectures as $prefecture)
                            <option value="{{ $prefecture->name }}" {{ old('prefecture') == $prefecture->name ? 'selected' : '' }}>
                                {{ $prefecture->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">市区町村</label>
                    <input type="text" name="city" id="city" value="{{ old('city') }}"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="例：新宿区">
                </div>
                
                <div>
                    <label for="building_name" class="block text-sm font-medium text-gray-700 mb-2">建物名 <span class="text-green-600">（入力推奨）</span></label>
                    <input type="text" name="building_name" id="building_name" value="{{ old('building_name') }}"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="例：○○ビル、○○マンション、○○商業施設">
                    <p class="text-xs text-gray-500 mt-1">※業者が事前確認しやすくなるため入力をお勧めします</p>
                </div>
                
                <div>
                    <label for="floors" class="block text-sm font-medium text-gray-700 mb-2">階数 <span class="text-red-500">*</span></label>
                    <input type="number" name="floors" id="floors" min="1" value="{{ old('floors') }}" required
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="例：10">
                </div>
                
                <div>
                    <label for="height_m" class="block text-sm font-medium text-gray-700 mb-2">建物高さ（概算）</label>
                    <input type="number" name="height_m" id="height_m" min="1" value="{{ old('height_m') }}"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="例：30（メートル）">
                </div>
                
                <div>
                    <label for="building_type" class="block text-sm font-medium text-gray-700 mb-2">建物種別</label>
                    <select name="building_type" id="building_type" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">選択してください</option>
                        @foreach($buildingTypes as $buildingType)
                            <option value="{{ $buildingType->label }}" {{ old('building_type') == $buildingType->label ? 'selected' : '' }}>
                                {{ $buildingType->label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="built_year" class="block text-sm font-medium text-gray-700 mb-2">建築年</label>
                    <input type="number" name="built_year" id="built_year" min="1900" max="{{ date('Y') }}" value="{{ old('built_year') }}"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="例：2015">
                </div>
            </div>
        </div>

        <!-- ロープ作業可否判断 -->
        <div class="mt-4 md:mt-6">
            <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 md:mb-4">ロープ作業可否判断</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                <div>
                    <label for="rooftop_access" class="block text-sm font-medium text-gray-700 mb-2">屋上・屋根へのアクセス</label>
                    <select name="rooftop_access" id="rooftop_access" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">不明</option>
                        <option value="可" {{ old('rooftop_access') === '可' ? 'selected' : '' }}>可</option>
                        <option value="不可" {{ old('rooftop_access') === '不可' ? 'selected' : '' }}>不可</option>
                        <option value="要相談" {{ old('rooftop_access') === '要相談' ? 'selected' : '' }}>要相談</option>
                    </select>
                </div>
                
                <div>
                    <label for="parapet" class="block text-sm font-medium text-gray-700 mb-2">パラペット（屋上の立ち上がり壁）</label>
                    <select name="parapet" id="parapet" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">不明</option>
                        <option value="あり" {{ old('parapet') === 'あり' ? 'selected' : '' }}>あり</option>
                        <option value="なし" {{ old('parapet') === 'なし' ? 'selected' : '' }}>なし</option>
                    </select>
                </div>
                
                <div>
                    <label for="handrail" class="block text-sm font-medium text-gray-700 mb-2">手すり・柵</label>
                    <select name="handrail" id="handrail" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">不明</option>
                        <option value="あり" {{ old('handrail') === 'あり' ? 'selected' : '' }}>あり</option>
                        <option value="なし" {{ old('handrail') === 'なし' ? 'selected' : '' }}>なし</option>
                    </select>
                </div>
                
                <div>
                    <label for="obstacles" class="block text-sm font-medium text-gray-700 mb-2">障害物（看板・配管等）</label>
                    <select name="obstacles" id="obstacles" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">不明</option>
                        <option value="あり" {{ old('obstacles') === 'あり' ? 'selected' : '' }}>あり</option>
                        <option value="なし" {{ old('obstacles') === 'なし' ? 'selected' : '' }}>なし</option>
                    </select>
                </div>
                
                <div class="md:col-span-2">
                    <label for="obstacles_detail" class="block text-sm font-medium text-gray-700 mb-2">障害物の詳細</label>
                    <textarea name="obstacles_detail" id="obstacles_detail" rows="2" 
                              class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="例：看板、室外機、配管、突起物など">{{ old('obstacles_detail') }}</textarea>
                </div>
            </div>
        </div>

        <!-- 作業条件 -->
        <div class="mt-4 md:mt-6">
            <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 md:mb-4">作業条件</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                <div>
                    <label for="work_time" class="block text-sm font-medium text-gray-700 mb-2">希望作業時間帯</label>
                    <select name="work_time" id="work_time" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">指定しない</option>
                        <option value="昼" {{ old('work_time') === '昼' ? 'selected' : '' }}>昼間（9-17時）</option>
                        <option value="夜" {{ old('work_time') === '夜' ? 'selected' : '' }}>夜間（18時以降）</option>
                        <option value="土日" {{ old('work_time') === '土日' ? 'selected' : '' }}>土日</option>
                    </select>
                </div>
                
                <div>
                    <label for="environment" class="block text-sm font-medium text-gray-700 mb-2">周辺環境</label>
                    <select name="environment" id="environment" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">不明</option>
                        <option value="人通り多い" {{ old('environment') === '人通り多い' ? 'selected' : '' }}>人通り多い</option>
                        <option value="人通り少ない" {{ old('environment') === '人通り少ない' ? 'selected' : '' }}>人通り少ない</option>
                    </select>
                </div>
                
                <div>
                    <label for="urgency" class="block text-sm font-medium text-gray-700 mb-2">緊急度</label>
                    <select name="urgency" id="urgency" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">選択してください</option>
                        <option value="すぐ" {{ old('urgency') === 'すぐ' ? 'selected' : '' }}>すぐ</option>
                        <option value="1ヶ月以内" {{ old('urgency') === '1ヶ月以内' ? 'selected' : '' }}>1ヶ月以内</option>
                        <option value="未定" {{ old('urgency') === '未定' ? 'selected' : '' }}>未定</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 作業内容選択 -->
        <div class="mt-4 md:mt-6" id="service-type-section">
            <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 md:mb-4">作業内容</h3>
            <div>
                <label for="service_type" class="block text-sm font-medium text-gray-700 mb-2">依頼内容 <span class="text-red-500">*</span></label>
                <select name="service_type" id="service_type" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">選択してください</option>
                    <option value="window_cleaning" {{ old('service_type') === 'window_cleaning' ? 'selected' : '' }}>窓ガラス清掃</option>
                    <option value="wall_painting" {{ old('service_type') === 'wall_painting' ? 'selected' : '' }}>外壁塗装</option>
                    <option value="wall_inspection" {{ old('service_type') === 'wall_inspection' ? 'selected' : '' }}>外壁調査・点検</option>
                    <option value="wall_repair" {{ old('service_type') === 'wall_repair' ? 'selected' : '' }}>外壁補修</option>
                    <option value="bird_control" {{ old('service_type') === 'bird_control' ? 'selected' : '' }}>鳥害対策</option>
                    <option value="sign_work" {{ old('service_type') === 'sign_work' ? 'selected' : '' }}>看板作業</option>
                    <option value="leak_inspection" {{ old('service_type') === 'leak_inspection' ? 'selected' : '' }}>雨漏り調査</option>
                    <option value="other" {{ old('service_type') === 'other' ? 'selected' : '' }}>その他</option>
                </select>
            </div>
        </div>

        <!-- ガラス清掃詳細 -->
        <div class="mt-4 md:mt-6" id="glass-cleaning-section" style="display: none;">
            <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 md:mb-4">ガラス清掃詳細</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                <div>
                    <label for="glass_side" class="block text-sm font-medium text-gray-700 mb-2">清掃対象面 <span class="text-red-500">*</span></label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="glass_side" value="外側のみ" class="mr-2" {{ old('glass_side') === '外側のみ' ? 'checked' : '' }}>
                            外側のみ
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="glass_side" value="内側のみ" class="mr-2" {{ old('glass_side') === '内側のみ' ? 'checked' : '' }}>
                            内側のみ
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="glass_side" value="内外両方" class="mr-2" {{ old('glass_side') === '内外両方' ? 'checked' : '' }}>
                            内外両方
                        </label>
                    </div>
                </div>
                
                <div>
                    <label for="glass_area" class="block text-sm font-medium text-gray-700 mb-2">ガラス面積（㎡） <span class="text-red-500">*</span></label>
                    <input type="number" name="glass_area" id="glass_area" min="1" step="0.1" value="{{ old('glass_area') }}" required
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="例：150">
                </div>
                
                <div>
                    <label for="glass_count" class="block text-sm font-medium text-gray-700 mb-2">窓の枚数・面数 <span class="text-red-500">*</span></label>
                    <input type="number" name="glass_count" id="glass_count" min="1" value="{{ old('glass_count') }}" required
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="例：50">
                </div>
                
                <div>
                    <label for="cleaning_frequency" class="block text-sm font-medium text-gray-700 mb-2">清掃頻度</label>
                    <select name="cleaning_frequency" id="cleaning_frequency" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="単発" {{ old('cleaning_frequency') === '単発' ? 'selected' : '' }}>単発</option>
                        <option value="定期" {{ old('cleaning_frequency') === '定期' ? 'selected' : '' }}>定期</option>
                    </select>
                </div>
                
                <div>
                    <label for="last_cleaning_date" class="block text-sm font-medium text-gray-700 mb-2">前回清掃日</label>
                    <input type="date" name="last_cleaning_date" id="last_cleaning_date" value="{{ old('last_cleaning_date') }}"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label for="gondola_available" class="block text-sm font-medium text-gray-700 mb-2">ゴンドラ設備</label>
                    <select name="gondola_available" id="gondola_available" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">不明</option>
                        <option value="あり" {{ old('gondola_available') === 'あり' ? 'selected' : '' }}>あり</option>
                        <option value="なし" {{ old('gondola_available') === 'なし' ? 'selected' : '' }}>なし</option>
                    </select>
                </div>
                
                <div>
                    <label for="water_available" class="block text-sm font-medium text-gray-700 mb-2">給水設備</label>
                    <select name="water_available" id="water_available" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">不明</option>
                        <option value="あり" {{ old('water_available') === 'あり' ? 'selected' : '' }}>あり</option>
                        <option value="なし" {{ old('water_available') === 'なし' ? 'selected' : '' }}>なし</option>
                    </select>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-3">汚れ種類（複数選択可）</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="dirt_types[]" value="水垢" class="mr-2" {{ is_array(old('dirt_types')) && in_array('水垢', old('dirt_types')) ? 'checked' : '' }}>
                            水垢
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="dirt_types[]" value="油汚れ" class="mr-2" {{ is_array(old('dirt_types')) && in_array('油汚れ', old('dirt_types')) ? 'checked' : '' }}>
                            油汚れ
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="dirt_types[]" value="鳥のフン" class="mr-2" {{ is_array(old('dirt_types')) && in_array('鳥のフン', old('dirt_types')) ? 'checked' : '' }}>
                            鳥のフン
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="dirt_types[]" value="その他" class="mr-2" {{ is_array(old('dirt_types')) && in_array('その他', old('dirt_types')) ? 'checked' : '' }}>
                            その他
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- 外壁塗装詳細 -->
        <div class="mt-4 md:mt-6" id="wall-painting-section" style="display: none;">
            <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 md:mb-4">外壁塗装詳細</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                <div>
                    <label for="wall_area" class="block text-sm font-medium text-gray-700 mb-2">塗装面積（㎡） <span class="text-red-500">*</span></label>
                    <input type="number" name="wall_area" id="wall_area" min="1" step="0.1" value="{{ old('wall_area') }}"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="例：300">
                </div>
                
                <div>
                    <label for="wall_material" class="block text-sm font-medium text-gray-700 mb-2">外壁材質</label>
                    <select name="wall_material" id="wall_material" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">不明</option>
                        <option value="コンクリート" {{ old('wall_material') === 'コンクリート' ? 'selected' : '' }}>コンクリート</option>
                        <option value="モルタル" {{ old('wall_material') === 'モルタル' ? 'selected' : '' }}>モルタル</option>
                        <option value="サイディング" {{ old('wall_material') === 'サイディング' ? 'selected' : '' }}>サイディング</option>
                        <option value="ALC" {{ old('wall_material') === 'ALC' ? 'selected' : '' }}>ALC</option>
                        <option value="タイル" {{ old('wall_material') === 'タイル' ? 'selected' : '' }}>タイル</option>
                        <option value="その他" {{ old('wall_material') === 'その他' ? 'selected' : '' }}>その他</option>
                    </select>
                </div>
                
                <div>
                    <label for="paint_type" class="block text-sm font-medium text-gray-700 mb-2">希望塗料タイプ</label>
                    <select name="paint_type" id="paint_type" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">指定しない</option>
                        <option value="アクリル" {{ old('paint_type') === 'アクリル' ? 'selected' : '' }}>アクリル</option>
                        <option value="ウレタン" {{ old('paint_type') === 'ウレタン' ? 'selected' : '' }}>ウレタン</option>
                        <option value="シリコン" {{ old('paint_type') === 'シリコン' ? 'selected' : '' }}>シリコン</option>
                        <option value="フッ素" {{ old('paint_type') === 'フッ素' ? 'selected' : '' }}>フッ素</option>
                        <option value="無機" {{ old('paint_type') === '無機' ? 'selected' : '' }}>無機</option>
                    </select>
                </div>
                
                <div>
                    <label for="painting_reason" class="block text-sm font-medium text-gray-700 mb-2">塗装理由</label>
                    <select name="painting_reason" id="painting_reason" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">選択してください</option>
                        <option value="定期メンテナンス" {{ old('painting_reason') === '定期メンテナンス' ? 'selected' : '' }}>定期メンテナンス</option>
                        <option value="色あせ・汚れ" {{ old('painting_reason') === '色あせ・汚れ' ? 'selected' : '' }}>色あせ・汚れ</option>
                        <option value="ひび割れ補修" {{ old('painting_reason') === 'ひび割れ補修' ? 'selected' : '' }}>ひび割れ補修</option>
                        <option value="防水性向上" {{ old('painting_reason') === '防水性向上' ? 'selected' : '' }}>防水性向上</option>
                        <option value="美観向上" {{ old('painting_reason') === '美観向上' ? 'selected' : '' }}>美観向上</option>
                    </select>
                </div>
                
                <div>
                    <label for="last_painting_date" class="block text-sm font-medium text-gray-700 mb-2">前回塗装時期</label>
                    <input type="date" name="last_painting_date" id="last_painting_date" value="{{ old('last_painting_date') }}"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label for="scaffold_available" class="block text-sm font-medium text-gray-700 mb-2">足場設置可否</label>
                    <select name="scaffold_available" id="scaffold_available" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">不明</option>
                        <option value="可能" {{ old('scaffold_available') === '可能' ? 'selected' : '' }}>可能</option>
                        <option value="困難" {{ old('scaffold_available') === '困難' ? 'selected' : '' }}>困難</option>
                        <option value="要相談" {{ old('scaffold_available') === '要相談' ? 'selected' : '' }}>要相談</option>
                    </select>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-3">希望色・仕上がり</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="color_preferences[]" value="白系" class="mr-2" {{ is_array(old('color_preferences')) && in_array('白系', old('color_preferences')) ? 'checked' : '' }}>
                            白系
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="color_preferences[]" value="グレー系" class="mr-2" {{ is_array(old('color_preferences')) && in_array('グレー系', old('color_preferences')) ? 'checked' : '' }}>
                            グレー系
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="color_preferences[]" value="ベージュ系" class="mr-2" {{ is_array(old('color_preferences')) && in_array('ベージュ系', old('color_preferences')) ? 'checked' : '' }}>
                            ベージュ系
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="color_preferences[]" value="現状維持" class="mr-2" {{ is_array(old('color_preferences')) && in_array('現状維持', old('color_preferences')) ? 'checked' : '' }}>
                            現状維持
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- その他作業詳細 -->
        <div class="mt-4 md:mt-6" id="other-work-section" style="display: none;">
            <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 md:mb-4">作業詳細</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                <div>
                    <label for="work_area" class="block text-sm font-medium text-gray-700 mb-2">作業面積（㎡）</label>
                    <input type="number" name="work_area" id="work_area" min="1" step="0.1" value="{{ old('work_area') }}"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="例：100">
                </div>
                
                <div>
                    <label for="work_location" class="block text-sm font-medium text-gray-700 mb-2">作業箇所</label>
                    <input type="text" name="work_location" id="work_location" value="{{ old('work_location') }}"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="例：建物南面、屋上、看板周辺">
                </div>
                
                <div class="md:col-span-2">
                    <label for="work_detail" class="block text-sm font-medium text-gray-700 mb-2">作業詳細</label>
                    <textarea name="work_detail" id="work_detail" rows="3" 
                              class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="具体的な作業内容をご記入ください">{{ old('work_detail') }}</textarea>
                </div>
            </div>
        </div>

        <!-- 添付 -->
        <div class="mt-4 md:mt-6">
            <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-3 md:mb-4">添付資料（任意）</h3>
            <div>
                <label for="images" class="block text-sm font-medium text-gray-700 mb-2">建物・現場写真</label>
                <input type="file" name="images[]" id="images" multiple accept="image/*"
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">※複数枚選択可能。建物外観、作業対象箇所の写真があると見積もり精度が向上します。</p>
            </div>
        </div>
        
        <!-- Priority Selection -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-3">今回のご依頼で特に重視したい点は？</label>
            <p class="text-xs text-gray-500 mb-3">※複数選択OK</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <label class="flex items-center">
                    <input type="checkbox" name="priorities[]" value="低価格" class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ is_array(old('priorities')) && in_array('低価格', old('priorities')) ? 'checked' : '' }}>
                    <span class="text-sm">価格を抑えたい</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="priorities[]" value="安全対策" class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ is_array(old('priorities')) && in_array('安全対策', old('priorities')) ? 'checked' : '' }}>
                    <span class="text-sm">安全対策がしっかりしている会社</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="priorities[]" value="高所実績" class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ is_array(old('priorities')) && in_array('高所実績', old('priorities')) ? 'checked' : '' }}>
                    <span class="text-sm">高所実績が豊富</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="priorities[]" value="迅速対応" class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ is_array(old('priorities')) && in_array('迅速対応', old('priorities')) ? 'checked' : '' }}>
                    <span class="text-sm">すぐ対応してほしい</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="priorities[]" value="大型ビル対応" class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ is_array(old('priorities')) && in_array('大型ビル対応', old('priorities')) ? 'checked' : '' }}>
                    <span class="text-sm">大型ビル対応可能</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" name="priorities[]" value="相談重視" class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ is_array(old('priorities')) && in_array('相談重視', old('priorities')) ? 'checked' : '' }}>
                    <span class="text-sm">とにかく相談したい（お任せ）</span>
                </label>
            </div>
        </div>
        
        <div>
            <label for="note" class="block text-sm font-medium text-gray-700 mb-2">備考</label>
            <textarea name="note" id="note" rows="4" 
                      class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                      placeholder="その他ご要望があればお書きください">{{ old('note') }}</textarea>
        </div>
        
        <div class="text-center">
            <button type="submit" class="bg-orange-600 text-white px-12 py-4 rounded-lg font-bold text-lg hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500">
                無料見積もりを依頼
            </button>
        </div>
        
        <div class="text-sm text-gray-600 text-center">
            <p>※送信後、各業者から直接連絡が来ます。</p>
            <p>※高所作業の安全性・保険・資格については各業者へ直接ご確認ください。</p>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load wishlist from localStorage
    let wishlist = JSON.parse(localStorage.getItem('companyWishlist') || '[]');
    
    function displayWishlistCompanies() {
        const section = document.getElementById('wishlist-companies-section');
        const countSpan = document.getElementById('company-count');
        const companiesList = document.getElementById('wishlist-companies-list');
        const hiddenInput = document.getElementById('wishlist_companies_input');
        
        if (wishlist.length > 0) {
            section.style.display = 'block';
            countSpan.textContent = wishlist.length;
            
            // Create company display elements
            companiesList.innerHTML = '';
            wishlist.forEach((company, index) => {
                const companyElement = document.createElement('div');
                companyElement.className = 'flex items-center justify-between bg-white px-4 py-3 rounded-lg border';
                companyElement.innerHTML = `
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                            ${index + 1}
                        </div>
                        <span class="font-medium text-gray-900">${company.name}</span>
                    </div>
                    <button type="button" 
                            class="text-gray-400 hover:text-red-500 transition-colors duration-200" 
                            onclick="removeFromWishlist(${company.id})">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                `;
                companiesList.appendChild(companyElement);
            });
            
            // Set hidden input value
            hiddenInput.value = JSON.stringify(wishlist);
        } else {
            section.style.display = 'none';
            hiddenInput.value = '';
        }
    }
    
    // Remove company from wishlist
    window.removeFromWishlist = function(companyId) {
        wishlist = wishlist.filter(company => company.id !== companyId);
        localStorage.setItem('companyWishlist', JSON.stringify(wishlist));
        displayWishlistCompanies();
    };
    
    // Initialize display
    displayWishlistCompanies();
    
    // Form submission handler
    document.getElementById('quote-form').addEventListener('submit', function(e) {
        // Update hidden input before submission
        document.getElementById('wishlist_companies_input').value = JSON.stringify(wishlist);
    });
});

function removeFromCompare(companyId) {
    $.ajax({
        url: '/compare/remove/' + companyId,
        method: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function(data) {
        if (data.success) {
            location.reload();
        }
    });
}

// Handle requester type changes
document.querySelectorAll('input[name="requester_type"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        const companyNameField = document.getElementById('company_name_field');
        const companyNameInput = document.getElementById('company_name');
        const serviceTypeSection = document.getElementById('service-type-section');
        const serviceTypeSelect = document.getElementById('service_type');
        const glassSection = document.getElementById('glass-cleaning-section');
        const wallSection = document.getElementById('wall-painting-section');
        const otherSection = document.getElementById('other-work-section');
        
        if (this.value === 'corp') {
            // Show company name field
            companyNameField.classList.remove('hidden');
            companyNameInput.setAttribute('required', 'required');
            
            // Show service type and work sections
            serviceTypeSection.style.display = 'block';
            serviceTypeSelect.setAttribute('required', 'required');
            
            // Trigger service type change to show appropriate work section
            if (serviceTypeSelect.value) {
                serviceTypeSelect.dispatchEvent(new Event('change'));
            }
        } else {
            // Hide company name field
            companyNameField.classList.add('hidden');
            companyNameInput.removeAttribute('required');
            
            // Hide all work-related sections for personal users
            serviceTypeSection.style.display = 'none';
            serviceTypeSelect.removeAttribute('required');
            glassSection.style.display = 'none';
            wallSection.style.display = 'none';
            otherSection.style.display = 'none';
            
            // Remove required attributes from all work detail fields
            document.querySelectorAll('input[name="glass_side"]').forEach(radio => radio.removeAttribute('required'));
            document.getElementById('glass_area').removeAttribute('required');
            document.getElementById('glass_count').removeAttribute('required');
            document.getElementById('wall_area').removeAttribute('required');
        }
    });
});

// Initialize on page load
const checkedRequesterType = document.querySelector('input[name="requester_type"]:checked');
if (checkedRequesterType) {
    checkedRequesterType.dispatchEvent(new Event('change'));
}

// Load wishlist companies
function loadWishlistCompanies() {
    let wishlist = JSON.parse(localStorage.getItem('companyWishlist') || '[]');
    
    if (wishlist.length > 0) {
        const section = document.getElementById('wishlist-companies-section');
        const list = document.getElementById('wishlist-companies-list');
        const countElement = document.getElementById('company-count');
        const hiddenInput = document.getElementById('wishlist_companies_input');
        
        // Show section
        section.style.display = 'block';
        
        // Update count
        countElement.textContent = wishlist.length;
        
        // Clear existing list
        list.innerHTML = '';
        
        // Add companies to list
        wishlist.forEach(function(company) {
            const companyDiv = document.createElement('div');
            companyDiv.className = 'flex items-center justify-between bg-white p-2 rounded border';
            companyDiv.innerHTML = `
                <span class="font-medium">${company.name}</span>
                <button type="button" onclick="removeFromWishlist(${company.id})" class="text-red-600 hover:text-red-800 text-sm">
                    削除
                </button>
            `;
            list.appendChild(companyDiv);
        });
        
        // Set hidden input value
        hiddenInput.value = JSON.stringify(wishlist);
    }
}

function removeFromWishlist(companyId) {
    let wishlist = JSON.parse(localStorage.getItem('companyWishlist') || '[]');
    wishlist = wishlist.filter(item => item.id != companyId);
    localStorage.setItem('companyWishlist', JSON.stringify(wishlist));
    loadWishlistCompanies();
}

// Load wishlist on page load
document.addEventListener('DOMContentLoaded', function() {
    loadWishlistCompanies();
});

// Dynamic form sections based on service type
document.getElementById('service_type').addEventListener('change', function() {
    const serviceType = this.value;
    const glassSection = document.getElementById('glass-cleaning-section');
    const wallSection = document.getElementById('wall-painting-section');
    const otherSection = document.getElementById('other-work-section');
    
    // Hide all sections first
    glassSection.style.display = 'none';
    wallSection.style.display = 'none';
    otherSection.style.display = 'none';
    
    // Show appropriate section based on selection
    if (serviceType === 'window_cleaning') {
        glassSection.style.display = 'block';
        // Set required fields for glass cleaning
        document.querySelectorAll('input[name="glass_side"]').forEach(radio => radio.setAttribute('required', 'required'));
        document.getElementById('glass_area').setAttribute('required', 'required');
        document.getElementById('glass_count').setAttribute('required', 'required');
        // Remove required from other sections
        document.getElementById('wall_area').removeAttribute('required');
    } else if (serviceType === 'wall_painting') {
        wallSection.style.display = 'block';
        // Set required fields for wall painting
        document.getElementById('wall_area').setAttribute('required', 'required');
        // Remove required from other sections
        document.querySelectorAll('input[name="glass_side"]').forEach(radio => radio.removeAttribute('required'));
        document.getElementById('glass_area').removeAttribute('required');
        document.getElementById('glass_count').removeAttribute('required');
    } else if (serviceType && serviceType !== '') {
        otherSection.style.display = 'block';
        // Remove required from all specific sections
        document.querySelectorAll('input[name="glass_side"]').forEach(radio => radio.removeAttribute('required'));
        document.getElementById('glass_area').removeAttribute('required');
        document.getElementById('glass_count').removeAttribute('required');
        document.getElementById('wall_area').removeAttribute('required');
    }
});

// Initialize on page load if service_type is already selected
if (document.getElementById('service_type').value) {
    document.getElementById('service_type').dispatchEvent(new Event('change'));
}
</script>
@endsection