<section class="mt-10">
    <div class="mx-auto w-full max-w-[1400px] px-4 sm:px-6 lg:px-10">
        <div class="mt-6 rounded-2xl bg-slate-100 p-6">
            <h2 class="text-center text-lg font-bold text-orange-600 mb-4">都道府県を選択してください</h2>
            <!-- キャンバス（これが relative の親） -->
            <div class="relative mx-auto w-full max-w-[450px] aspect-[1535/1125]">
                <!-- 背景地図：必ず z-0 -->
                <img src="{{ asset('images/japan-map.png') }}"
                     alt="日本地図"
                     class="absolute inset-0 z-0 h-full w-full object-contain pointer-events-none select-none" />

                <!-- 配置レイヤー：必ず z-10 -->
                <div class="absolute inset-0 z-10">
                    @php
                    // Create prefecture mapping for slug lookup
                    $prefectureMapping = [
                        '北海道' => 'hokkaido',
                        '青森県' => 'aomori',
                        '岩手県' => 'iwate',
                        '宮城県' => 'miyagi',
                        '秋田県' => 'akita',
                        '山形県' => 'yamagata',
                        '福島県' => 'fukushima',
                        '茨城県' => 'ibaraki',
                        '栃木県' => 'tochigi',
                        '群馬県' => 'gunma',
                        '埼玉県' => 'saitama',
                        '千葉県' => 'chiba',
                        '東京都' => 'tokyo',
                        '神奈川県' => 'kanagawa',
                        '新潟県' => 'niigata',
                        '富山県' => 'toyama',
                        '石川県' => 'ishikawa',
                        '福井県' => 'fukui',
                        '山梨県' => 'yamanashi',
                        '長野県' => 'nagano',
                        '岐阜県' => 'gifu',
                        '静岡県' => 'shizuoka',
                        '愛知県' => 'aichi',
                        '三重県' => 'mie',
                        '滋賀県' => 'shiga',
                        '京都府' => 'kyoto',
                        '大阪府' => 'osaka',
                        '兵庫県' => 'hyogo',
                        '奈良県' => 'nara',
                        '和歌山県' => 'wakayama',
                        '鳥取県' => 'tottori',
                        '島根県' => 'shimane',
                        '岡山県' => 'okayama',
                        '広島県' => 'hiroshima',
                        '山口県' => 'yamaguchi',
                        '徳島県' => 'tokushima',
                        '香川県' => 'kagawa',
                        '愛媛県' => 'ehime',
                        '高知県' => 'kochi',
                        '福岡県' => 'fukuoka',
                        '佐賀県' => 'saga',
                        '長崎県' => 'nagasaki',
                        '熊本県' => 'kumamoto',
                        '大分県' => 'oita',
                        '宮崎県' => 'miyazaki',
                        '鹿児島県' => 'kagoshima',
                        '沖縄県' => 'okinawa',
                    ];
                    
                    $regions = [
                        ['id' => 'region-1', 'name' => '北海道・東北', 'left' => 85, 'top' => 15, 'prefectures' => ['北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県']],
                        ['id' => 'region-2', 'name' => '関東', 'left' => 85, 'top' => 55, 'prefectures' => ['茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県']],
                        ['id' => 'region-3', 'name' => '中部', 'left' => 50, 'top' => 45, 'prefectures' => ['新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県', '静岡県', '愛知県']],
                        ['id' => 'region-4', 'name' => '関西', 'left' => 50, 'top' => 70, 'prefectures' => ['三重県', '滋賀県', '京都府', '大阪府', '兵庫県', '奈良県', '和歌山県']],
                        ['id' => 'region-5', 'name' => '中国・四国', 'left' => 15, 'top' => 50, 'prefectures' => ['鳥取県', '島根県', '岡山県', '広島県', '山口県', '徳島県', '香川県', '愛媛県', '高知県']],
                        ['id' => 'region-6', 'name' => '九州・沖縄', 'left' => 15, 'top' => 85, 'prefectures' => ['福岡県', '佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県']]
                    ];
                    @endphp

                    @foreach($regions as $region)
                        <div class="absolute transform -translate-x-1/2 -translate-y-1/2 region-container"
                             style="left: {{ $region['left'] }}%; top: {{ $region['top'] }}%;"
                             data-region="{{ $region['id'] }}">
                            <!-- 地方ボタン -->
                            <button onclick="toggleRegion('{{ $region['id'] }}')"
                                    class="region-button bg-white hover:bg-gray-50 border border-gray-200 hover:border-gray-300 text-gray-800 rounded-lg px-5 py-2 text-base font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 min-w-[120px] whitespace-nowrap">
                                {{ $region['name'] }}
                            </button>
                            
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- モーダル背景 -->
        <div id="modal-overlay" class="hidden fixed inset-0 bg-black/50 z-40" onclick="closeModal()"></div>
        
        <!-- モーダル本体 -->
        <div id="modal-content" class="hidden fixed top-1/2 left-1/2 bg-white rounded-2xl shadow-2xl p-6 w-[85%] max-w-[380px] z-50 transition-all duration-300" style="transform: translate(-50%, -50%);">
            <!-- 閉じるボタン -->
            <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-2xl font-bold">
                ×
            </button>
            
            <!-- モーダル内容 -->
            <div id="modal-body">
                <!-- 動的に地方名と都道府県リストが入る -->
            </div>
        </div>

        <script>
        const regionsData = {
            'region-1': { name: '北海道・東北地方', prefectures: ['北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県'] },
            'region-2': { name: '関東地方', prefectures: ['茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県'] },
            'region-3': { name: '中部地方', prefectures: ['新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県', '静岡県', '愛知県'] },
            'region-4': { name: '関西地方', prefectures: ['三重県', '滋賀県', '京都府', '大阪府', '兵庫県', '奈良県', '和歌山県'] },
            'region-5': { name: '中国・四国地方', prefectures: ['鳥取県', '島根県', '岡山県', '広島県', '山口県', '徳島県', '香川県', '愛媛県', '高知県'] },
            'region-6': { name: '九州・沖縄地方', prefectures: ['福岡県', '佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県'] }
        };

        function toggleRegion(regionId) {
            const regionData = regionsData[regionId];
            if (!regionData) return;

            // モーダル内容を構築
            const modalBody = document.getElementById('modal-body');
            modalBody.innerHTML = `
                <h3 class="text-xl font-bold text-gray-800 mb-4">${regionData.name}</h3>
                <div class="grid grid-cols-1 gap-2 max-h-[300px] overflow-y-auto">
                    ${regionData.prefectures.map(prefecture => {
                        const slug = @json($prefectureMapping)[prefecture] || prefecture;
                        return `
                        <a href="{{ route('companies.index') }}?prefecture=${slug}" 
                           class="block w-full rounded-lg bg-gray-50 hover:bg-blue-50 border border-gray-200 hover:border-blue-300 px-4 py-3 text-left text-base font-medium text-gray-800 hover:text-blue-700 transition-all duration-200">
                            ${prefecture}
                        </a>`;
                    }).join('')}
                </div>
            `;

            // モーダル表示（スライドアニメーション）
            const modal = document.getElementById('modal-content');
            const overlay = document.getElementById('modal-overlay');
            
            overlay.classList.remove('hidden');
            modal.classList.remove('hidden');
            modal.style.transform = 'translate(-50%, -50%) scale(0.9) translateY(20px)';
            modal.style.opacity = '0';
            
            setTimeout(() => {
                modal.style.transform = 'translate(-50%, -50%) scale(1)';
                modal.style.opacity = '1';
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById('modal-content');
            const overlay = document.getElementById('modal-overlay');
            
            // スライドアウトアニメーション
            modal.style.transform = 'translate(-50%, -50%) scale(0.9)';
            modal.style.opacity = '0';
            
            setTimeout(() => {
                overlay.classList.add('hidden');
                modal.classList.add('hidden');
            }, 300);
        }

        // ESCキーでモーダルを閉じる
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
        </script>
        
        <style>
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateX(-50%) translateY(-10px); }
            to { opacity: 1; transform: translateX(-50%) translateY(0); }
        }
        
        .region-button {
            transition: all 0.2s ease;
        }
        
        .region-button:active {
            transform: scale(0.98);
        }
        </style>

        <!-- 人気都道府県（クイックアクセス） -->
        <div class="mt-12 text-center">
            <h3 class="text-lg font-bold text-gray-800 mb-4">人気都道府県</h3>
            <div class="flex flex-wrap justify-center gap-3">
                @foreach(['東京都', '大阪府', '神奈川県', '愛知県', '福岡県', '北海道'] as $popular)
                    <a href="{{ route('companies.index', ['prefecture' => $prefectureMapping[$popular]]) }}" 
                       class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition shadow-sm">
                        {{ $popular }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>