// フロントエンド記事ページでのテーブル表示修正
document.addEventListener('DOMContentLoaded', function() {
    console.log('Frontend table fix script loaded');
    
    // すぐに実行
    fixFrontendTables();
    
    // 少し遅れて再実行（CSSが完全に読み込まれた後）
    setTimeout(fixFrontendTables, 500);
});

function fixFrontendTables() {
    const selectors = [
        '.article-content table',
        '.prose table',
        'table'
    ];
    
    let tablesFound = 0;
    
    selectors.forEach(function(selector) {
        const tables = document.querySelectorAll(selector);
        tables.forEach(function(table) {
            // 記事内のテーブルかチェック
            const isInArticle = table.closest('.article-content') || table.closest('.prose');
            if (!isInArticle && selector === 'table') return;
            
            tablesFound++;
            console.log('Fixing frontend table:', table);
            
            // テーブル自体のスタイル強制
            table.style.cssText = `
                display: table !important;
                width: 100% !important;
                border-collapse: collapse !important;
                margin: 2rem 0 !important;
                background: white !important;
                border-radius: 8px !important;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
                border: 2px solid #e5e7eb !important;
                font-size: 0.875rem !important;
                line-height: 1.25rem !important;
                table-layout: auto !important;
                overflow: hidden !important;
            `;
            
            // thead の表示強制
            const thead = table.querySelector('thead');
            if (thead) {
                thead.style.cssText = 'display: table-header-group !important;';
            }
            
            // tbody の表示強制
            const tbody = table.querySelector('tbody');
            if (tbody) {
                tbody.style.cssText = 'display: table-row-group !important;';
            }
            
            // すべての行の表示強制
            const rows = table.querySelectorAll('tr');
            rows.forEach(function(row) {
                row.style.cssText = 'display: table-row !important;';
            });
            
            // すべてのセルの表示強制
            const cells = table.querySelectorAll('th, td');
            cells.forEach(function(cell) {
                cell.style.cssText = `
                    display: table-cell !important;
                    padding: 1rem !important;
                    border: 1px solid #e5e7eb !important;
                    vertical-align: top !important;
                `;
            });
            
            // ヘッダーセルの特別なスタイリング
            const headers = table.querySelectorAll('th');
            headers.forEach(function(header) {
                header.style.backgroundColor = '#f3f4f6';
                header.style.color = '#1f2937';
                header.style.fontWeight = '600';
                header.style.textAlign = 'left';
            });
            
            // データセルの背景色設定
            const dataCells = table.querySelectorAll('td');
            dataCells.forEach(function(cell, index) {
                const row = cell.parentElement;
                const rowIndex = Array.from(row.parentElement.children).indexOf(row);
                
                if (rowIndex % 2 === 0) {
                    cell.style.backgroundColor = '#f9fafb';
                } else {
                    cell.style.backgroundColor = 'white';
                }
            });
        });
    });
    
    if (tablesFound > 0) {
        console.log('Fixed', tablesFound, 'frontend tables');
    }
}