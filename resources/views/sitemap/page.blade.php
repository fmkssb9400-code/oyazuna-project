@extends('layouts.app')

@section('title', 'サイトマップ | オヤズナ')
@section('description', 'オヤズナのサイト内ページを一覧できるサイトマップです。都道府県別ページ、サービス別ページ、都道府県×サービスの組み合わせページなど、目的のページをすぐに見つけられます。')

@section('content')
<div class="min-h-screen bg-blue-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex flex-wrap items-center gap-x-2 text-sm text-gray-600">
                <li><a href="{{ route('home') }}" class="hover:text-blue-600">ホーム</a></li>
                <li><span class="mx-1">/</span></li>
                <li class="text-gray-900">サイトマップ</li>
            </ol>
        </nav>

        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8 leading-tight">サイトマップ</h1>

        <div class="space-y-10">

            <!-- 基本ページ -->
            <section>
                <h2 class="text-lg font-bold text-gray-900 mb-3 pb-2 border-b-2 border-blue-500">基本ページ</h2>
                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-1">
                    <li><a href="{{ route('home') }}" class="text-blue-600 hover:underline">ホーム</a></li>
                    <li><a href="{{ route('companies.index') }}" class="text-blue-600 hover:underline">専門業者一覧（全カテゴリ）</a></li>
                    <li><a href="{{ route('news.index') }}" class="text-blue-600 hover:underline">ニュース・記事</a></li>
                    <li><a href="{{ route('reviews.index') }}" class="text-blue-600 hover:underline">口コミを書く</a></li>
                    <li><a href="{{ route('quote-data.create') }}" class="text-blue-600 hover:underline">見積もりデータを登録</a></li>
                    <li><a href="{{ route('quote.create') }}" class="text-blue-600 hover:underline">お見積もり相談</a></li>
                    <li><a href="{{ route('contact.create') }}" class="text-blue-600 hover:underline">お問い合わせ</a></li>
                    <li><a href="{{ route('partner.create') }}" class="text-blue-600 hover:underline">提携に関するご相談</a></li>
                </ul>
            </section>

            <!-- お役立ちガイド -->
            <section>
                <h2 class="text-lg font-bold text-gray-900 mb-3 pb-2 border-b-2 border-blue-500">お役立ちガイド</h2>
                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-1">
                    <li><a href="{{ route('guide.window-cleaning-price') }}" class="text-blue-600 hover:underline">窓ガラス清掃の費用相場ガイド</a></li>
                    <li><a href="{{ route('guide.window-cleaning-contractor-selection') }}" class="text-blue-600 hover:underline">窓ガラス清掃業者の選び方ガイド</a></li>
                    <li><a href="{{ route('guide.exterior-wall-painting-pricing') }}" class="text-blue-600 hover:underline">外壁塗装の費用相場ガイド</a></li>
                    <li><a href="{{ route('guide.exterior-wall-painting-contractor-selection') }}" class="text-blue-600 hover:underline">外壁塗装業者の選び方ガイド</a></li>
                </ul>
            </section>

            <!-- サービスから探す -->
            <section>
                <h2 class="text-lg font-bold text-gray-900 mb-3 pb-2 border-b-2 border-blue-500">サービスから探す</h2>
                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-1">
                    @foreach($categoryHubs as $hubSlug => $hubConfig)
                        <li><a href="{{ route('hub.category', $hubSlug) }}" class="text-blue-600 hover:underline">{{ $hubConfig['nav_label'] ?? $hubConfig['label'] }}</a></li>
                    @endforeach
                </ul>
            </section>

            <!-- 条件で絞り込む -->
            <section>
                <h2 class="text-lg font-bold text-gray-900 mb-3 pb-2 border-b-2 border-blue-500">条件で絞り込む</h2>
                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-1">
                    @foreach($conditionHubs as $hubSlug => $hubConfig)
                        <li><a href="{{ route('hub.category', $hubSlug) }}" class="text-blue-600 hover:underline">{{ $hubConfig['nav_label'] ?? $hubConfig['label'] }}</a></li>
                    @endforeach
                </ul>
            </section>

            <!-- 都道府県から探す -->
            <section>
                <h2 class="text-lg font-bold text-gray-900 mb-3 pb-2 border-b-2 border-blue-500">都道府県から探す</h2>
                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-1">
                    @foreach($areaPages as $areaSlug => $areaConfig)
                        <li><a href="{{ route('area.show', $areaSlug) }}" class="text-blue-600 hover:underline">{{ $areaConfig['label'] }}</a></li>
                    @endforeach
                </ul>
            </section>

            <!-- 都道府県×サービスの組み合わせ -->
            <section>
                <h2 class="text-lg font-bold text-gray-900 mb-3 pb-2 border-b-2 border-blue-500">都道府県×サービスの組み合わせページ</h2>
                <p class="text-sm text-gray-600 mb-4">掲載企業数が一定数に達しているエリア×サービスの組み合わせだけを掲載しています。</p>
                <div class="space-y-6">
                    @forelse($combinationsByArea as $areaSlug => $combos)
                        <div>
                            <h3 class="font-bold text-gray-900 mb-2">{{ $combos->first()['areaConfig']['label'] }}</h3>
                            <ul class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-1">
                                @foreach($combos as $combo)
                                    <li>
                                        <a href="{{ route('area.hub.show', [$combo['areaSlug'], $combo['hubSlug']]) }}" class="text-blue-600 hover:underline">
                                            {{ $combo['areaConfig']['label'] }}の{{ $combo['hubConfig']['label'] }}業者一覧
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @empty
                        <p class="text-gray-600">現在準備中です。</p>
                    @endforelse
                </div>
            </section>

            <!-- 規約・その他 -->
            <section>
                <h2 class="text-lg font-bold text-gray-900 mb-3 pb-2 border-b-2 border-blue-500">規約・その他</h2>
                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-1">
                    <li><a href="{{ route('legal.privacy') }}" class="text-blue-600 hover:underline">プライバシーポリシー</a></li>
                    <li><a href="{{ route('legal.terms') }}" class="text-blue-600 hover:underline">利用規約</a></li>
                    <li><a href="{{ route('legal.disclaimer') }}" class="text-blue-600 hover:underline">免責事項</a></li>
                </ul>
            </section>

        </div>
    </div>
</div>
@endsection
