// 標準的なH2/H3見出し機能
window.setupTinyMCEHeadings = function(editor) {
    // H2ボタンを追加
    editor.ui.registry.addButton('heading2', {
        text: 'H2',
        tooltip: 'カーソル位置の行を見出し2にする',
        onAction: function() {
            editor.execCommand('FormatBlock', false, 'h2');
        }
    });
    
    // H3ボタンを追加
    editor.ui.registry.addButton('heading3', {
        text: 'H3', 
        tooltip: 'カーソル位置の行を見出し3にする',
        onAction: function() {
            editor.execCommand('FormatBlock', false, 'h3');
        }
    });
};