document.addEventListener('DOMContentLoaded', function() {
    // TinyMCE初期化後にヘッドボタンを追加
    if (typeof tinymce !== 'undefined') {
        tinymce.on('SetupEditor', function(e) {
            const editor = e.editor;
            
            // H2ボタンを追加
            editor.ui.registry.addButton('heading2', {
                text: 'H2',
                tooltip: '見出し2',
                onAction: function() {
                    editor.execCommand('FormatBlock', false, 'h2');
                }
            });
            
            // H3ボタンを追加  
            editor.ui.registry.addButton('heading3', {
                text: 'H3',
                tooltip: '見出し3',
                onAction: function() {
                    editor.execCommand('FormatBlock', false, 'h3');
                }
            });
        });
    }
});