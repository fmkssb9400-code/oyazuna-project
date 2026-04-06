@extends('layouts.app')

@section('title', '業者比較 - オヤズナ')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">業者比較</h1>
    
    @if($companies->isEmpty())
        <div class="text-center py-12">
            <p class="text-gray-600 text-lg">比較する業者が選択されていません。</p>
            <a href="{{ route('companies.index') }}" class="mt-4 inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                業者一覧に戻る
            </a>
        </div>
    @else
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">項目</th>
                        @foreach($companies as $company)
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $company->name }}
                                <button onclick="removeFromCompare({{ $company->id }})" class="ml-2 text-red-500 hover:text-red-700">
                                    ×
                                </button>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">対応工法</td>
                        @foreach($companies as $company)
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $company->serviceMethods->pluck('label')->implode('、') }}
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">対応サービス</td>
                        @foreach($companies as $company)
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $company->serviceCategories->pluck('label')->implode('、') }}
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">対応建物</td>
                        @foreach($companies as $company)
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $company->buildingTypes->pluck('label')->implode('、') }}
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">最大階数</td>
                        @foreach($companies as $company)
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @if($company->max_floor)
                                    {{ $company->max_floor }}階
                                @else
                                    制限なし
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">緊急対応</td>
                        @foreach($companies as $company)
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @if($company->emergency_supported)
                                    <span class="text-red-600 font-semibold">対応可</span>
                                @else
                                    対応不可
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">料金目安</td>
                        @foreach($companies as $company)
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $company->price_note ?: '-' }}
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">アクション</td>
                        @foreach($companies as $company)
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="space-y-2">
                                    <a href="{{ route('companies.show', $company->slug) }}" 
                                       class="block bg-blue-600 text-white px-4 py-2 rounded text-center hover:bg-blue-700">
                                        詳細
                                    </a>
                                    <a href="{{ route('quote.create', ['company_id' => $company->id]) }}" 
                                       class="block bg-green-600 text-white px-4 py-2 rounded text-center hover:bg-green-700">
                                        見積依頼
                                    </a>
                                </div>
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="bg-gray-50 px-8 py-6 text-center">
            <a href="{{ route('quote.create') }}" class="bg-orange-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-orange-700">
                この条件で一括見積もり依頼
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function removeFromCompare(companyId) {
    $.ajax({
        url: '/compare/remove/' + companyId,
        method: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        }
    }).done(function(data) {
        if (data.success) {
            location.reload();
        }
    }).fail(function() {
        alert('エラーが発生しました');
    });
}
</script>
@endsection