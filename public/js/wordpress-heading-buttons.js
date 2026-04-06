// WordPress風H2/H3ボタンをTinyMCEに追加
(function() {
    'use strict';
    
    // TinyMCEが読み込まれるまで待機
    function waitForTinyMCE() {
        if (typeof tinymce !== 'undefined') {
            setupHeadingButtons();
        } else {
            setTimeout(waitForTinyMCE, 100);
        }
    }
    
    function setupHeadingButtons() {
        // 新しいエディタインスタンス用のセットアップ
        tinymce.on('SetupEditor', function(e) {
            const editor = e.editor;
            addHeadingButtons(editor);
        });
        
        // 既存のエディタインスタンスにも適用
        if (tinymce.editors) {
            tinymce.editors.forEach(function(editor) {
                if (editor.initialized) {
                    addHeadingButtons(editor);
                }
            });
        }
    }
    
    function addHeadingButtons(editor) {
        // H2ボタンを登録
        editor.ui.registry.addButton('h2button', {
            text: 'H2',
            tooltip: '見出し2',
            onAction: function() {
                editor.execCommand('FormatBlock', false, 'h2');
            }
        });
        
        // H3ボタンを登録
        editor.ui.registry.addButton('h3button', {
            text: 'H3',
            tooltip: '見出し3',
            onAction: function() {
                editor.execCommand('FormatBlock', false, 'h3');
            }
        });
        
        // H4ボタンを登録
        editor.ui.registry.addButton('h4button', {
            text: 'H4',
            tooltip: '見出し4',
            onAction: function() {
                editor.execCommand('FormatBlock', false, 'h4');
            }
        });
    }
    
    // ページ読み込み後に実行
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', waitForTinyMCE);
    } else {
        waitForTinyMCE();
    }
})();