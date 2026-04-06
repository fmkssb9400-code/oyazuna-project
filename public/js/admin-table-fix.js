// Trixエディタ内でのテーブル表示・編集修正
document.addEventListener('DOMContentLoaded', function() {
    console.log('Enhanced table fix script loaded - TEXT CONVERSION DISABLED');
    
    // テキストテーブル変換を無効化 - HTMLテーブルをそのまま表示
    // setTimeout(function() {
    //     convertAllTextTables();
    //     addConvertButton();
    // }, 500);
    
    // 定期実行を無効化
    // setInterval(convertAllTextTables, 1000);
    
    // Trixエディタのイベント監視を無効化
    // document.addEventListener('trix-change', function() {
    //     setTimeout(convertAllTextTables, 100);
    // });
    
    // HTMLテーブルの表示を確実にする
    setTimeout(function() {
        enhanceHTMLTables();
    }, 500);
    
    // HTMLテーブルの定期的な強化
    setInterval(enhanceHTMLTables, 2000);
    
    // テーブル挿入監視（Filamentアクション後の通知を受信）
    let previousTableInserted = false;
    setInterval(function() {
        if (window.tableInserted && !previousTableInserted) {
            console.log('New table insertion detected, applying styles immediately...');
            setTimeout(function() {
                enhanceHTMLTables();
                forceTableDisplay();
            }, 200);
            previousTableInserted = true;
            
            // フラグをリセット
            setTimeout(function() {
                window.tableInserted = false;
                previousTableInserted = false;
            }, 1500);
        }
    }, 100);
});

// メイン変換機能
function convertAllTextTables() {
    console.log('Running table conversion...');
    
    // 全てのリッチエディタを検索
    const editors = document.querySelectorAll('.fi-fo-rich-editor, trix-editor');
    
    editors.forEach(function(editor) {
        try {
            // エディタ内のHTMLを取得
            let content = editor.innerHTML;
            const originalContent = content;
            
            // パターン1: 項目比較1比較2 で始まるテーブル
            content = content.replace(
                /項目比較1比較2項目1\s*\|\s*([^<\n]*)\s*(?:<br>|\n)項目2\s*\|\s*([^<\n]*)\s*(?:<br>|\n)項目3\s*\|\s*([^<\n]*)\s*(?:<br>|\n)項目4\s*\|\s*([^<\n]*)/gi,
                function(match, row1, row2, row3, row4) {
                    console.log('Found 3-column table:', match);
                    return generateEditableTable([
                        ['項目1', ...(row1 ? row1.split('|').map(s => s.trim()) : ['-', '-'])],
                        ['項目2', ...(row2 ? row2.split('|').map(s => s.trim()) : ['-', '-'])],
                        ['項目3', ...(row3 ? row3.split('|').map(s => s.trim()) : ['-', '-'])],
                        ['項目4', ...(row4 ? row4.split('|').map(s => s.trim()) : ['-', '-'])]
                    ], ['項目', '比較1', '比較2']);
                }
            );
            
            // パターン2: 項目比較1 で始まるテーブル（2列）
            content = content.replace(
                /項目比較1項目1\s*\|\s*([^<\n]*)\s*(?:<br>|\n)項目2\s*\|\s*([^<\n]*)\s*(?:<br>|\n)項目3\s*\|\s*([^<\n]*)\s*(?:<br>|\n)項目4\s*\|\s*([^<\n]*)/gi,
                function(match, row1, row2, row3, row4) {
                    console.log('Found 2-column table:', match);
                    return generateEditableTable([
                        ['項目1', row1 ? row1.trim() : '-'],
                        ['項目2', row2 ? row2.trim() : '-'],
                        ['項目3', row3 ? row3.trim() : '-'],
                        ['項目4', row4 ? row4.trim() : '-']
                    ], ['項目', '比較1']);
                }
            );
            
            // 変更があった場合のみ更新
            if (content !== originalContent) {
                console.log('Converting table in editor');
                editor.innerHTML = content;
                
                // 新しいテーブルを修正
                setTimeout(function() {
                    fixAllTables(editor);
                }, 50);
            }
            
        } catch (error) {
            console.error('Error in table conversion:', error);
        }
    });
}

// 編集可能なHTMLテーブルを生成
function generateEditableTable(rows, headers) {
    let html = '<div style="margin: 20px 0;"><table contenteditable="false" border="1" style="';
    html += 'border-collapse: collapse; width: 100%; background: white; ';
    html += 'border: 2px solid #d1d5db; font-size: 14px;">';
    
    // ヘッダー行
    html += '<thead><tr>';
    headers.forEach(function(header) {
        html += '<th contenteditable="true" style="';
        html += 'border: 1px solid #9ca3af; padding: 12px; ';
        html += 'background-color: #f3f4f6; font-weight: bold; ';
        html += 'text-align: left; cursor: text;">';
        html += header + '</th>';
    });
    html += '</tr></thead>';
    
    // データ行
    html += '<tbody>';
    rows.forEach(function(row) {
        html += '<tr>';
        row.forEach(function(cell, index) {
            const tag = index === 0 ? 'th' : 'td';
            const bgColor = index === 0 ? '#f9fafb' : 'white';
            const fontWeight = index === 0 ? 'bold' : 'normal';
            
            html += '<' + tag + ' contenteditable="true" style="';
            html += 'border: 1px solid #9ca3af; padding: 12px; ';
            html += 'background-color: ' + bgColor + '; ';
            html += 'font-weight: ' + fontWeight + '; ';
            html += 'text-align: left; cursor: text;">';
            html += cell + '</' + tag + '>';
        });
        html += '</tr>';
    });
    html += '</tbody></table></div>';
    
    return html;
}

// テーブルの表示を修正
function fixAllTables(container) {
    const tables = container ? container.querySelectorAll('table') : document.querySelectorAll('.fi-fo-rich-editor table, trix-editor table');
    
    tables.forEach(function(table) {
        // テーブル本体の修正
        table.style.cssText = `
            display: table !important;
            width: 100% !important;
            border-collapse: collapse !important;
            margin: 20px 0 !important;
            background: white !important;
            border: 2px solid #d1d5db !important;
            font-size: 14px !important;
            visibility: visible !important;
            opacity: 1 !important;
        `;
        
        // すべてのセルを編集可能にする
        const cells = table.querySelectorAll('th, td');
        cells.forEach(function(cell) {
            // 既存のイベントリスナーを削除（重複を防ぐため）
            if (cell.hasAttribute('data-editable-setup')) return;
            cell.setAttribute('data-editable-setup', 'true');
            
            cell.contentEditable = true;
            cell.style.cursor = 'text';
            cell.style.minWidth = '100px';
            cell.style.padding = '12px';
            cell.style.border = '1px solid #9ca3af';
            
            // フォーカス時のスタイル
            cell.addEventListener('focus', function() {
                this.style.outline = '2px solid #3b82f6';
                this.style.backgroundColor = '#eff6ff';
                console.log('Cell focused:', this.textContent);
            });
            
            cell.addEventListener('blur', function() {
                this.style.outline = 'none';
                if (this.tagName === 'TH') {
                    this.style.backgroundColor = this.parentElement.parentElement.tagName === 'THEAD' ? '#f3f4f6' : '#f9fafb';
                } else {
                    this.style.backgroundColor = 'white';
                }
                console.log('Cell updated:', this.textContent);
            });
            
            // キーボード入力を有効にする
            cell.addEventListener('keydown', function(e) {
                // Enterキーで次のセルに移動
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    const nextCell = findNextCell(this);
                    if (nextCell) {
                        nextCell.focus();
                    }
                }
            });
            
            // テキスト入力をより確実にする
            cell.addEventListener('input', function() {
                // 入力内容を保持
                console.log('Cell input:', this.textContent);
            });
        });
        
        // テーブル構造要素の修正
        ['thead', 'tbody', 'tr'].forEach(function(tagName) {
            const elements = table.querySelectorAll(tagName);
            elements.forEach(function(element) {
                if (tagName === 'thead') element.style.display = 'table-header-group';
                if (tagName === 'tbody') element.style.display = 'table-row-group';
                if (tagName === 'tr') element.style.display = 'table-row';
            });
        });
    });
    
    console.log('Fixed', tables.length, 'tables for editing');
}

// 次のセルを見つける関数
function findNextCell(currentCell) {
    const table = currentCell.closest('table');
    if (!table) return null;
    
    const cells = Array.from(table.querySelectorAll('th, td'));
    const currentIndex = cells.indexOf(currentCell);
    
    return cells[currentIndex + 1] || cells[0]; // 次のセル、または最初に戻る
}

// 手動変換ボタンを追加
function addConvertButton() {
    setTimeout(function() {
        const editors = document.querySelectorAll('.fi-fo-rich-editor');
        
        editors.forEach(function(editor) {
            if (editor.querySelector('.table-convert-button')) return;
            
            const button = document.createElement('button');
            button.className = 'table-convert-button';
            button.innerHTML = '🔄 テーブル変換';
            button.type = 'button';
            
            button.style.cssText = `
                position: absolute;
                top: 10px;
                right: 10px;
                z-index: 9999;
                background: #059669;
                color: white;
                border: none;
                padding: 8px 16px;
                border-radius: 6px;
                font-size: 12px;
                font-weight: bold;
                cursor: pointer;
                box-shadow: 0 2px 8px rgba(0,0,0,0.2);
                transition: all 0.2s;
            `;
            
            button.addEventListener('mouseenter', function() {
                this.style.background = '#047857';
                this.style.transform = 'scale(1.05)';
            });
            
            button.addEventListener('mouseleave', function() {
                this.style.background = '#059669';
                this.style.transform = 'scale(1)';
            });
            
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                console.log('Manual conversion triggered');
                this.innerHTML = '⏳ 変換中...';
                this.style.background = '#f59e0b';
                
                setTimeout(() => {
                    convertAllTextTables();
                    this.innerHTML = '✅ 完了!';
                    this.style.background = '#10b981';
                    
                    setTimeout(() => {
                        this.innerHTML = '🔄 テーブル変換';
                        this.style.background = '#059669';
                    }, 2000);
                }, 100);
            });
            
            editor.style.position = 'relative';
            editor.appendChild(button);
        });
    }, 1000);
}

// HTMLテーブルの表示を強化する関数
function enhanceHTMLTables() {
    console.log('Enhancing HTML tables...');
    
    const editors = document.querySelectorAll('.fi-fo-rich-editor, trix-editor');
    
    editors.forEach(function(editor) {
        // HTMLテーブルを検索
        const tables = editor.querySelectorAll('table');
        
        tables.forEach(function(table) {
            // テーブルの表示を強制
            table.style.cssText = `
                display: table !important;
                width: 100% !important;
                border-collapse: collapse !important;
                margin: 20px auto !important;
                border: 1px solid #e5e7eb !important;
                font-size: 14px !important;
                background: white !important;
                visibility: visible !important;
                opacity: 1 !important;
            `;
            
            // 全てのセルの表示を強制
            const cells = table.querySelectorAll('th, td');
            cells.forEach(function(cell) {
                cell.style.cssText = `
                    display: table-cell !important;
                    border: 1px solid #e5e7eb !important;
                    padding: 12px !important;
                    text-align: left !important;
                    vertical-align: top !important;
                    cursor: text !important;
                    background: white !important;
                `;
                
                // ヘッダーセルの背景色
                if (cell.tagName === 'TH') {
                    if (cell.closest('thead')) {
                        cell.style.backgroundColor = '#f3f4f6';
                    } else {
                        cell.style.backgroundColor = '#f9fafb';
                    }
                    cell.style.fontWeight = 'bold';
                }
                
                // contenteditable属性を確認・追加
                if (!cell.hasAttribute('contenteditable')) {
                    cell.setAttribute('contenteditable', 'true');
                }
                
                // フォーカス・ホバーイベント
                if (!cell.hasAttribute('data-enhanced')) {
                    cell.addEventListener('focus', function() {
                        this.style.outline = '2px solid #3b82f6';
                        this.style.boxShadow = '0 0 0 1px #3b82f6';
                    });
                    
                    cell.addEventListener('blur', function() {
                        this.style.outline = 'none';
                        this.style.boxShadow = 'none';
                    });
                    
                    cell.addEventListener('mouseenter', function() {
                        if (this !== document.activeElement) {
                            this.style.backgroundColor = '#eff6ff';
                        }
                    });
                    
                    cell.addEventListener('mouseleave', function() {
                        if (this !== document.activeElement) {
                            if (this.tagName === 'TH') {
                                if (this.closest('thead')) {
                                    this.style.backgroundColor = '#f3f4f6';
                                } else {
                                    this.style.backgroundColor = '#f9fafb';
                                }
                            } else {
                                this.style.backgroundColor = 'white';
                            }
                        }
                    });
                    
                    cell.setAttribute('data-enhanced', 'true');
                }
            });
            
            // テーブル構造要素の表示強制
            const thead = table.querySelector('thead');
            const tbody = table.querySelector('tbody');
            const rows = table.querySelectorAll('tr');
            
            if (thead) thead.style.display = 'table-header-group';
            if (tbody) tbody.style.display = 'table-row-group';
            rows.forEach(function(row) {
                row.style.display = 'table-row';
            });
        });
        
        console.log('Enhanced', tables.length, 'HTML tables');
    });
}

// 強制的にテーブルを表示する関数
function forceTableDisplay() {
    console.log('Force table display triggered');
    
    const editors = document.querySelectorAll('.fi-fo-rich-editor, trix-editor');
    
    editors.forEach(function(editor) {
        const tables = editor.querySelectorAll('table');
        
        tables.forEach(function(table) {
            // 最強制スタイル適用
            table.style.setProperty('display', 'table', 'important');
            table.style.setProperty('width', '100%', 'important');
            table.style.setProperty('border-collapse', 'collapse', 'important');
            table.style.setProperty('margin', '20px 0', 'important');
            table.style.setProperty('border', '2px solid #d1d5db', 'important');
            table.style.setProperty('background', 'white', 'important');
            table.style.setProperty('visibility', 'visible', 'important');
            table.style.setProperty('opacity', '1', 'important');
            table.style.setProperty('position', 'relative', 'important');
            table.style.setProperty('z-index', '10', 'important');
            
            // すべてのセルを強制表示
            const allCells = table.querySelectorAll('th, td');
            allCells.forEach(function(cell) {
                cell.style.setProperty('display', 'table-cell', 'important');
                cell.style.setProperty('border', '1px solid #9ca3af', 'important');
                cell.style.setProperty('padding', '12px', 'important');
                cell.style.setProperty('text-align', 'left', 'important');
                cell.style.setProperty('cursor', 'text', 'important');
                cell.style.setProperty('background', 'white', 'important');
                
                // contenteditable追加
                if (!cell.hasAttribute('contenteditable')) {
                    cell.setAttribute('contenteditable', 'true');
                }
                
                // ヘッダーセルの背景
                if (cell.tagName === 'TH') {
                    if (cell.closest('thead')) {
                        cell.style.setProperty('background-color', '#f3f4f6', 'important');
                    } else {
                        cell.style.setProperty('background-color', '#f9fafb', 'important');
                    }
                    cell.style.setProperty('font-weight', 'bold', 'important');
                }
            });
            
            // テーブル構造要素も強制表示
            const thead = table.querySelector('thead');
            const tbody = table.querySelector('tbody');
            const rows = table.querySelectorAll('tr');
            
            if (thead) thead.style.setProperty('display', 'table-header-group', 'important');
            if (tbody) tbody.style.setProperty('display', 'table-row-group', 'important');
            rows.forEach(function(row) {
                row.style.setProperty('display', 'table-row', 'important');
            });
        });
    });
    
    console.log('Force table display completed');
}

// グローバルな修正関数（コンソールから実行可能）
window.forceTableConversion = function() {
    console.log('Force HTML table enhancement triggered');
    enhanceHTMLTables();
    forceTableDisplay();
};