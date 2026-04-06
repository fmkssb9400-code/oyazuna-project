<script>
document.addEventListener('DOMContentLoaded', function() {
    // Livewireでコンテンツ挿入イベントをリスンする
    window.addEventListener('insert-content', function(event) {
        insertContentToEditor(event.detail.content);
    });
});

function insertContentToEditor(content) {
    // Tiptapエディタのインスタンスを取得を試行
    const editorElement = document.querySelector('[data-tiptap]');
    
    if (editorElement && editorElement.__tiptapEditor) {
        // Tiptapエディタが利用可能な場合
        const editor = editorElement.__tiptapEditor;
        editor.commands.insertContent(content);
        
        // 成功メッセージを表示
        showInsertionMessage('コンテンツがエディタに挿入されました。');
        
    } else {
        // フォールバック：テキストエリアに挿入またはクリップボードにコピー
        fallbackContentInsertion(content);
    }
}

function fallbackContentInsertion(content) {
    // Filament Rich Editorの隠されたテキストエリアを探す
    const hiddenTextarea = document.querySelector('textarea[wire\\:model="data.content"]') ||
                          document.querySelector('input[name="content"]') ||
                          document.querySelector('textarea[name="content"]');
    
    if (hiddenTextarea) {
        // 現在の値の最後に追加
        const currentValue = hiddenTextarea.value || '';
        hiddenTextarea.value = currentValue + '\n' + content;
        
        // Livewire更新をトリガー
        if (hiddenTextarea.hasAttribute('wire:model')) {
            hiddenTextarea.dispatchEvent(new Event('input', { bubbles: true }));
        }
        
        showInsertionMessage('コンテンツが追加されました。');
    } else {
        // 最終手段：クリップボードにコピー
        copyToClipboard(content);
        showInsertionMessage('コンテンツをクリップボードにコピーしました。エディタに貼り付けてください。');
    }
}

function copyToClipboard(text) {
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text);
    } else {
        // フォールバック方法
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            document.execCommand('copy');
        } catch (err) {
            console.warn('クリップボードへのコピーに失敗しました:', err);
        }
        document.body.removeChild(textArea);
    }
}

function showInsertionMessage(message) {
    // Filamentの通知システムを使用（利用可能な場合）
    if (window.$wireUI && window.$wireUI.notify) {
        window.$wireUI.notify({
            title: '成功',
            description: message,
            icon: 'success'
        });
    } else if (window.dispatchEvent) {
        // カスタム通知イベントを発火
        window.dispatchEvent(new CustomEvent('notify', {
            detail: { message: message, type: 'success' }
        }));
    } else {
        // シンプルなアラート
        alert(message);
    }
}
</script>