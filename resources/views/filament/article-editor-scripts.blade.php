<div>
<!-- 表・ボタン作成モーダル -->
<div id="table-creator-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeTableCreator()"></div>

        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div class="mb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">表を作成</h3>
                <p class="mt-1 text-sm text-gray-500">行と列の数を指定して表を作成します</p>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">行数</label>
                    <input type="number" id="table-rows" value="3" min="1" max="20" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">列数</label>
                    <input type="number" id="table-cols" value="2" min="1" max="10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">表のスタイル</label>
                <select id="table-style" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="bordered">枠線あり</option>
                    <option value="simple">シンプル</option>
                    <option value="striped">ストライプ</option>
                </select>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeTableCreator()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    キャンセル
                </button>
                <button type="button" onclick="generateTable()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    表を生成
                </button>
            </div>
        </div>
    </div>
</div>

<div id="button-creator-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeButtonCreator()"></div>

        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div class="mb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">ボタンを作成</h3>
                <p class="mt-1 text-sm text-gray-500">カスタムボタンを作成します</p>
            </div>

            <div class="space-y-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">ボタンテキスト</label>
                    <input type="text" id="button-text" value="ボタン" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">リンクURL</label>
                    <input type="url" id="button-url" value="https://" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">ボタンスタイル</label>
                    <select id="button-style" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="primary">青（プライマリ）</option>
                        <option value="success">緑（成功）</option>
                        <option value="warning">オレンジ（警告）</option>
                        <option value="danger">赤（危険）</option>
                        <option value="secondary">グレー（セカンダリ）</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">ボタンサイズ</label>
                    <select id="button-size" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="sm">小</option>
                        <option value="md" selected>中</option>
                        <option value="lg">大</option>
                    </select>
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" id="button-new-tab" class="rounded border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">新しいタブで開く</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeButtonCreator()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    キャンセル
                </button>
                <button type="button" onclick="generateButton()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    ボタンを生成
                </button>
            </div>
        </div>
    </div>
</div>

<div id="chart-creator-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeChartCreator()"></div>

        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6">
            <div class="mb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">グラフを作成</h3>
                <p class="mt-1 text-sm text-gray-500">データを入力してグラフを作成します</p>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">グラフタイトル</label>
                    <input type="text" id="chart-title" value="グラフタイトル" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">グラフタイプ</label>
                    <select id="chart-type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="bar">棒グラフ</option>
                        <option value="line">折れ線グラフ</option>
                        <option value="pie">円グラフ</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">データ入力</label>
                <div id="chart-data-container">
                    <div class="grid grid-cols-2 gap-2 mb-2">
                        <input type="text" placeholder="ラベル" class="chart-label border rounded px-2 py-1" value="項目1">
                        <input type="number" placeholder="値" class="chart-value border rounded px-2 py-1" value="10">
                    </div>
                    <div class="grid grid-cols-2 gap-2 mb-2">
                        <input type="text" placeholder="ラベル" class="chart-label border rounded px-2 py-1" value="項目2">
                        <input type="number" placeholder="値" class="chart-value border rounded px-2 py-1" value="20">
                    </div>
                    <div class="grid grid-cols-2 gap-2 mb-2">
                        <input type="text" placeholder="ラベル" class="chart-label border rounded px-2 py-1" value="項目3">
                        <input type="number" placeholder="値" class="chart-value border rounded px-2 py-1" value="15">
                    </div>
                </div>
                <button type="button" onclick="addChartDataRow()" class="text-blue-600 hover:text-blue-800 text-sm">+ 項目を追加</button>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeChartCreator()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    キャンセル
                </button>
                <button type="button" onclick="generateChart()" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
                    グラフを生成
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// 表作成機能
function openTableCreator() {
    document.getElementById('table-creator-modal').classList.remove('hidden');
}

function closeTableCreator() {
    document.getElementById('table-creator-modal').classList.add('hidden');
}

function generateTable() {
    const rows = parseInt(document.getElementById('table-rows').value);
    const cols = parseInt(document.getElementById('table-cols').value);
    const style = document.getElementById('table-style').value;

    let tableClass = '';
    let tableStyle = '';

    switch(style) {
        case 'bordered':
            tableClass = 'border border-gray-300';
            tableStyle = 'border-collapse: collapse; width: 100%;';
            break;
        case 'simple':
            tableClass = '';
            tableStyle = 'width: 100%;';
            break;
        case 'striped':
            tableClass = 'table-striped';
            tableStyle = 'width: 100%;';
            break;
    }

    let html = `<table class="${tableClass}" style="${tableStyle}">\n`;
    
    // ヘッダー行
    html += '  <thead>\n    <tr>\n';
    for (let j = 0; j < cols; j++) {
        html += `      <th style="border: 1px solid #ccc; padding: 8px; background-color: #f5f5f5;">ヘッダー${j + 1}</th>\n`;
    }
    html += '    </tr>\n  </thead>\n';

    // データ行
    html += '  <tbody>\n';
    for (let i = 1; i < rows; i++) {
        html += '    <tr>\n';
        for (let j = 0; j < cols; j++) {
            html += `      <td style="border: 1px solid #ccc; padding: 8px;">データ${i}-${j + 1}</td>\n`;
        }
        html += '    </tr>\n';
    }
    html += '  </tbody>\n</table>';

    // ヘルパーテキストエリアに結果を表示
    const helperTextarea = document.querySelector('textarea[wire\\:model="data.html_helper"]') || 
                          document.querySelector('[name="html_helper"]') ||
                          document.querySelector('textarea[placeholder*="HTMLコード"]');
    
    if (helperTextarea) {
        helperTextarea.value = html;
    }

    closeTableCreator();
}

// ボタン作成機能
function openButtonCreator() {
    document.getElementById('button-creator-modal').classList.remove('hidden');
}

function closeButtonCreator() {
    document.getElementById('button-creator-modal').classList.add('hidden');
}

function generateButton() {
    const text = document.getElementById('button-text').value;
    const url = document.getElementById('button-url').value;
    const style = document.getElementById('button-style').value;
    const size = document.getElementById('button-size').value;
    const newTab = document.getElementById('button-new-tab').checked;

    let buttonClass = '';
    let buttonStyle = '';

    // スタイルクラス
    switch(style) {
        case 'primary':
            buttonClass = 'bg-blue-500 hover:bg-blue-700 text-white';
            break;
        case 'success':
            buttonClass = 'bg-green-500 hover:bg-green-700 text-white';
            break;
        case 'warning':
            buttonClass = 'bg-orange-500 hover:bg-orange-700 text-white';
            break;
        case 'danger':
            buttonClass = 'bg-red-500 hover:bg-red-700 text-white';
            break;
        case 'secondary':
            buttonClass = 'bg-gray-500 hover:bg-gray-700 text-white';
            break;
    }

    // サイズクラス
    switch(size) {
        case 'sm':
            buttonClass += ' py-1 px-2 text-sm';
            break;
        case 'md':
            buttonClass += ' py-2 px-4 text-base';
            break;
        case 'lg':
            buttonClass += ' py-3 px-6 text-lg';
            break;
    }

    buttonClass += ' font-bold rounded transition-colors duration-200';

    const target = newTab ? ' target="_blank"' : '';
    
    const html = `<a href="${url}" class="${buttonClass}" style="display: inline-block; text-decoration: none;"${target}>
    ${text}
</a>`;

    // ヘルパーテキストエリアに結果を表示
    const helperTextarea = document.querySelector('textarea[wire\\:model="data.html_helper"]') || 
                          document.querySelector('[name="html_helper"]') ||
                          document.querySelector('textarea[placeholder*="HTMLコード"]');
    
    if (helperTextarea) {
        helperTextarea.value = html;
    }

    closeButtonCreator();
}

// グラフ作成機能
function openChartCreator() {
    document.getElementById('chart-creator-modal').classList.remove('hidden');
}

function closeChartCreator() {
    document.getElementById('chart-creator-modal').classList.add('hidden');
}

function addChartDataRow() {
    const container = document.getElementById('chart-data-container');
    const newRow = document.createElement('div');
    newRow.className = 'grid grid-cols-2 gap-2 mb-2';
    newRow.innerHTML = `
        <input type="text" placeholder="ラベル" class="chart-label border rounded px-2 py-1">
        <input type="number" placeholder="値" class="chart-value border rounded px-2 py-1">
    `;
    container.appendChild(newRow);
}

function generateChart() {
    const title = document.getElementById('chart-title').value;
    const type = document.getElementById('chart-type').value;
    
    const labels = [];
    const values = [];
    
    document.querySelectorAll('.chart-label').forEach((input, index) => {
        const label = input.value.trim();
        const value = parseFloat(document.querySelectorAll('.chart-value')[index].value) || 0;
        if (label) {
            labels.push(label);
            values.push(value);
        }
    });

    if (labels.length === 0) {
        alert('少なくとも1つのデータを入力してください。');
        return;
    }

    let html = '';
    const chartId = 'chart-' + Math.random().toString(36).substr(2, 9);

    if (type === 'bar') {
        html = generateBarChart(chartId, title, labels, values);
    } else if (type === 'line') {
        html = generateLineChart(chartId, title, labels, values);
    } else if (type === 'pie') {
        html = generatePieChart(chartId, title, labels, values);
    }

    // ヘルパーテキストエリアに結果を表示
    const helperTextarea = document.querySelector('textarea[wire\\:model="data.html_helper"]') || 
                          document.querySelector('[name="html_helper"]') ||
                          document.querySelector('textarea[placeholder*="HTMLコード"]');
    
    if (helperTextarea) {
        helperTextarea.value = html;
    }

    closeChartCreator();
}

function generateBarChart(chartId, title, labels, values) {
    const maxValue = Math.max(...values);
    const barHtml = labels.map((label, index) => {
        const height = (values[index] / maxValue) * 200;
        const percentage = ((values[index] / maxValue) * 100).toFixed(1);
        return `
            <div class="chart-bar-item" style="text-align: center;">
                <div class="chart-bar-value" style="margin-bottom: 5px; font-size: 12px; color: #666;">${values[index]}</div>
                <div class="chart-bar" style="width: 40px; height: ${height}px; background: linear-gradient(to top, #3b82f6, #60a5fa); margin: 0 auto 10px; border-radius: 4px 4px 0 0;"></div>
                <div class="chart-bar-label" style="font-size: 12px; color: #374151; word-break: break-word;">${label}</div>
            </div>
        `;
    }).join('');

    return `
<div class="chart-container" style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; margin: 20px 0; background: white;">
    <h3 style="text-align: center; margin-bottom: 20px; font-size: 18px; color: #1f2937;">${title}</h3>
    <div style="display: flex; justify-content: center; align-items: end; gap: 20px; min-height: 250px;">
        ${barHtml}
    </div>
</div>`;
}

function generateLineChart(chartId, title, labels, values) {
    const maxValue = Math.max(...values);
    const points = labels.map((label, index) => {
        const x = (index / (labels.length - 1)) * 300;
        const y = 200 - (values[index] / maxValue) * 180;
        return { x, y, value: values[index] };
    });

    const pathData = points.map((point, index) => 
        `${index === 0 ? 'M' : 'L'} ${point.x + 50} ${point.y + 30}`
    ).join(' ');

    const pointsHtml = points.map(point => 
        `<circle cx="${point.x + 50}" cy="${point.y + 30}" r="4" fill="#3b82f6"/>`
    ).join('');

    const labelsHtml = labels.map((label, index) => 
        `<text x="${(index / (labels.length - 1)) * 300 + 50}" y="240" text-anchor="middle" font-size="12" fill="#374151">${label}</text>`
    ).join('');

    return `
<div class="chart-container" style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; margin: 20px 0; background: white;">
    <h3 style="text-align: center; margin-bottom: 20px; font-size: 18px; color: #1f2937;">${title}</h3>
    <svg width="400" height="260" style="margin: 0 auto; display: block;">
        <path d="${pathData}" stroke="#3b82f6" stroke-width="2" fill="none"/>
        ${pointsHtml}
        ${labelsHtml}
    </svg>
</div>`;
}

function generatePieChart(chartId, title, labels, values) {
    const total = values.reduce((sum, val) => sum + val, 0);
    let currentAngle = 0;
    
    const slices = labels.map((label, index) => {
        const percentage = (values[index] / total) * 100;
        const angle = (values[index] / total) * 360;
        
        const x1 = 100 + 80 * Math.cos((currentAngle - 90) * Math.PI / 180);
        const y1 = 100 + 80 * Math.sin((currentAngle - 90) * Math.PI / 180);
        
        currentAngle += angle;
        
        const x2 = 100 + 80 * Math.cos((currentAngle - 90) * Math.PI / 180);
        const y2 = 100 + 80 * Math.sin((currentAngle - 90) * Math.PI / 180);
        
        const largeArcFlag = angle > 180 ? 1 : 0;
        
        const colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'];
        const color = colors[index % colors.length];
        
        return {
            path: `M 100,100 L ${x1},${y1} A 80,80 0 ${largeArcFlag},1 ${x2},${y2} Z`,
            color,
            label,
            value: values[index],
            percentage: percentage.toFixed(1)
        };
    });

    const slicesHtml = slices.map(slice => 
        `<path d="${slice.path}" fill="${slice.color}"/>`
    ).join('');

    const legendHtml = slices.map((slice, index) => 
        `<div style="display: flex; align-items: center; margin-bottom: 8px;">
            <div style="width: 16px; height: 16px; background: ${slice.color}; margin-right: 8px; border-radius: 2px;"></div>
            <span style="font-size: 14px; color: #374151;">${slice.label}: ${slice.value} (${slice.percentage}%)</span>
        </div>`
    ).join('');

    return `
<div class="chart-container" style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; margin: 20px 0; background: white;">
    <h3 style="text-align: center; margin-bottom: 20px; font-size: 18px; color: #1f2937;">${title}</h3>
    <div style="display: flex; align-items: center; justify-content: center; gap: 40px;">
        <svg width="200" height="200">
            ${slicesHtml}
        </svg>
        <div style="flex: 1; max-width: 200px;">
            ${legendHtml}
        </div>
    </div>
</div>`;
}

// ESCキーでモーダルを閉じる
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeTableCreator();
        closeButtonCreator();
        closeChartCreator();
    }
});
</script>

<style>
.table-striped tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}
</style>
</div>