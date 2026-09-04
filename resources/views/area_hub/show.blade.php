@extends('layouts.app')

@php
    $pageTitle = $areaConfig['prefecture'] . 'の' . ($hubConfig['nav_label'] ?? $hubConfig['label']) . '｜高所ロープ作業・見積り無料';
    $pageDescription = $areaConfig['prefecture'] . 'で' . $hubConfig['label'] . 'に対応する高所ロープ作業の専門業者を' . $count . '社掲載。無料で見積もり依頼できます。';
@endphp

@section('title', $pageTitle . ' | オヤズナ')
@section('description', $pageDescription)

@section('head')
    <link rel="canonical" href="{{ url()->full() }}">
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
