<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            人気記事ランキング（今月・Google Analytics）
        </x-slot>

        @if (! $this->isReady())
            <p class="text-sm text-gray-500">
                Google Analyticsが設定されていないため、ランキングを表示できません。
            </p>
        @else
            @php($rows = $this->getRows())

            @if (empty($rows))
                <p class="text-sm text-gray-500">
                    今月のデータがまだありません。
                </p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th class="py-2 text-center w-10">#</th>
                                <th class="py-2 text-left">記事タイトル</th>
                                <th class="py-2 text-center w-24">PV数</th>
                                <th class="py-2 text-center w-28">公開日</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $index => $row)
                                <tr class="border-b border-gray-100 dark:border-gray-800">
                                    <td class="py-2 text-center">{{ $index + 1 }}</td>
                                    <td class="py-2">
                                        <a
                                            href="{{ route('news.show', $row['article']->slug) }}"
                                            target="_blank"
                                            class="text-primary-600 hover:underline"
                                            title="{{ $row['article']->title }}"
                                        >
                                            {{ \Illuminate\Support\Str::limit($row['article']->title, 50) }}
                                        </a>
                                    </td>
                                    <td class="py-2 text-center">{{ number_format($row['views']) }}</td>
                                    <td class="py-2 text-center">{{ $row['article']->published_at?->format('Y/m/d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
