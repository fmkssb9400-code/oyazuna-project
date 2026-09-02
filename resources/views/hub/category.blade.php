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

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- 業者一覧 -->
        <div class="mb-10">
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

        <!-- FAQ -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-lg p-6 md:p-8 mb-10">
            <h2 class="text-xl font-bold text-gray-900 mb-6">よくある質問</h2>
            <div class="space-y-5">
                @foreach($config['faq'] as $item)
                    <div class="border-b border-gray-100 pb-5 last:border-b-0 last:pb-0">
                        <p class="font-semibold text-gray-900 mb-2">Q. {{ $item['q'] }}</p>
                        <p class="text-gray-700 text-sm leading-relaxed">A. {{ $item['a'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 関連ページ -->
        <div class="mb-4">
            <h2 class="text-lg font-bold text-gray-900 mb-4">関連ページ</h2>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('companies.index') }}" class="px-4 py-2 bg-white border border-gray-200 rounded-full text-sm text-gray-700 hover:border-blue-400 hover:text-blue-700 transition-colors">
                    専門業者一覧（全カテゴリ）
                </a>
                @foreach($otherHubs as $otherSlug => $otherConfig)
                    <a href="{{ route('hub.category', $otherSlug) }}" class="px-4 py-2 bg-white border border-gray-200 rounded-full text-sm text-gray-700 hover:border-blue-400 hover:text-blue-700 transition-colors">
                        {{ $otherConfig['label'] }}対応業者一覧
                    </a>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection
