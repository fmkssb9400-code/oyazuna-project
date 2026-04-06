// Filament Rich Editor マーカー機能
document.addEventListener('DOMContentLoaded', function() {
    // マーカーボタンを追加する関数
    function addMarkerButton() {
        const editors = document.querySelectorAll('[data-enable-marker="true"] .trix-editor');
        
        editors.forEach(editor => {
            if (editor.hasAttribute('data-marker-initialized')) {
                return;
            }
            
            const toolbar = editor.previousElementSibling;
            if (!toolbar || !toolbar.classList.contains('trix-toolbar')) {
                return;
            }
            
            // マーカーボタンを作成
            const markerButton = document.createElement('button');
            markerButton.type = 'button';
            markerButton.className = 'trix-button trix-button--icon trix-button--icon-marker';
            markerButton.title = 'マーカー';
            markerButton.innerHTML = '🖍️';
            
            // マーカー機能の実装
            markerButton.addEventListener('click', function(e) {
                e.preventDefault();
                
                const selection = window.getSelection();
                if (selection.rangeCount === 0 || selection.isCollapsed) {
                    alert('マーカーを引きたいテキストを選択してください。');
                    return;
                }
                
                const range = selection.getRangeAt(0);
                const selectedText = range.toString();
                
                // マークタグで囲む
                const markElement = document.createElement('mark');
                markElement.className = 'marker';
                markElement.textContent = selectedText;
                
                try {
                    range.deleteContents();
                    range.insertNode(markElement);
                    selection.removeAllRanges();
                    
                    // Trixに変更を通知
                    editor.dispatchEvent(new Event('input', { bubbles: true }));
                } catch (error) {
                    console.error('マーカー挿入エラー:', error);
                }
            });
            
            // ツールバーにボタンを追加
            const buttonGroup = toolbar.querySelector('.trix-button-group');
            if (buttonGroup) {
                buttonGroup.appendChild(markerButton);
            }
            
            editor.setAttribute('data-marker-initialized', 'true');
        });
    }
    
    // 初期化
    addMarkerButton();
    
    // Livewireの更新後にも対応
    document.addEventListener('livewire:updated', addMarkerButton);
    
    // MutationObserverで動的な変更も監視
    const observer = new MutationObserver(function(mutations) {
        let shouldCheck = false;
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length > 0) {
                shouldCheck = true;
            }
        });
        
        if (shouldCheck) {
            setTimeout(addMarkerButton, 100);
        }
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
});