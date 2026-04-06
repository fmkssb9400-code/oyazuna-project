window.setupTinyMCE = function(editor) {
    console.log('TinyMCE setup started');
    
    // H2, H3ボタンを個別に追加
    editor.ui.registry.addButton('h2', {
        text: 'H2',
        tooltip: 'Heading 2',
        onAction: function () {
            editor.execCommand('FormatBlock', false, 'h2');
        }
    });
    
    editor.ui.registry.addButton('h3', {
        text: 'H3', 
        tooltip: 'Heading 3',
        onAction: function () {
            editor.execCommand('FormatBlock', false, 'h3');
        }
    });
    
    console.log('TinyMCE setup completed');
};