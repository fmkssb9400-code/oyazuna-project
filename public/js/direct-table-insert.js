// 直接テーブル挿入のためのスクリプト
window.insertDirectTable = function(cols, rows) {
    console.log('Direct table insertion started with cols:', cols, 'rows:', rows);
    
    // Trixエディタを取得
    const trixEditor = document.querySelector('trix-editor');
    if (!trixEditor) {
        console.error('Trix editor not found for direct insertion');
        return false;
    }
    
    // テーブル要素を直接作成
    const table = document.createElement('table');
    table.style.cssText = `
        display: table !important;
        width: 100% !important;
        border-collapse: collapse !important;
        border: 2px solid #d1d5db !important;
        margin: 20px 0 !important;
        background: white !important;
        font-size: 14px !important;
    `;
    
    // ヘッダー作成
    const thead = document.createElement('thead');
    thead.style.display = 'table-header-group';
    const headerRow = document.createElement('tr');
    headerRow.style.display = 'table-row';
    
    for (let i = 0; i < cols; i++) {
        const th = document.createElement('th');
        th.style.cssText = `
            display: table-cell !important;
            border: 1px solid #9ca3af !important;
            padding: 12px !important;
            background-color: #f3f4f6 !important;
            font-weight: bold !important;
            cursor: text !important;
            text-align: left !important;
        `;
        th.contentEditable = true;
        th.textContent = i === 0 ? '項目' : '比較' + i;
        
        // フォーカス・ブラーイベント追加
        th.addEventListener('focus', function() {
            this.style.outline = '2px solid #3b82f6';
            this.style.backgroundColor = '#eff6ff';
        });
        
        th.addEventListener('blur', function() {
            this.style.outline = 'none';
            this.style.backgroundColor = '#f3f4f6';
        });
        
        headerRow.appendChild(th);
    }
    
    thead.appendChild(headerRow);
    table.appendChild(thead);
    
    // ボディ作成
    const tbody = document.createElement('tbody');
    tbody.style.display = 'table-row-group';
    
    for (let row = 1; row < rows; row++) {
        const tr = document.createElement('tr');
        tr.style.display = 'table-row';
        
        for (let col = 0; col < cols; col++) {
            const cell = col === 0 ? document.createElement('th') : document.createElement('td');
            
            cell.style.cssText = `
                display: table-cell !important;
                border: 1px solid #9ca3af !important;
                padding: 12px !important;
                cursor: text !important;
                text-align: left !important;
                background-color: ${col === 0 ? '#f9fafb' : 'white'} !important;
                font-weight: ${col === 0 ? 'bold' : 'normal'} !important;
            `;
            
            cell.contentEditable = true;
            cell.textContent = col === 0 ? '項目' + row : 'データ';
            
            // フォーカス・ブラーイベント追加
            cell.addEventListener('focus', function() {
                this.style.outline = '2px solid #3b82f6';
                this.style.backgroundColor = '#eff6ff';
            });
            
            cell.addEventListener('blur', function() {
                this.style.outline = 'none';
                const bgColor = col === 0 ? '#f9fafb' : 'white';
                this.style.backgroundColor = bgColor;
            });
            
            tr.appendChild(cell);
        }
        
        tbody.appendChild(tr);
    }
    
    table.appendChild(tbody);
    
    // テーブルをTrixエディタに挿入
    try {
        // Method 1: Trix APIを試す
        if (trixEditor.editor && typeof trixEditor.editor.insertHTML === 'function') {
            const tableHTML = table.outerHTML;
            trixEditor.editor.insertHTML(tableHTML);
            console.log('Table inserted via Trix insertHTML');
            return true;
        }
        
        // Method 2: 直接DOM挿入
        trixEditor.appendChild(table);
        console.log('Table inserted via direct DOM manipulation');
        
        // Trixエディタの内容を更新
        if (trixEditor.editor && typeof trixEditor.editor.recordUndoEntry === 'function') {
            trixEditor.editor.recordUndoEntry('Insert Table');
        }
        
        return true;
        
    } catch (error) {
        console.error('Error in direct table insertion:', error);
        
        // Method 3: より直接的なDOM挿入
        try {
            const editorElement = trixEditor.querySelector('[contenteditable="true"]') || trixEditor;
            editorElement.appendChild(table);
            console.log('Table inserted via contenteditable element');
            return true;
        } catch (innerError) {
            console.error('All insertion methods failed:', innerError);
            return false;
        }
    }
};

// ページ読み込み時に実行される関数
document.addEventListener('DOMContentLoaded', function() {
    console.log('Direct table insert script loaded');
    
    // グローバル関数として公開
    window.forceDirectTableInsert = function(cols = 3, rows = 5) {
        return window.insertDirectTable(cols, rows);
    };
});