@php
    // quote_itemsフィールドから現在の状態を取得
    $record = $getRecord();
    $state = [];
    if ($record && $record->quote_items) {
        $state = $record->quote_items;
    } elseif ($getState() && $getState()['quote_items']) {
        $state = $getState()['quote_items'];
    }
    $isDisabled = $isDisabled();
@endphp

<div x-data="quoteTable(@js($state))" class="space-y-4">
    <div class="bg-white border border-gray-300 rounded-lg shadow-sm overflow-hidden">
        <!-- テーブルヘッダー -->
        <div class="bg-green-50 border-b border-gray-200 px-4 py-2">
            <h3 class="text-sm font-medium text-gray-900">見積もり項目</h3>
        </div>
        
        <!-- テーブル本体 -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-green-100">
                    <tr>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider min-w-48">
                            項目名
                        </th>
                        <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider min-w-20">
                            単位
                        </th>
                        <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider min-w-24">
                            数量
                        </th>
                        <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider min-w-24">
                            単価
                        </th>
                        <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider min-w-20">
                            回/年
                        </th>
                        <th class="px-3 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider min-w-28">
                            金額
                        </th>
                        @unless($isDisabled)
                        <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider w-16">
                            操作
                        </th>
                        @endunless
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-for="(item, index) in items" :key="index">
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2">
                                <input 
                                    type="text" 
                                    x-model="item.item_name"
                                    @input="updateData()"
                                    placeholder="例：窓ガラス清掃"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                    :disabled="{{ $isDisabled ? 'true' : 'false' }}"
                                />
                            </td>
                            <td class="px-3 py-2">
                                <input 
                                    type="text" 
                                    x-model="item.unit"
                                    @input="updateData()"
                                    placeholder="㎡"
                                    class="w-full text-center border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                    :disabled="{{ $isDisabled ? 'true' : 'false' }}"
                                />
                            </td>
                            <td class="px-3 py-2">
                                <input 
                                    type="number" 
                                    x-model.number="item.quantity"
                                    @input="calculateSubtotal(index); updateData()"
                                    step="0.01"
                                    placeholder="0"
                                    class="w-full text-center border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                    :disabled="{{ $isDisabled ? 'true' : 'false' }}"
                                />
                            </td>
                            <td class="px-3 py-2">
                                <input 
                                    type="number" 
                                    x-model.number="item.unit_price"
                                    @input="calculateSubtotal(index); updateData()"
                                    step="1"
                                    placeholder="0"
                                    class="w-full text-center border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                    :disabled="{{ $isDisabled ? 'true' : 'false' }}"
                                />
                            </td>
                            <td class="px-3 py-2">
                                <input 
                                    type="number" 
                                    x-model.number="item.frequency"
                                    @input="calculateSubtotal(index); updateData()"
                                    step="1"
                                    min="1"
                                    placeholder="1"
                                    class="w-full text-center border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                    :disabled="{{ $isDisabled ? 'true' : 'false' }}"
                                />
                            </td>
                            <td class="px-3 py-2">
                                <input 
                                    type="number" 
                                    x-model.number="item.total_price"
                                    @input="updateData()"
                                    step="1"
                                    placeholder="0"
                                    class="w-full text-right border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                    :disabled="{{ $isDisabled ? 'true' : 'false' }}"
                                />
                            </td>
                            @unless($isDisabled)
                            <td class="px-3 py-2 text-center">
                                <button 
                                    type="button"
                                    @click="removeItem(index)"
                                    class="text-red-600 hover:text-red-900 text-sm"
                                >
                                    ×
                                </button>
                            </td>
                            @endunless
                        </tr>
                    </template>
                    
                    <!-- 合計行 -->
                    <tr class="bg-blue-50 font-medium">
                        <td colspan="{{ $isDisabled ? '5' : '6' }}" class="px-3 py-3 text-right text-sm font-semibold text-gray-900">
                            合計
                        </td>
                        <td class="px-3 py-3">
                            <div class="text-right text-lg font-bold text-blue-600">
                                ¥<span x-text="formatNumber(calculateTotal())"></span>
                            </div>
                        </td>
                        @unless($isDisabled)
                        <td class="px-3 py-3"></td>
                        @endunless
                    </tr>
                </tbody>
            </table>
        </div>
        
        @unless($isDisabled)
        <!-- 行追加ボタン -->
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
            <button 
                type="button"
                @click="addItem()"
                class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                行を追加
            </button>
        </div>
        @endunless
    </div>
    
    <!-- エラーメッセージ表示 -->
    @error($statePath)
        <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
    @enderror
</div>

<script>
function quoteTable(initialState) {
    return {
        items: initialState || [],
        
        init() {
            // 初期状態で最低1行は表示
            if (this.items.length === 0) {
                this.addItem();
            }
            
            // 既存アイテムの小計を計算
            this.items.forEach((item, index) => {
                this.calculateSubtotal(index);
            });
            
            // 初期データをFilamentに送信
            this.updateData();
        },
        
        addItem() {
            this.items.push({
                item_name: '',
                unit: '',
                quantity: 1,
                unit_price: 0,
                frequency: 1,
                total_price: 0
            });
            this.updateData();
        },
        
        removeItem(index) {
            this.items.splice(index, 1);
            this.updateData();
        },
        
        calculateSubtotal(index) {
            const item = this.items[index];
            if (item) {
                const quantity = parseFloat(item.quantity) || 0;
                const unitPrice = parseFloat(item.unit_price) || 0;
                const frequency = parseFloat(item.frequency) || 1;
                item.total_price = quantity * unitPrice * frequency;
            }
        },
        
        calculateTotal() {
            return this.items.reduce((total, item) => {
                return total + (parseFloat(item.total_price) || 0);
            }, 0);
        },
        
        formatNumber(number) {
            return new Intl.NumberFormat('ja-JP').format(Math.round(number || 0));
        },
        
        updateData() {
            // Filamentのquote_itemsフィールドを探して更新
            const textarea = document.querySelector('textarea[name="quote_items"]');
            if (textarea) {
                textarea.value = JSON.stringify(this.items);
                textarea.dispatchEvent(new Event('input', { bubbles: true }));
            }
        }
    }
}
</script>

<style>
/* スマホ対応：横スクロール時のテーブルセル最小幅 */
@media (max-width: 640px) {
    .min-w-48 { min-width: 12rem; }
    .min-w-28 { min-width: 7rem; }
    .min-w-24 { min-width: 6rem; }
    .min-w-20 { min-width: 5rem; }
}

/* テーブル内の入力フィールドのスタイル調整 */
.quote-table input[type="number"]::-webkit-outer-spin-button,
.quote-table input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.quote-table input[type="number"] {
    -moz-appearance: textfield;
}
</style>