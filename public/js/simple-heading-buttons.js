// 最もシンプルで確実なH2/H3ボタン実装
document.addEventListener('DOMContentLoaded', function() {
    // TinyMCEの初期化を待つ
    let attempts = 0;
    const maxAttempts = 50; // 5秒間試行
    
    function addHeadingButtons() {
        attempts++;
        
        if (typeof tinymce === 'undefined') {
            if (attempts < maxAttempts) {
                setTimeout(addHeadingButtons, 100);
            }
            return;
        }
        
        // 全てのTinyMCEエディタインスタンスを対象
        function setupEditor(editor) {
            // H2ボタンを追加
            if (!editor.ui.registry.getAll().buttons['custom_h2']) {
                editor.ui.registry.addButton('custom_h2', {
                    text: 'H2',
                    tooltip: 'カーソル位置を見出し2にする',
                    onAction: function() {
                        editor.execCommand('formatBlock', false, 'h2');
                    }
                });
            }
            
            // H3ボタンを追加
            if (!editor.ui.registry.getAll().buttons['custom_h3']) {
                editor.ui.registry.addButton('custom_h3', {
                    text: 'H3',
                    tooltip: 'カーソル位置を見出し3にする',
                    onAction: function() {
                        editor.execCommand('formatBlock', false, 'h3');
                    }
                });
            }
            
            console.log('H2/H3ボタンを追加しました:', editor.id);
        }
        
        // 新しいエディタが追加される度に実行
        tinymce.on('AddEditor', function(e) {
            const editor = e.editor;
            editor.on('init', function() {
                setupEditor(editor);
            });
        });
        
        // 既存のエディタにも適用
        for (let editor of tinymce.editors) {
            if (editor.initialized) {
                setupEditor(editor);
            } else {
                editor.on('init', function() {
                    setupEditor(editor);
                });
            }
        }
        
        console.log('H2/H3ボタンセットアップ完了');
    }
    
    addHeadingButtons();
});