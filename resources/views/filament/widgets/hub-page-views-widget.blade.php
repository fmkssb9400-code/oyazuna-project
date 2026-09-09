<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            ハブページ PVランキング（今月・Google Analytics）
        </x-slot>

        @if (! $this->isReady())
            <p class="text-sm text-gray-500">
                Google Analyticsが設定されていないため、ランキングを表示できません。
            </p>
        @else
            @php($categoryRows = $this->getCategoryRows())
            @php($areaHubRows = $this->getAreaHubRows())

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-semibold mb-2">カテゴリハブ（/hub/*）</h3>

                    @if (empty($categoryRows))
                        <p class="text-sm text-gray-500">今月のデータがまだありません。</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <th class="py-2 text-center w-10">#</th>
                                        <th class="py-2 text-left">ページ</th>
                                        <th class="py-2 text-center w-20">PV数</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categoryRows as $index => $row)
                                        <tr class="border-b border-gray-100 dark:border-gray-800">
                                            <td class="py-2 text-center">{{ $index + 1 }}</td>
                                            <td class="py-2">
                                                <a
                                                    href="{{ route('hub.category', $row['slug']) }}"
                                                    target="_blank"
                                                    class="text-primary-600 hover:underline"
                                                >
                                                    {{ $row['label'] }}
                                                </a>
                                            </td>
                                            <td class="py-2 text-center">{{ number_format($row['views']) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <div>
                    <h3 class="text-sm font-semibold mb-2">エリア×ハブ（/area/{県}/{サービス}）</h3>

                    @if (empty($areaHubRows))
                        <p class="text-sm text-gray-500">今月のデータがまだありません。</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <th class="py-2 text-center w-10">#</th>
                                        <th class="py-2 text-left">ページ</th>
                                        <th class="py-2 text-center w-20">PV数</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($areaHubRows as $index => $row)
                                        <tr class="border-b border-gray-100 dark:border-gray-800">
                                            <td class="py-2 text-center">{{ $index + 1 }}</td>
                                            <td class="py-2">
                                                <a
                                                    href="{{ route('area.hub.show', [$row['area_slug'], $row['hub_slug']]) }}"
                                                    target="_blank"
                                                    class="text-primary-600 hover:underline"
                                                >
                                                    {{ $row['label'] }}
                                                </a>
                                            </td>
                                            <td class="py-2 text-center">{{ number_format($row['views']) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
