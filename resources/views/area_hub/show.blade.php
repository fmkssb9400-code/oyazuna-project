@extends('layouts.app')

@php
    $customContentView = 'area_hub.custom.' . $areaSlug . '_' . str_replace('-', '_', $hubSlug);
    $comboKey = $areaSlug . '_' . $hubSlug;

    // area_hub/custom配下に独自コンテンツを追加したページは、その軸を反映したtitle/descriptionを個別指定する。
    // 指定がない組み合わせは従来通りの自動生成文言にフォールバックする。
    $customMeta = [
        'tokyo_exterior-cleaning' => [
            'title' => '東京都の外壁清掃、足場なしで依頼できる業者一覧｜見積り無料',
            'description' => '東京都で足場を組まずに外壁清掃できるロープアクセス・ゴンドラ対応の専門業者を69社掲載。費用相場・業者選びのポイントも解説。無料で見積もり依頼できます。',
        ],
        'tokyo_wall-repair' => [
            'title' => '東京都の外壁補修業者一覧｜補助金の実態・費用相場も解説',
            'description' => '東京都で外壁のひび割れ・タイル浮きを補修する専門業者を28社掲載。区市町村ごとに異なる補助金の実態や工法別の費用相場も解説。無料で見積もり依頼できます。',
        ],
        'tokyo_exterior-inspection' => [
            'title' => '東京都の外壁調査業者一覧｜ドローン・赤外線調査の費用も解説',
            'description' => '東京都で外壁調査(打診・赤外線・ドローン)に対応する専門業者を27社掲載。国に認められた調査手法や費用相場も解説。無料で見積もり依頼できます。',
        ],
        'tokyo_exterior-painting' => [
            'title' => '東京都の外壁塗装業者一覧｜補助金の実態・悪質業者の見分け方',
            'description' => '東京都で外壁塗装に対応する専門業者を17社掲載。区市町村ごとに異なる補助金制度の実態や、悪質な訪問販売業者の見分け方も解説。無料で見積もり依頼できます。',
        ],
        'tokyo_signboard' => [
            'title' => '東京都の看板業者一覧｜屋外広告物条例の許可基準も解説',
            'description' => '東京都で看板の設置・作業に対応する専門業者を14社掲載。屋外広告物条例に基づく許可基準・禁止区域・手数料も解説。無料で見積もり依頼できます。',
        ],
        'tokyo_bird-control' => [
            'title' => '東京都の鳥害対策業者一覧｜区ごとの餌やり禁止条例も解説',
            'description' => '東京都で鳩・カラス対策に対応する専門業者を14社掲載。区市によって異なる餌やり禁止条例の実態も解説。無料で見積もり依頼できます。',
        ],
        'kanagawa_exterior-cleaning' => [
            'title' => '神奈川県の外壁清掃、足場なしで依頼できる業者一覧｜見積り無料',
            'description' => '神奈川県で足場を組まずに外壁清掃できる専門業者を39社掲載。みなとみらい・湘南エリアの事情も解説。無料で見積もり依頼できます。',
        ],
        'saitama_exterior-cleaning' => [
            'title' => '埼玉県の外壁清掃、足場なしで依頼できる業者一覧｜見積り無料',
            'description' => '埼玉県で足場を組まずに外壁清掃できる専門業者を33社掲載。夏の猛暑を踏まえた業者選びのポイントも解説。無料で見積もり依頼できます。',
        ],
        'chiba_exterior-cleaning' => [
            'title' => '千葉県の外壁清掃、足場なしで依頼できる業者一覧｜見積り無料',
            'description' => '千葉県で足場を組まずに外壁清掃できる専門業者を32社掲載。沿岸部の塩害・京葉工業地域の事情も解説。無料で見積もり依頼できます。',
        ],
        'ibaraki_exterior-cleaning' => [
            'title' => '茨城県の外壁清掃、足場なしで依頼できる業者一覧｜見積り無料',
            'description' => '茨城県で足場を組まずに外壁清掃できる専門業者を10社掲載。太平洋沿岸・鹿島臨海工業地帯の事情も解説。無料で見積もり依頼できます。',
        ],
        'chiba_window-cleaning' => [
            'title' => '千葉県の高所窓ガラス清掃業者一覧｜幕張・湾岸エリア対応で見積り無料',
            'description' => '千葉県で高所窓ガラス清掃に対応する専門業者を35社掲載。幕張新都心などタワーマンション・オフィスビル集積地の事情も解説。無料で見積もり依頼できます。',
        ],
        'kanagawa_window-cleaning' => [
            'title' => '神奈川県の高所窓ガラス清掃業者一覧｜横浜・川崎対応で見積り無料',
            'description' => '神奈川県で高所窓ガラス清掃に対応する専門業者を44社掲載。横浜・川崎・相模原の3政令指定都市の事情も解説。無料で見積もり依頼できます。',
        ],
        'saitama_window-cleaning' => [
            'title' => '埼玉県の高所窓ガラス清掃業者一覧｜内陸県の猛暑対応で見積り無料',
            'description' => '埼玉県で高所窓ガラス清掃に対応する専門業者を36社掲載。海のない内陸県ならではの夏の暑さ対策も解説。無料で見積もり依頼できます。',
        ],
        'ibaraki_window-cleaning' => [
            'title' => '茨城県の高所窓ガラス清掃業者一覧｜つくば・水戸対応で見積り無料',
            'description' => '茨城県で高所窓ガラス清掃に対応する専門業者を11社掲載。2025年国勢調査でつくば市が県内最大都市になった事情も解説。無料で見積もり依頼できます。',
        ],
        'osaka_window-cleaning' => [
            'title' => '大阪府の高所窓ガラス清掃業者一覧｜梅田・中之島エリア対応で見積り無料',
            'description' => '大阪府で高所窓ガラス清掃に対応する専門業者を24社掲載。都心回帰が進む大阪市中心部の高層ビル集積や堺泉北臨海工業地帯の事情も解説。無料で見積もり依頼できます。',
        ],
        'osaka_exterior-cleaning' => [
            'title' => '大阪府の外壁清掃、足場なしで依頼できる業者一覧｜見積り無料',
            'description' => '大阪府で足場を組まずに外壁清掃できる専門業者を15社掲載。あべのハルカス等の超高層ビル密集地や堺泉北臨海工業地帯の事情も解説。無料で見積もり依頼できます。',
        ],
    ];

    $pageTitle = $customMeta[$comboKey]['title']
        ?? ($areaConfig['prefecture'] . 'の' . ($hubConfig['nav_label'] ?? $hubConfig['label']) . '｜高所ロープ作業・見積り無料');
    $pageDescription = $customMeta[$comboKey]['description']
        ?? ($areaConfig['prefecture'] . 'で' . $hubConfig['label'] . 'に対応する高所ロープ作業の専門業者を' . $count . '社掲載。無料で見積もり依頼できます。');
@endphp

@section('title', $pageTitle . ' | オヤズナ')
@section('description', $pageDescription)

@section('head')
    @if(!empty($hubConfig['faq']))
        <script type="application/ld+json">
            {!! json_encode([
                '@@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => collect($hubConfig['faq'])->map(fn ($item) => [
                    '@type' => 'Question',
                    'name' => $item['q'],
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $item['a'],
                    ],
                ])->values()->all(),
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
        </script>
    @endif
    <style>
        .hub-custom-content h2 { font-size: 1.5rem; font-weight: 700; color: #111827; margin: 2rem 0 0.75rem; }
        .hub-custom-content h2:first-child { margin-top: 0; }
        .hub-custom-content h3 { font-size: 1.125rem; font-weight: 700; color: #111827; margin: 1.5rem 0 0.5rem; border-left: 4px solid #22c55e; padding-left: 0.75rem; }
        .hub-custom-content p { color: #374151; line-height: 1.75; margin-bottom: 1rem; }
        .hub-custom-content ul { list-style: disc; padding-left: 1.5rem; margin-bottom: 1rem; color: #374151; }
        .hub-custom-content li { margin-bottom: 0.5rem; }
        .hub-custom-content strong { font-weight: 700; }
        .hub-custom-content p a:not([class*="bg-"]) { color: #2563eb; text-decoration: underline; }
        .hub-custom-content table { width: 100%; border-collapse: collapse; margin-bottom: 1rem; font-size: 0.9rem; }
        .hub-custom-content table th, .hub-custom-content table td { border: 1px solid #d1d5db; padding: 0.5rem 0.75rem; text-align: left; }
        .hub-custom-content table th { background: #eff6ff; }
        @media (max-width: 640px) {
            .hub-custom-content table { display: block; overflow-x: auto; white-space: nowrap; }
        }
    </style>
@endsection

@section('content')
<div class="min-h-screen bg-blue-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex flex-wrap items-center gap-x-2 text-sm text-gray-600">
                <li><a href="{{ route('home') }}" class="hover:text-blue-600">ホーム</a></li>
                <li><span class="mx-1">/</span></li>
                <li><a href="{{ route('area.show', $areaSlug) }}" class="hover:text-blue-600">{{ $areaConfig['label'] }}</a></li>
                <li><span class="mx-1">/</span></li>
                <li class="text-gray-900">{{ $hubConfig['nav_label'] ?? $hubConfig['label'] }}</li>
            </ol>
        </nav>

        <!-- Hero -->
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8 leading-tight">{{ $areaConfig['prefecture'] }}の{{ $hubConfig['nav_label'] ?? $hubConfig['label'] }}を比較｜見積り無料</h1>

    </div>

    <!-- 比較表 -->
    @if($topCompanies->isNotEmpty())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 shadow-lg p-6 md:p-8">
                <div class="space-y-4 mb-6">
                    <p class="text-gray-700 leading-relaxed">{{ $areaConfig['lead'][0] ?? '' }}</p>
                    <p class="text-gray-700 leading-relaxed">{{ $insightParagraph }}</p>
                    @if($averageRating)
                        <p class="text-gray-700 leading-relaxed">口コミ平均評価は{{ $averageRating }}です。</p>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mb-2 sm:hidden">→ 横にスクロールできます</p>
                <div class="overflow-x-auto -mx-6 md:-mx-8 px-6 md:px-8">
                    <table class="w-full text-sm text-left border-collapse border border-gray-300 min-w-[640px]">
                        <thead>
                            <tr class="bg-blue-50 text-gray-700">
                                <th class="px-4 py-3 font-semibold whitespace-nowrap border border-gray-300">会社名</th>
                                <th class="px-4 py-3 font-semibold whitespace-nowrap border border-gray-300">対応エリア</th>
                                <th class="px-4 py-3 font-semibold whitespace-nowrap border border-gray-300">対応可能ケース</th>
                                <th class="px-4 py-3 font-semibold whitespace-nowrap border border-gray-300">対応工法</th>
                                <th class="px-4 py-3 font-semibold whitespace-nowrap border border-gray-300"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topCompanies as $index => $company)
                                @php
                                    $areasList = is_array($company->areas) ? $company->areas : [];
                                    $areasText = count($areasList) > 2
                                        ? $areasList[0].'・'.$areasList[1].' 他'.(count($areasList) - 2).'県'
                                        : (implode('・', $areasList) ?: '全国');

                                    $methods = array_filter([
                                        $company->rope_support ? 'ロープアクセス' : null,
                                        $company->gondola_supported ? 'ゴンドラ' : null,
                                        $company->branco_supported ? 'ブランコ' : null,
                                        $company->aerial_platform_supported ? '高所作業車' : null,
                                    ]);

                                    $highlightTags = $company->matchingConditionTags();
                                @endphp
                                <tr class="{{ $index % 2 === 1 ? 'bg-gray-50' : '' }}">
                                    <td class="px-4 py-3 font-medium whitespace-nowrap border border-gray-300">
                                        <a href="{{ $company->official_url }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-800 hover:underline">{{ $company->name }}</a>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 whitespace-nowrap border border-gray-300">{{ $areasText }}</td>
                                    <td class="px-4 py-3 text-gray-600 whitespace-nowrap border border-gray-300">
                                        {{ $highlightTags ? implode('・', $highlightTags) : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 whitespace-nowrap border border-gray-300">{{ $methods ? implode('・', $methods) : '-' }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap border border-gray-300">
                                        <a href="{{ route('companies.show', $company->slug) }}" class="text-blue-600 text-xs font-semibold hover:underline">詳細を見る &rsaquo;</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- 業者一覧 -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mt-10 mb-10">
            <div class="lg:grid lg:grid-cols-3 lg:gap-8 lg:items-start">
                <div class="lg:col-span-2">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">{{ $areaConfig['prefecture'] }}の{{ $hubConfig['label'] }}業者一覧</h2>
                    <div class="space-y-6">
                        @foreach($companies as $company)
                            <x-company-card :company="$company" />
                        @endforeach
                    </div>
                    @if($companies->hasPages())
                        <div class="mt-10 flex justify-center">
                            {{ $companies->links('hub.pagination') }}
                        </div>
                    @endif
                </div>

                <!-- 関連ページ -->
                <aside class="mt-10 lg:mt-0">
                    <h2 class="text-xl font-bold mb-4 invisible hidden lg:block" aria-hidden="true">&nbsp;</h2>
                    <div class="border border-gray-200">
                        <div class="bg-blue-500 px-4 py-3">
                            <h3 class="text-white font-bold">関連ページ</h3>
                        </div>
                        <div class="bg-white divide-y divide-dashed divide-gray-300">
                            <a href="{{ route('area.show', $areaSlug) }}" class="block px-4 py-4 text-blue-600 underline hover:text-blue-800">
                                {{ $areaConfig['label'] }}の業者一覧（全カテゴリ）
                            </a>
                            <a href="{{ route('hub.category', $hubSlug) }}" class="block px-4 py-4 text-blue-600 underline hover:text-blue-800">
                                {{ $hubConfig['nav_label'] ?? $hubConfig['label'] }}（全国）
                            </a>
                        </div>
                        @if($siblingsByArea->isNotEmpty())
                            <div class="bg-gray-50 px-4 py-2 text-sm font-semibold text-gray-600">{{ $areaConfig['label'] }}の他の工法</div>
                            <div class="bg-white divide-y divide-dashed divide-gray-300">
                                @foreach($siblingsByArea as $sibling)
                                    <a href="{{ route('area.hub.show', [$sibling['areaSlug'], $sibling['hubSlug']]) }}" class="block px-4 py-4 text-blue-600 underline hover:text-blue-800">
                                        {{ $sibling['hubConfig']['nav_label'] ?? $sibling['hubConfig']['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                        @if($siblingsByHub->isNotEmpty())
                            <div class="bg-gray-50 px-4 py-2 text-sm font-semibold text-gray-600">他の都道府県の{{ $hubConfig['label'] }}</div>
                            <div class="bg-white divide-y divide-dashed divide-gray-300">
                                @foreach($siblingsByHub as $sibling)
                                    <a href="{{ route('area.hub.show', [$sibling['areaSlug'], $sibling['hubSlug']]) }}" class="block px-4 py-4 text-blue-600 underline hover:text-blue-800">
                                        {{ $sibling['areaConfig']['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </aside>
            </div>
        </div>
    </div>

    @if(!empty($sections))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-3 lg:gap-8">
                <div class="lg:col-span-2">
                    <div class="bg-white p-6 md:p-8 mb-10">
                        @foreach($sections as $section)
                            <div class="{{ $loop->first ? '' : 'mt-12' }}">
                                <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ $section['heading'] }}</h2>
                                <div class="h-1 bg-green-500 mb-6"></div>
                                @foreach($section['body'] ?? [] as $p)
                                    <p class="text-base text-gray-700 leading-relaxed mb-4">{{ $p }}</p>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(View::exists($customContentView))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-3 lg:gap-8">
                <div class="lg:col-span-2">
                    <div class="bg-white p-6 md:p-8 mb-10 hub-custom-content">
                        @include($customContentView)
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(!empty($hubConfig['faq']))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-3 lg:gap-8">
                <div class="lg:col-span-2">
                    <div class="bg-white p-6 md:p-8 mb-10">
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">よくある質問</h2>
                        <div class="h-1 bg-green-500 mb-8"></div>
                        <div class="space-y-10">
                            @foreach($hubConfig['faq'] as $item)
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 border-l-4 border-green-500 pl-3 mb-3">{{ $item['q'] }}</h3>
                                    <p class="text-base text-gray-700 leading-relaxed">{{ $item['a'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- PC版：画面下部固定の現調依頼CTA -->
<div class="hidden md:flex fixed bottom-0 inset-x-0 z-50 justify-center bg-black/50 px-3 py-4">
    <a href="{{ route('quote.create') }}"
       class="inline-flex items-center justify-center gap-3 bg-blue-600 hover:bg-blue-700 transition-colors text-white font-bold text-xl px-12 py-4 shadow-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" />
            <path d="M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
        </svg>
        かんたん入力で現調依頼
    </a>
</div>
@endsection
