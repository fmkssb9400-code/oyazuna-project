@php
    use Illuminate\Support\Facades\Storage;
@endphp

<div class="space-y-4">
    @if(!empty($images) && is_array($images))
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($images as $index => $image)
                @php
                    $imageExists = Storage::disk('public')->exists($image);
                    $imageUrl = $imageExists ? Storage::disk('public')->url($image) : null;
                @endphp
                
                <div class="border rounded-lg p-3">
                    <div class="mb-2">
                        <strong>画像 {{ $index + 1 }}</strong>
                    </div>
                    
                    @if($imageExists && $imageUrl)
                        <img src="{{ $imageUrl }}" 
                             alt="見積書画像 {{ $index + 1 }}" 
                             class="w-full h-48 object-cover rounded cursor-pointer border" 
                             onclick="window.open('{{ $imageUrl }}', '_blank')"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        
                        <div class="hidden bg-red-50 border border-red-200 p-4 rounded text-center">
                            <span class="text-red-600">画像読み込みエラー</span><br>
                            <span class="text-xs text-red-500">{{ $imageUrl }}</span>
                        </div>
                        
                        <p class="text-xs text-gray-500 mt-2 text-center">
                            ファイル名: {{ basename($image) }}
                        </p>
                    @else
                        <div class="w-full h-48 bg-gray-200 rounded flex flex-col items-center justify-center">
                            <span class="text-gray-500 text-sm mb-2">❌ 画像が見つかりません</span>
                            <span class="text-xs text-gray-400 text-center px-2">{{ $image }}</span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8 text-gray-500 bg-gray-50 rounded">
            📷 アップロードされた画像がありません
        </div>
    @endif
</div>