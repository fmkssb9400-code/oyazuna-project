@extends('layouts.app')

@section('title', $config['h1'] . ' | オヤズナ')
@section('description', $config['meta_description'])

@section('content')
<div class="min-h-screen bg-blue-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex flex-wrap items-center gap-x-2 text-sm text-gray-600">
                <li><a href="{{ route('home') }}" class="hover:text-blue-600">ホーム</a></li>
                <li><span class="mx-1">/</span></li>
                <li><a href="{{ route('companies.index') }}" class="hover:text-blue-600">専門業者一覧</a></li>
                <li><span class="mx-1">/</span></li>
                <li class="text-gray-900">{{ $config['label'] }}</li>
            </ol>
        </nav>

        <!-- Hero -->
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8 leading-tight">{{ $config['h1'] }}</h1>

    </div>

    <!-- 比較表（他セクションより横幅1.25倍: max-w-5xl(64rem) → max-w-7xl(80rem)） -->
    @if($topCompanies->isNotEmpty())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 shadow-lg p-6 md:p-8">
                <div class="space-y-4 mb-6">
                    @foreach((array) $config['lead'] as $paragraph)
                        <p class="text-gray-700 leading-relaxed">{{ $paragraph }}</p>
                    @endforeach
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
                                @endphp
                                <tr class="{{ $index % 2 === 1 ? 'bg-gray-50' : '' }}">
                                    <td class="px-4 py-3 font-medium whitespace-nowrap border border-gray-300">
                                        <a href="{{ $company->official_url }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-800 hover:underline">{{ $company->name }}</a>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 whitespace-nowrap border border-gray-300">{{ $areasText }}</td>
                                    <td class="px-4 py-3 text-gray-600 whitespace-nowrap border border-gray-300">
                                        {{ $company->condition_highlights ? implode('・', $company->condition_highlights) : '-' }}
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

    <!-- 業者一覧（比較表と同じ横幅: max-w-7xl） -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mt-10 mb-10">
            <div class="lg:grid lg:grid-cols-3 lg:gap-8 lg:items-start">
                <div class="lg:col-span-2">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">{{ $config['label'] }}対応業者一覧</h2>
                    @if($companies->isEmpty())
                        <div class="bg-white rounded-2xl border border-gray-200 p-8 text-center text-gray-600">
                            現在この条件に合う業者情報を準備中です。<a href="{{ route('companies.index') }}" class="text-blue-600 underline">業者一覧</a>から他の条件もご確認ください。
                        </div>
                    @else
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
                    @endif
                </div>

                <!-- 関連ページ（サイドバー、カード一覧と高さを揃える） -->
                <aside class="mt-10 lg:mt-0">
                    <h2 class="text-xl font-bold mb-4 invisible hidden lg:block" aria-hidden="true">&nbsp;</h2>
                    <div class="border border-gray-200">
                        <div class="bg-blue-500 px-4 py-3">
                            <h3 class="text-white font-bold">関連ページ</h3>
                        </div>
                        <div class="bg-white divide-y divide-dashed divide-gray-300">
                            <a href="{{ route('companies.index') }}" class="block px-4 py-4 text-blue-600 underline hover:text-blue-800">
                                専門業者一覧（全カテゴリ）
                            </a>
                            @foreach($otherHubs as $otherSlug => $otherConfig)
                                <a href="{{ route('hub.category', $otherSlug) }}" class="block px-4 py-4 text-blue-600 underline hover:text-blue-800">
                                    {{ $otherConfig['label'] }}対応業者一覧
                                </a>
                            @endforeach
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- FAQ -->
        <div class="bg-white p-6 md:p-8 mb-10">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">よくある質問</h2>
            <div class="h-1 bg-green-500 mb-8"></div>
            <div class="space-y-10">
                @foreach($config['faq'] as $item)
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 border-l-4 border-green-500 pl-3 mb-3">{{ $item['q'] }}</h3>
                        <p class="text-base text-gray-700 leading-relaxed">{{ $item['a'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>


    </div>
</div>

<!-- PC版：画面下部固定の現調依頼CTA（黒帯の中にオレンジボタン。スマホは共通レイアウト側のCTAを表示） -->
<div class="hidden md:flex fixed bottom-0 inset-x-0 z-50 justify-center bg-black/50 px-3 py-4">
    <a href="{{ route('quote.create') }}"
       class="inline-flex items-center justify-center gap-3 bg-orange-500 hover:bg-orange-600 transition-colors text-white font-bold text-xl px-12 py-4 shadow-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" />
            <path d="M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
        </svg>
        かんたん入力で現調依頼
    </a>
</div>
@endsection
