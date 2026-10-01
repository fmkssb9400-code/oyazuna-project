{{--
    パンくずナビゲーション共通コンポーネント。
    $items: [['label' => '表示名', 'url' => 'https://...'], ..., ['label' => '現在地', 'url' => null]]
          先頭に「ホーム」は自動で付与するので渡さなくてよい。最後の要素はurl=nullで現在地として扱う。
    画面表示用のnavと、BreadcrumbList構造化データ(JSON-LD)を両方出力する。
--}}
@props(['items' => []])

@php
    $__crumbs = array_merge(
        [['label' => 'ホーム', 'url' => 'https://oyazuna.com/']],
        $items
    );
@endphp

<nav aria-label="パンくずリスト" class="text-xs md:text-sm text-gray-500 mb-4 overflow-x-auto whitespace-nowrap">
    <ol class="flex items-center gap-1">
        @foreach($__crumbs as $__i => $__crumb)
            <li class="flex items-center gap-1">
                @if($__i > 0)
                    <span class="text-gray-300" aria-hidden="true">&rsaquo;</span>
                @endif
                @if(!empty($__crumb['url']) && $__i < count($__crumbs) - 1)
                    <a href="{{ $__crumb['url'] }}" class="hover:text-blue-600 hover:underline">{{ $__crumb['label'] }}</a>
                @else
                    <span class="text-gray-700" aria-current="page">{{ $__crumb['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>

<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => collect($__crumbs)->values()->map(function ($crumb, $i) {
        return [
            '@type' => 'ListItem',
            'position' => $i + 1,
            'name' => $crumb['label'],
            'item' => $crumb['url'] ?? request()->fullUrl(),
        ];
    })->all(),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
