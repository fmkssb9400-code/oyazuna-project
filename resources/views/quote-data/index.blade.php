@extends('layouts.app')

@section('title', '見積もりデータ一覧 - オヤズナ | 高所ロープ作業の見積もり・相場データベース')

@section('content')
<div class="min-h-screen bg-blue-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Main Content with Sidebar -->
        <div class="max-w-7xl mx-auto px-4">
            <div class="lg:grid lg:grid-cols-3 lg:gap-8">
                <!-- 左：メインコンテンツ -->
                <div class="lg:col-span-2 space-y-6">
                    <h1 class="text-3xl font-bold text-gray-900 mb-8 flex items-center">
                        <img src="{{ asset('images/estimate.png') }}" alt="見積もりデータ" class="w-10 h-10 mr-3">
                        見積もりデータ一覧
                    </h1>
                    
                    <!-- タブナビゲーション -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-lg overflow-hidden">
                        <div class="border-b border-gray-200">
                            <nav class="flex overflow-x-auto">
                                <button class="service-tab flex-1 py-4 px-6 text-sm font-medium text-center whitespace-nowrap border-b-2 border-blue-500 text-blue-600 bg-blue-50" data-service="window">
                                    窓ガラス清掃
                                </button>
                                <button class="service-tab flex-1 py-4 px-6 text-sm font-medium text-center whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-service="inspection">
                                    外壁調査
                                </button>
                                <button class="service-tab flex-1 py-4 px-6 text-sm font-medium text-center whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-service="repair">
                                    外壁補修
                                </button>
                                <button class="service-tab flex-1 py-4 px-6 text-sm font-medium text-center whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-service="painting">
                                    外壁塗装
                                </button>
                                <button class="service-tab flex-1 py-4 px-6 text-sm font-medium text-center whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-service="bird_control">
                                    鳥害対策
                                </button>
                                <button class="service-tab flex-1 py-4 px-6 text-sm font-medium text-center whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-service="sign">
                                    看板作業
                                </button>
                                <button class="service-tab flex-1 py-4 px-6 text-sm font-medium text-center whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-service="leak">
                                    雨漏り調査
                                </button>
                                <button class="service-tab flex-1 py-4 px-6 text-sm font-medium text-center whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-service="other">
                                    その他
                                </button>
                            </nav>
                        </div>

                        <!-- タブコンテンツ -->
                        <div class="p-8">
                            <!-- 窓ガラス清掃 -->
                            <div id="tab-window" class="tab-content">
                                <h3 class="text-xl font-bold text-gray-900 mb-6">窓ガラス清掃の見積もりデータ</h3>
                                
                                <div class="space-y-6">
                                    <!-- 見積もりカード 1 -->
                                    <div class="bg-white border border-gray-300 rounded-lg shadow-sm">
                                        <div class="bg-gray-100 px-6 py-3 border-b border-gray-200">
                                            <div class="grid grid-cols-5 gap-4 text-sm">
                                                <div><span class="font-semibold">日付:</span> 2026/3/15</div>
                                                <div><span class="font-semibold">物件名:</span> オフィスビルA</div>
                                                <div><span class="font-semibold">クライアント名:</span> ABC不動産管理株式会社</div>
                                                <div><span class="font-semibold">金額:</span> 180,000円</div>
                                                <div><span class="font-semibold">担当者:</span> 田中</div>
                                            </div>
                                        </div>
                                        <div class="p-6">
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full">
                                                    <thead class="bg-green-100">
                                                        <tr>
                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">項目名</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単位</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">数量</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単価/円</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">回/年</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">金額/円</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-200">
                                                        <tr class="bg-gray-50">
                                                            <td class="px-4 py-2 text-sm font-medium">定期窓ガラス清掃（年4回）</td>
                                                            <td class="px-4 py-2 text-center text-sm"></td>
                                                            <td class="px-4 py-2 text-center text-sm"></td>
                                                            <td class="px-4 py-2 text-center text-sm"></td>
                                                            <td class="px-4 py-2 text-center text-sm"></td>
                                                            <td class="px-4 py-2 text-center text-sm">0</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm font-medium">外装ガラス清掃費</td>
                                                            <td class="px-4 py-2 text-center text-sm"></td>
                                                            <td class="px-4 py-2 text-center text-sm"></td>
                                                            <td class="px-4 py-2 text-center text-sm"></td>
                                                            <td class="px-4 py-2 text-center text-sm"></td>
                                                            <td class="px-4 py-2 text-center text-sm">0</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm pl-8">① 外装ガラス/外面のみ</td>
                                                            <td class="px-4 py-2 text-center text-sm">㎡</td>
                                                            <td class="px-4 py-2 text-center text-sm">306.00</td>
                                                            <td class="px-4 py-2 text-center text-sm">80</td>
                                                            <td class="px-4 py-2 text-center text-sm">4</td>
                                                            <td class="px-4 py-2 text-center text-sm text-blue-600 font-medium">97,920</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm pl-8">② 手摺ガラス/両面</td>
                                                            <td class="px-4 py-2 text-center text-sm">㎡</td>
                                                            <td class="px-4 py-2 text-center text-sm">90.00</td>
                                                            <td class="px-4 py-2 text-center text-sm">160</td>
                                                            <td class="px-4 py-2 text-center text-sm">4</td>
                                                            <td class="px-4 py-2 text-center text-sm text-blue-600 font-medium">57,600</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm">地上監視員配置費</td>
                                                            <td class="px-4 py-2 text-center text-sm">人工</td>
                                                            <td class="px-4 py-2 text-center text-sm">0.50</td>
                                                            <td class="px-4 py-2 text-center text-sm">18500</td>
                                                            <td class="px-4 py-2 text-center text-sm">4</td>
                                                            <td class="px-4 py-2 text-center text-sm text-blue-600 font-medium">37,000</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm">資材搬入出費</td>
                                                            <td class="px-4 py-2 text-center text-sm">台</td>
                                                            <td class="px-4 py-2 text-center text-sm">1.00</td>
                                                            <td class="px-4 py-2 text-center text-sm">4000</td>
                                                            <td class="px-4 py-2 text-center text-sm">4</td>
                                                            <td class="px-4 py-2 text-center text-sm text-blue-600 font-medium">16,000</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="mt-4 flex justify-between items-center">
                                                <div class="text-sm text-gray-600">東京都 | 施工面積: 396㎡</div>
                                                <div class="text-lg font-bold text-blue-600">合計: ¥208,520</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 見積もりカード 2 -->
                                    <div class="bg-white border border-gray-300 rounded-lg shadow-sm">
                                        <div class="bg-gray-100 px-6 py-3 border-b border-gray-200">
                                            <div class="grid grid-cols-5 gap-4 text-sm">
                                                <div><span class="font-semibold">日付:</span> 2026/3/20</div>
                                                <div><span class="font-semibold">物件名:</span> レジデンスB</div>
                                                <div><span class="font-semibold">クライアント名:</span> マンション管理組合</div>
                                                <div><span class="font-semibold">金額:</span> 85,000円</div>
                                                <div><span class="font-semibold">担当者:</span> 佐藤</div>
                                            </div>
                                        </div>
                                        <div class="p-6">
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full">
                                                    <thead class="bg-green-100">
                                                        <tr>
                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">項目名</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単位</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">数量</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単価/円</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">回/年</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">金額/円</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-200">
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm">定期窓ガラス清掃（月1回）</td>
                                                            <td class="px-4 py-2 text-center text-sm">式</td>
                                                            <td class="px-4 py-2 text-center text-sm">1.00</td>
                                                            <td class="px-4 py-2 text-center text-sm">85000</td>
                                                            <td class="px-4 py-2 text-center text-sm">1</td>
                                                            <td class="px-4 py-2 text-center text-sm text-blue-600 font-medium">85,000</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="mt-4 flex justify-between items-center">
                                                <div class="text-sm text-gray-600">大阪府 | 施工面積: 150㎡</div>
                                                <div class="text-lg font-bold text-blue-600">合計: ¥85,000</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 見積もりカード 3 -->
                                    <div class="bg-white border border-gray-300 rounded-lg shadow-sm">
                                        <div class="bg-gray-100 px-6 py-3 border-b border-gray-200">
                                            <div class="grid grid-cols-5 gap-4 text-sm">
                                                <div><span class="font-semibold">日付:</span> 2026/3/25</div>
                                                <div><span class="font-semibold">物件名:</span> 戸建住宅C</div>
                                                <div><span class="font-semibold">クライアント名:</span> 山田様邸</div>
                                                <div><span class="font-semibold">金額:</span> 45,000円</div>
                                                <div><span class="font-semibold">担当者:</span> 鈴木</div>
                                            </div>
                                        </div>
                                        <div class="p-6">
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full">
                                                    <thead class="bg-green-100">
                                                        <tr>
                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">項目名</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単位</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">数量</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単価/円</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">回/年</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">金額/円</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-200">
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm">スポット窓ガラス清掃</td>
                                                            <td class="px-4 py-2 text-center text-sm">㎡</td>
                                                            <td class="px-4 py-2 text-center text-sm">80.00</td>
                                                            <td class="px-4 py-2 text-center text-sm">450</td>
                                                            <td class="px-4 py-2 text-center text-sm">1</td>
                                                            <td class="px-4 py-2 text-center text-sm text-blue-600 font-medium">36,000</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm">出張費</td>
                                                            <td class="px-4 py-2 text-center text-sm">式</td>
                                                            <td class="px-4 py-2 text-center text-sm">1.00</td>
                                                            <td class="px-4 py-2 text-center text-sm">9000</td>
                                                            <td class="px-4 py-2 text-center text-sm">1</td>
                                                            <td class="px-4 py-2 text-center text-sm text-blue-600 font-medium">9,000</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="mt-4 flex justify-between items-center">
                                                <div class="text-sm text-gray-600">愛知県 | 施工面積: 80㎡</div>
                                                <div class="text-lg font-bold text-blue-600">合計: ¥45,000</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 外壁調査 -->
                            <div id="tab-inspection" class="tab-content hidden">
                                <h3 class="text-xl font-bold text-gray-900 mb-6">外壁調査の見積もりデータ</h3>
                                
                                <div class="space-y-6">
                                    <!-- 見積もりカード 1 -->
                                    <div class="bg-white border border-gray-300 rounded-lg shadow-sm">
                                        <div class="bg-gray-100 px-6 py-3 border-b border-gray-200">
                                            <div class="grid grid-cols-5 gap-4 text-sm">
                                                <div><span class="font-semibold">日付:</span> 2026/3/18</div>
                                                <div><span class="font-semibold">物件名:</span> グランドマンションA</div>
                                                <div><span class="font-semibold">クライアント名:</span> 大手不動産管理株式会社</div>
                                                <div><span class="font-semibold">金額:</span> 350,000円</div>
                                                <div><span class="font-semibold">担当者:</span> 高橋</div>
                                            </div>
                                        </div>
                                        <div class="p-6">
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full">
                                                    <thead class="bg-green-100">
                                                        <tr>
                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">項目名</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単位</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">数量</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単価/円</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">回/年</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">金額/円</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-200">
                                                        <tr class="bg-gray-50">
                                                            <td class="px-4 py-2 text-sm font-medium">外壁全面点検調査</td>
                                                            <td class="px-4 py-2 text-center text-sm"></td>
                                                            <td class="px-4 py-2 text-center text-sm"></td>
                                                            <td class="px-4 py-2 text-center text-sm"></td>
                                                            <td class="px-4 py-2 text-center text-sm"></td>
                                                            <td class="px-4 py-2 text-center text-sm">0</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm pl-8">① 目視点検調査</td>
                                                            <td class="px-4 py-2 text-center text-sm">㎡</td>
                                                            <td class="px-4 py-2 text-center text-sm">1,200.00</td>
                                                            <td class="px-4 py-2 text-center text-sm">150</td>
                                                            <td class="px-4 py-2 text-center text-sm">1</td>
                                                            <td class="px-4 py-2 text-center text-sm text-blue-600 font-medium">180,000</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm pl-8">② 打診調査</td>
                                                            <td class="px-4 py-2 text-center text-sm">㎡</td>
                                                            <td class="px-4 py-2 text-center text-sm">400.00</td>
                                                            <td class="px-4 py-2 text-center text-sm">300</td>
                                                            <td class="px-4 py-2 text-center text-sm">1</td>
                                                            <td class="px-4 py-2 text-center text-sm text-blue-600 font-medium">120,000</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm">調査報告書作成</td>
                                                            <td class="px-4 py-2 text-center text-sm">式</td>
                                                            <td class="px-4 py-2 text-center text-sm">1.00</td>
                                                            <td class="px-4 py-2 text-center text-sm">50000</td>
                                                            <td class="px-4 py-2 text-center text-sm">1</td>
                                                            <td class="px-4 py-2 text-center text-sm text-blue-600 font-medium">50,000</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="mt-4 flex justify-between items-center">
                                                <div class="text-sm text-gray-600">東京都 | 調査面積: 1,200㎡</div>
                                                <div class="text-lg font-bold text-blue-600">合計: ¥350,000</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 見積もりカード 2 -->
                                    <div class="bg-white border border-gray-300 rounded-lg shadow-sm">
                                        <div class="bg-gray-100 px-6 py-3 border-b border-gray-200">
                                            <div class="grid grid-cols-5 gap-4 text-sm">
                                                <div><span class="font-semibold">日付:</span> 2026/3/22</div>
                                                <div><span class="font-semibold">物件名:</span> オフィスビルB</div>
                                                <div><span class="font-semibold">クライアント名:</span> XYZ管理会社</div>
                                                <div><span class="font-semibold">金額:</span> 280,000円</div>
                                                <div><span class="font-semibold">担当者:</span> 中村</div>
                                            </div>
                                        </div>
                                        <div class="p-6">
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full">
                                                    <thead class="bg-green-100">
                                                        <tr>
                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">項目名</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単位</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">数量</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単価/円</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">回/年</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">金額/円</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-200">
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm">劣化診断調査</td>
                                                            <td class="px-4 py-2 text-center text-sm">㎡</td>
                                                            <td class="px-4 py-2 text-center text-sm">800.00</td>
                                                            <td class="px-4 py-2 text-center text-sm">350</td>
                                                            <td class="px-4 py-2 text-center text-sm">1</td>
                                                            <td class="px-4 py-2 text-center text-sm text-blue-600 font-medium">280,000</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="mt-4 flex justify-between items-center">
                                                <div class="text-sm text-gray-600">神奈川県 | 調査面積: 800㎡</div>
                                                <div class="text-lg font-bold text-blue-600">合計: ¥280,000</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 外壁補修 -->
                            <div id="tab-repair" class="tab-content hidden">
                                <h3 class="text-xl font-bold text-gray-900 mb-6">外壁補修の見積もりデータ</h3>
                                
                                <div class="space-y-6">
                                    <!-- 見積もりカード 1 -->
                                    <div class="bg-white border border-gray-300 rounded-lg shadow-sm">
                                        <div class="bg-gray-100 px-6 py-3 border-b border-gray-200">
                                            <div class="grid grid-cols-5 gap-4 text-sm">
                                                <div><span class="font-semibold">日付:</span> 2026/3/19</div>
                                                <div><span class="font-semibold">物件名:</span> レジデンスC</div>
                                                <div><span class="font-semibold">クライアント名:</span> 住宅管理組合C</div>
                                                <div><span class="font-semibold">金額:</span> 120,000円</div>
                                                <div><span class="font-semibold">担当者:</span> 渡辺</div>
                                            </div>
                                        </div>
                                        <div class="p-6">
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full">
                                                    <thead class="bg-green-100">
                                                        <tr>
                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">項目名</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単位</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">数量</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単価/円</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">回/年</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">金額/円</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-200">
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm">ひび割れ補修工事</td>
                                                            <td class="px-4 py-2 text-center text-sm">m</td>
                                                            <td class="px-4 py-2 text-center text-sm">50.00</td>
                                                            <td class="px-4 py-2 text-center text-sm">2400</td>
                                                            <td class="px-4 py-2 text-center text-sm">1</td>
                                                            <td class="px-4 py-2 text-center text-sm text-blue-600 font-medium">120,000</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="mt-4 flex justify-between items-center">
                                                <div class="text-sm text-gray-600">大阪府 | 補修箇所: 50m</div>
                                                <div class="text-lg font-bold text-blue-600">合計: ¥120,000</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 見積もりカード 2 -->
                                    <div class="bg-white border border-gray-300 rounded-lg shadow-sm">
                                        <div class="bg-gray-100 px-6 py-3 border-b border-gray-200">
                                            <div class="grid grid-cols-5 gap-4 text-sm">
                                                <div><span class="font-semibold">日付:</span> 2026/3/24</div>
                                                <div><span class="font-semibold">物件名:</span> オフィスビルC</div>
                                                <div><span class="font-semibold">クライアント名:</span> 商業ビル管理株式会社</div>
                                                <div><span class="font-semibold">金額:</span> 180,000円</div>
                                                <div><span class="font-semibold">担当者:</span> 小林</div>
                                            </div>
                                        </div>
                                        <div class="p-6">
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full">
                                                    <thead class="bg-green-100">
                                                        <tr>
                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">項目名</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単位</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">数量</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単価/円</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">回/年</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">金額/円</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-200">
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm">外壁剥離部補修</td>
                                                            <td class="px-4 py-2 text-center text-sm">㎡</td>
                                                            <td class="px-4 py-2 text-center text-sm">30.00</td>
                                                            <td class="px-4 py-2 text-center text-sm">6000</td>
                                                            <td class="px-4 py-2 text-center text-sm">1</td>
                                                            <td class="px-4 py-2 text-center text-sm text-blue-600 font-medium">180,000</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="mt-4 flex justify-between items-center">
                                                <div class="text-sm text-gray-600">愛知県 | 補修面積: 30㎡</div>
                                                <div class="text-lg font-bold text-blue-600">合計: ¥180,000</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 外壁塗装 -->
                            <div id="tab-painting" class="tab-content hidden">
                                <h3 class="text-xl font-bold text-gray-900 mb-6">外壁塗装の見積もりデータ</h3>
                                
                                <div class="space-y-6">
                                    <!-- 見積もりカード 1 -->
                                    <div class="bg-white border border-gray-300 rounded-lg shadow-sm">
                                        <div class="bg-gray-100 px-6 py-3 border-b border-gray-200">
                                            <div class="grid grid-cols-5 gap-4 text-sm">
                                                <div><span class="font-semibold">日付:</span> 2026/3/26</div>
                                                <div><span class="font-semibold">物件名:</span> 戸建住宅D</div>
                                                <div><span class="font-semibold">クライアント名:</span> 佐藤様邸</div>
                                                <div><span class="font-semibold">金額:</span> 250,000円</div>
                                                <div><span class="font-semibold">担当者:</span> 田中</div>
                                            </div>
                                        </div>
                                        <div class="p-6">
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full">
                                                    <thead class="bg-green-100">
                                                        <tr>
                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">項目名</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単位</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">数量</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単価/円</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">回/年</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">金額/円</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-200">
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm">外壁部分塗装工事</td>
                                                            <td class="px-4 py-2 text-center text-sm">㎡</td>
                                                            <td class="px-4 py-2 text-center text-sm">100.00</td>
                                                            <td class="px-4 py-2 text-center text-sm">2500</td>
                                                            <td class="px-4 py-2 text-center text-sm">1</td>
                                                            <td class="px-4 py-2 text-center text-sm text-blue-600 font-medium">250,000</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="mt-4 flex justify-between items-center">
                                                <div class="text-sm text-gray-600">福岡県 | 塗装面積: 100㎡</div>
                                                <div class="text-lg font-bold text-blue-600">合計: ¥250,000</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 見積もりカード 2 -->
                                    <div class="bg-white border border-gray-300 rounded-lg shadow-sm">
                                        <div class="bg-gray-100 px-6 py-3 border-b border-gray-200">
                                            <div class="grid grid-cols-5 gap-4 text-sm">
                                                <div><span class="font-semibold">日付:</span> 2026/3/28</div>
                                                <div><span class="font-semibold">物件名:</span> レジデンシャルE</div>
                                                <div><span class="font-semibold">クライアント名:</span> マンション管理組合E</div>
                                                <div><span class="font-semibold">金額:</span> 320,000円</div>
                                                <div><span class="font-semibold">担当者:</span> 吉田</div>
                                            </div>
                                        </div>
                                        <div class="p-6">
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full">
                                                    <thead class="bg-green-100">
                                                        <tr>
                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">項目名</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単位</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">数量</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単価/円</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">回/年</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">金額/円</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-200">
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm">色合わせ塗装工事</td>
                                                            <td class="px-4 py-2 text-center text-sm">㎡</td>
                                                            <td class="px-4 py-2 text-center text-sm">150.00</td>
                                                            <td class="px-4 py-2 text-center text-sm">2000</td>
                                                            <td class="px-4 py-2 text-center text-sm">1</td>
                                                            <td class="px-4 py-2 text-center text-sm text-blue-600 font-medium">300,000</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm">調色費用</td>
                                                            <td class="px-4 py-2 text-center text-sm">式</td>
                                                            <td class="px-4 py-2 text-center text-sm">1.00</td>
                                                            <td class="px-4 py-2 text-center text-sm">20000</td>
                                                            <td class="px-4 py-2 text-center text-sm">1</td>
                                                            <td class="px-4 py-2 text-center text-sm text-blue-600 font-medium">20,000</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="mt-4 flex justify-between items-center">
                                                <div class="text-sm text-gray-600">兵庫県 | 塗装面積: 150㎡</div>
                                                <div class="text-lg font-bold text-blue-600">合計: ¥320,000</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 鳥害対策 -->
                            <div id="tab-bird_control" class="tab-content hidden">
                                <h3 class="text-xl font-bold text-gray-900 mb-6">鳥害対策の見積もりデータ</h3>
                                
                                <div class="space-y-6">
                                    <!-- 見積もりカード 1 -->
                                    <div class="bg-white border border-gray-300 rounded-lg shadow-sm">
                                        <div class="bg-gray-100 px-6 py-3 border-b border-gray-200">
                                            <div class="grid grid-cols-5 gap-4 text-sm">
                                                <div><span class="font-semibold">日付:</span> 2026/3/29</div>
                                                <div><span class="font-semibold">物件名:</span> オフィスビルF</div>
                                                <div><span class="font-semibold">クライアント名:</span> 都心不動産管理株式会社</div>
                                                <div><span class="font-semibold">金額:</span> 150,000円</div>
                                                <div><span class="font-semibold">担当者:</span> 鈴木</div>
                                            </div>
                                        </div>
                                        <div class="p-6">
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full">
                                                    <thead class="bg-green-100">
                                                        <tr>
                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">項目名</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単位</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">数量</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単価/円</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">回/年</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">金額/円</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-200">
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm">防鳥ネット設置工事</td>
                                                            <td class="px-4 py-2 text-center text-sm">㎡</td>
                                                            <td class="px-4 py-2 text-center text-sm">200.00</td>
                                                            <td class="px-4 py-2 text-center text-sm">750</td>
                                                            <td class="px-4 py-2 text-center text-sm">1</td>
                                                            <td class="px-4 py-2 text-center text-sm text-blue-600 font-medium">150,000</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="mt-4 flex justify-between items-center">
                                                <div class="text-sm text-gray-600">東京都 | 設置面積: 200㎡</div>
                                                <div class="text-lg font-bold text-blue-600">合計: ¥150,000</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 見積もりカード 2 -->
                                    <div class="bg-white border border-gray-300 rounded-lg shadow-sm">
                                        <div class="bg-gray-100 px-6 py-3 border-b border-gray-200">
                                            <div class="grid grid-cols-5 gap-4 text-sm">
                                                <div><span class="font-semibold">日付:</span> 2026/3/30</div>
                                                <div><span class="font-semibold">物件名:</span> レジデンシャルG</div>
                                                <div><span class="font-semibold">クライアント名:</span> マンション管理組合G</div>
                                                <div><span class="font-semibold">金額:</span> 80,000円</div>
                                                <div><span class="font-semibold">担当者:</span> 佐藤</div>
                                            </div>
                                        </div>
                                        <div class="p-6">
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full">
                                                    <thead class="bg-green-100">
                                                        <tr>
                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">項目名</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単位</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">数量</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単価/円</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">回/年</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">金額/円</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-200">
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm">鳥の巣除去作業</td>
                                                            <td class="px-4 py-2 text-center text-sm">箇所</td>
                                                            <td class="px-4 py-2 text-center text-sm">5.00</td>
                                                            <td class="px-4 py-2 text-center text-sm">16000</td>
                                                            <td class="px-4 py-2 text-center text-sm">1</td>
                                                            <td class="px-4 py-2 text-center text-sm text-blue-600 font-medium">80,000</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="mt-4 flex justify-between items-center">
                                                <div class="text-sm text-gray-600">千葉県 | 除去箇所: 5箇所</div>
                                                <div class="text-lg font-bold text-blue-600">合計: ¥80,000</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 看板作業 -->
                            <div id="tab-sign" class="tab-content hidden">
                                <h3 class="text-xl font-bold text-gray-900 mb-6">看板作業の見積もりデータ</h3>
                                
                                <div class="space-y-6">
                                    <!-- 見積もりカード 1 -->
                                    <div class="bg-white border border-gray-300 rounded-lg shadow-sm">
                                        <div class="bg-gray-100 px-6 py-3 border-b border-gray-200">
                                            <div class="grid grid-cols-5 gap-4 text-sm">
                                                <div><span class="font-semibold">日付:</span> 2026/4/1</div>
                                                <div><span class="font-semibold">物件名:</span> 商業ビルH</div>
                                                <div><span class="font-semibold">クライアント名:</span> 新大阪商事株式会社</div>
                                                <div><span class="font-semibold">金額:</span> 200,000円</div>
                                                <div><span class="font-semibold">担当者:</span> 中川</div>
                                            </div>
                                        </div>
                                        <div class="p-6">
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full">
                                                    <thead class="bg-green-100">
                                                        <tr>
                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">項目名</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単位</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">数量</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単価/円</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">回/年</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">金額/円</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-200">
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm">看板設置作業</td>
                                                            <td class="px-4 py-2 text-center text-sm">枚</td>
                                                            <td class="px-4 py-2 text-center text-sm">1.00</td>
                                                            <td class="px-4 py-2 text-center text-sm">150000</td>
                                                            <td class="px-4 py-2 text-center text-sm">1</td>
                                                            <td class="px-4 py-2 text-center text-sm text-blue-600 font-medium">150,000</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm">高所作業車使用料</td>
                                                            <td class="px-4 py-2 text-center text-sm">日</td>
                                                            <td class="px-4 py-2 text-center text-sm">1.00</td>
                                                            <td class="px-4 py-2 text-center text-sm">50000</td>
                                                            <td class="px-4 py-2 text-center text-sm">1</td>
                                                            <td class="px-4 py-2 text-center text-sm text-blue-600 font-medium">50,000</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="mt-4 flex justify-between items-center">
                                                <div class="text-sm text-gray-600">大阪府 | 看板サイズ: 3m×2m</div>
                                                <div class="text-lg font-bold text-blue-600">合計: ¥200,000</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 見積もりカード 2 -->
                                    <div class="bg-white border border-gray-300 rounded-lg shadow-sm">
                                        <div class="bg-gray-100 px-6 py-3 border-b border-gray-200">
                                            <div class="grid grid-cols-5 gap-4 text-sm">
                                                <div><span class="font-semibold">日付:</span> 2026/4/3</div>
                                                <div><span class="font-semibold">物件名:</span> 店舗I</div>
                                                <div><span class="font-semibold">クライアント名:</span> 横浜グリルレストラン</div>
                                                <div><span class="font-semibold">金額:</span> 80,000円</div>
                                                <div><span class="font-semibold">担当者:</span> 山崎</div>
                                            </div>
                                        </div>
                                        <div class="p-6">
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full">
                                                    <thead class="bg-green-100">
                                                        <tr>
                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">項目名</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単位</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">数量</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単価/円</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">回/年</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">金額/円</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-200">
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm">看板撤去作業</td>
                                                            <td class="px-4 py-2 text-center text-sm">枚</td>
                                                            <td class="px-4 py-2 text-center text-sm">1.00</td>
                                                            <td class="px-4 py-2 text-center text-sm">80000</td>
                                                            <td class="px-4 py-2 text-center text-sm">1</td>
                                                            <td class="px-4 py-2 text-center text-sm text-blue-600 font-medium">80,000</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="mt-4 flex justify-between items-center">
                                                <div class="text-sm text-gray-600">神奈川県 | 看板サイズ: 2m×1.5m</div>
                                                <div class="text-lg font-bold text-blue-600">合計: ¥80,000</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 雨漏り調査 -->
                            <div id="tab-leak" class="tab-content hidden">
                                <h3 class="text-xl font-bold text-gray-900 mb-6">雨漏り調査の見積もりデータ</h3>
                                
                                <div class="space-y-6">
                                    <!-- 見積もりカード 1 -->
                                    <div class="bg-white border border-gray-300 rounded-lg shadow-sm">
                                        <div class="bg-gray-100 px-6 py-3 border-b border-gray-200">
                                            <div class="grid grid-cols-5 gap-4 text-sm">
                                                <div><span class="font-semibold">日付:</span> 2026/4/5</div>
                                                <div><span class="font-semibold">物件名:</span> マンションJ</div>
                                                <div><span class="font-semibold">クライアント名:</span> 住宅管理組合J</div>
                                                <div><span class="font-semibold">金額:</span> 120,000円</div>
                                                <div><span class="font-semibold">担当者:</span> 立花</div>
                                            </div>
                                        </div>
                                        <div class="p-6">
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full">
                                                    <thead class="bg-green-100">
                                                        <tr>
                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">項目名</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単位</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">数量</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単価/円</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">回/年</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">金額/円</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-200">
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm">雨漏り散水試験</td>
                                                            <td class="px-4 py-2 text-center text-sm">箇所</td>
                                                            <td class="px-4 py-2 text-center text-sm">3.00</td>
                                                            <td class="px-4 py-2 text-center text-sm">40000</td>
                                                            <td class="px-4 py-2 text-center text-sm">1</td>
                                                            <td class="px-4 py-2 text-center text-sm text-blue-600 font-medium">120,000</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="mt-4 flex justify-between items-center">
                                                <div class="text-sm text-gray-600">埼玉県 | 調査箇所: 3箇所</div>
                                                <div class="text-lg font-bold text-blue-600">合計: ¥120,000</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 見積もりカード 2 -->
                                    <div class="bg-white border border-gray-300 rounded-lg shadow-sm">
                                        <div class="bg-gray-100 px-6 py-3 border-b border-gray-200">
                                            <div class="grid grid-cols-5 gap-4 text-sm">
                                                <div><span class="font-semibold">日付:</span> 2026/4/7</div>
                                                <div><span class="font-semibold">物件名:</span> 戸建住宅K</div>
                                                <div><span class="font-semibold">クライアント名:</span> 田中様邸</div>
                                                <div><span class="font-semibold">金額:</span> 60,000円</div>
                                                <div><span class="font-semibold">担当者:</span> 加藤</div>
                                            </div>
                                        </div>
                                        <div class="p-6">
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full">
                                                    <thead class="bg-green-100">
                                                        <tr>
                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">項目名</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単位</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">数量</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単価/円</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">回/年</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">金額/円</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-200">
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm">雨漏り原因調査</td>
                                                            <td class="px-4 py-2 text-center text-sm">箇所</td>
                                                            <td class="px-4 py-2 text-center text-sm">1.00</td>
                                                            <td class="px-4 py-2 text-center text-sm">60000</td>
                                                            <td class="px-4 py-2 text-center text-sm">1</td>
                                                            <td class="px-4 py-2 text-center text-sm text-blue-600 font-medium">60,000</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="mt-4 flex justify-between items-center">
                                                <div class="text-sm text-gray-600">愛知県 | 調査箇所: 1箇所</div>
                                                <div class="text-lg font-bold text-blue-600">合計: ¥60,000</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- その他 -->
                            <div id="tab-other" class="tab-content hidden">
                                <h3 class="text-xl font-bold text-gray-900 mb-6">その他の見積もりデータ</h3>
                                
                                <div class="space-y-6">
                                    <!-- 見積もりカード 1 -->
                                    <div class="bg-white border border-gray-300 rounded-lg shadow-sm">
                                        <div class="bg-gray-100 px-6 py-3 border-b border-gray-200">
                                            <div class="grid grid-cols-5 gap-4 text-sm">
                                                <div><span class="font-semibold">日付:</span> 2026/4/8</div>
                                                <div><span class="font-semibold">物件名:</span> 工場L</div>
                                                <div><span class="font-semibold">クライアント名:</span> 製造業株式会社L</div>
                                                <div><span class="font-semibold">金額:</span> 400,000円</div>
                                                <div><span class="font-semibold">担当者:</span> 伊藤</div>
                                            </div>
                                        </div>
                                        <div class="p-6">
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full">
                                                    <thead class="bg-green-100">
                                                        <tr>
                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">項目名</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単位</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">数量</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単価/円</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">回/年</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">金額/円</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-200">
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm">特殊清掃作業</td>
                                                            <td class="px-4 py-2 text-center text-sm">㎡</td>
                                                            <td class="px-4 py-2 text-center text-sm">500.00</td>
                                                            <td class="px-4 py-2 text-center text-sm">700</td>
                                                            <td class="px-4 py-2 text-center text-sm">1</td>
                                                            <td class="px-4 py-2 text-center text-sm text-blue-600 font-medium">350,000</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm">特殊器具使用料</td>
                                                            <td class="px-4 py-2 text-center text-sm">式</td>
                                                            <td class="px-4 py-2 text-center text-sm">1.00</td>
                                                            <td class="px-4 py-2 text-center text-sm">50000</td>
                                                            <td class="px-4 py-2 text-center text-sm">1</td>
                                                            <td class="px-4 py-2 text-center text-sm text-blue-600 font-medium">50,000</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="mt-4 flex justify-between items-center">
                                                <div class="text-sm text-gray-600">茨城県 | 清掃面積: 500㎡</div>
                                                <div class="text-lg font-bold text-blue-600">合計: ¥400,000</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 見積もりカード 2 -->
                                    <div class="bg-white border border-gray-300 rounded-lg shadow-sm">
                                        <div class="bg-gray-100 px-6 py-3 border-b border-gray-200">
                                            <div class="grid grid-cols-5 gap-4 text-sm">
                                                <div><span class="font-semibold">日付:</span> 2026/4/10</div>
                                                <div><span class="font-semibold">物件名:</span> 倉庫M</div>
                                                <div><span class="font-semibold">クライアント名:</span> 物流センター株式会社</div>
                                                <div><span class="font-semibold">金額:</span> 150,000円</div>
                                                <div><span class="font-semibold">担当者:</span> 松本</div>
                                            </div>
                                        </div>
                                        <div class="p-6">
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full">
                                                    <thead class="bg-green-100">
                                                        <tr>
                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-700">項目名</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単位</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">数量</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">単価/円</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">回/年</th>
                                                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-700">金額/円</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-200">
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm">緊急対応作業</td>
                                                            <td class="px-4 py-2 text-center text-sm">式</td>
                                                            <td class="px-4 py-2 text-center text-sm">1.00</td>
                                                            <td class="px-4 py-2 text-center text-sm">120000</td>
                                                            <td class="px-4 py-2 text-center text-sm">1</td>
                                                            <td class="px-4 py-2 text-center text-sm text-blue-600 font-medium">120,000</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm">夜間作業加算</td>
                                                            <td class="px-4 py-2 text-center text-sm">式</td>
                                                            <td class="px-4 py-2 text-center text-sm">1.00</td>
                                                            <td class="px-4 py-2 text-center text-sm">30000</td>
                                                            <td class="px-4 py-2 text-center text-sm">1</td>
                                                            <td class="px-4 py-2 text-center text-sm text-blue-600 font-medium">30,000</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="mt-4 flex justify-between items-center">
                                                <div class="text-sm text-gray-600">栃木県 | 対応内容: 緊急対応</div>
                                                <div class="text-lg font-bold text-blue-600">合計: ¥150,000</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- 右：サイドバー -->
                <aside class="mt-10 lg:mt-0 space-y-4">
                    <!-- お問い合わせフォーム -->
                    <div class="bg-gray-500 rounded-lg shadow text-white">
                        <div class="p-6">
                            <h4 class="text-lg font-bold mb-2 text-center">お急ぎの方へ</h4>
                            <p class="text-sm mb-4 text-center">最短で業者をお探しします</p>
                            
                            <form action="{{ route('quote.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <input type="text" 
                                           name="name" 
                                           placeholder="お名前" 
                                           required
                                           class="w-full px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent">
                                </div>
                                
                                <div>
                                    <input type="tel" 
                                           name="phone" 
                                           placeholder="電話番号" 
                                           required
                                           class="w-full px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent">
                                </div>
                                
                                <div>
                                    <select name="service_type" 
                                            required
                                            class="w-full px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent">
                                        <option value="">サービスを選択</option>
                                        <option value="window_cleaning">窓ガラス清掃</option>
                                        <option value="building_cleaning">ビル清掃</option>
                                        <option value="wall_painting">外壁塗装</option>
                                        <option value="roof_repair">屋根修理</option>
                                        <option value="sign_installation">看板設置</option>
                                        <option value="other">その他</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <textarea name="message" 
                                              placeholder="ご要望・詳細（任意）" 
                                              rows="3"
                                              class="w-full px-3 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent resize-none"></textarea>
                                </div>
                                
                                <button type="submit" 
                                        class="glowing-button w-full bg-orange-600 text-white px-4 py-3 rounded-md font-bold hover:bg-orange-700 transition-colors">
                                    無料で相談する
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- おすすめ記事 -->
                    <div class="bg-white rounded-xl shadow p-6">
                        <h2 class="text-xl font-bold mb-6 text-blue-600">
                            おすすめ記事
                        </h2>

                        <div class="space-y-4">
                            @forelse($featuredArticles as $item)
                                <a href="{{ $item['url'] ?? '#' }}"
                                   class="flex gap-4 hover:opacity-80 transition-opacity">

                                    <div class="w-20 h-16 bg-blue-100 rounded flex items-center justify-center flex-shrink-0 overflow-hidden">
                                        @if(!empty($item['featured_image_url']))
                                            <img src="{{ $item['featured_image_url'] }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                            </svg>
                                        @endif
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-semibold text-gray-900 leading-tight line-clamp-2">
                                            {{ $item['title'] ?? '記事タイトル' }}
                                        </h3>
                                    </div>

                                </a>
                            @empty
                                <div class="text-center text-gray-500 text-sm py-8">
                                    おすすめ記事がありません
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- サイドバー広告1 -->
                    @php
                        $siteSettings = app(\App\Models\SiteSetting::class)->getSettings();
                    @endphp
                    
                    @if(!empty($siteSettings['sidebar_ad_1']))
                    <div class="bg-white rounded-lg shadow border border-gray-200">
                        <div class="p-4">
                            <div class="text-xs text-gray-400 mb-2 text-center">広告</div>
                            <div class="ad-container">
                                {!! $siteSettings['sidebar_ad_1'] !!}
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- サイドバー広告2 -->
                    @if(!empty($siteSettings['sidebar_ad_2']))
                    <div class="bg-white rounded-lg shadow border border-gray-200">
                        <div class="p-4">
                            <div class="text-xs text-gray-400 mb-2 text-center">広告</div>
                            <div class="ad-container">
                                {!! $siteSettings['sidebar_ad_2'] !!}
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- サイドバー広告3 -->
                    @if(!empty($siteSettings['sidebar_ad_3']))
                    <div class="bg-white rounded-lg shadow border border-gray-200">
                        <div class="p-4">
                            <div class="text-xs text-gray-400 mb-2 text-center">広告</div>
                            <div class="ad-container">
                                {!! $siteSettings['sidebar_ad_3'] !!}
                            </div>
                        </div>
                    </div>
                    @endif
                </aside>
            </div>
        </div>
    </div>
</div>

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

/* 記事タイトルの行制限 */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.service-tab');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const service = this.getAttribute('data-service');
            
            // タブのアクティブ状態を変更
            tabs.forEach(t => {
                t.classList.remove('border-blue-500', 'text-blue-600', 'bg-blue-50');
                t.classList.add('border-transparent', 'text-gray-500');
            });
            
            this.classList.remove('border-transparent', 'text-gray-500');
            this.classList.add('border-blue-500', 'text-blue-600', 'bg-blue-50');
            
            // コンテンツの表示を変更
            contents.forEach(content => {
                content.classList.add('hidden');
            });
            
            const targetContent = document.getElementById(`tab-${service}`);
            if (targetContent) {
                targetContent.classList.remove('hidden');
            }
        });
    });
});
</script>
@endsection