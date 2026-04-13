// フロントエンド記事ページでのテーブル・画像表示修正
document.addEventListener('DOMContentLoaded', function() {
    console.log('Frontend article fix script loaded');
    
    // すぐに実行
    fixFrontendTables();
    fixFrontendImages();
    
    // 少し遅れて再実行（CSSが完全に読み込まれた後）
    setTimeout(function() {
        fixFrontendTables();
        fixFrontendImages();
    }, 500);
    
    // さらに遅れて再実行（全リソースが読み込まれた後）
    setTimeout(function() {
        fixFrontendTables();
        fixFrontendImages();
    }, 2000);
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

function fixFrontendImages() {
    const selectors = [
        '.article-content img',
        '.article-content .article-image'
    ];
    
    let imagesFound = 0;
    
    selectors.forEach(function(selector) {
        const images = document.querySelectorAll(selector);
        images.forEach(function(image) {
            imagesFound++;
            console.log('Fixing frontend image:', image);
            
            // Force image styles
            image.style.cssText = `
                display: block !important;
                max-width: 100% !important;
                height: auto !important;
                margin: 1rem auto !important;
                border-radius: 8px !important;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
                object-fit: contain !important;
                background-color: transparent !important;
                visibility: visible !important;
                opacity: 1 !important;
                position: static !important;
            `;
            
            // Check if image source is accessible
            if (image.src && image.src.indexOf('/storage/') !== -1) {
                // Ensure the image loads properly
                image.onerror = function() {
                    console.error('Failed to load image:', image.src);
                    // Try to reload with a slight delay
                    setTimeout(function() {
                        if (image.src) {
                            const originalSrc = image.src;
                            image.src = '';
                            image.src = originalSrc;
                        }
                    }, 100);
                };
                
                image.onload = function() {
                    console.log('Image loaded successfully:', image.src);
                };
            }
        });
    });
    
    if (imagesFound > 0) {
        console.log('Fixed', imagesFound, 'frontend images');
    }
}