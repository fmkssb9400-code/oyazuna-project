// IME安全なTiptapEditor - 完全版

// Alpine.js用のIMEコンテンツエディタコンポーネント
function imeContentEditor() {
    return {
        isComposing: false,
        editorInstance: null,
        originalContent: '',
        
        initContentEditor() {
            this.$nextTick(() => {
                this.setupIMESafeEditor();
                this.handleContentInsertion();
            });
        },
        
        setupIMESafeEditor() {
            const prosemirror = this.$el.querySelector('.ProseMirror');
            if (!prosemirror) return;
            
            this.editorInstance = prosemirror;
            
            // 日本語IME最適化設定
            prosemirror.setAttribute('lang', 'ja');
            prosemirror.setAttribute('inputmode', 'text');
            prosemirror.style.imeMode = 'active';
            prosemirror.style.webkitImeMode = 'active';
            
            // IME変換開始 - content エディタのみに限定した安全な処理
            prosemirror.addEventListener('compositionstart', (e) => {
                this.isComposing = true;
                prosemirror.setAttribute('data-ime-composing', 'true');
                
                // IME変換中であることをグローバルに通知
                if (window.globalIMEState) {
                    window.globalIMEState.isComposing = true;
                    window.globalIMEState.activeEditor = this.editorInstance;
                }
            });
            
            // IME変換終了 - 手動同期の実行
            prosemirror.addEventListener('compositionend', (e) => {
                this.isComposing = false;
                prosemirror.removeAttribute('data-ime-composing');
                
                // IME変換終了をグローバルに通知
                if (window.globalIMEState) {
                    window.globalIMEState.isComposing = false;
                    window.globalIMEState.activeEditor = null;
                }
                
                // 変換確定後に手動同期を実行
                setTimeout(() => {
                    this.syncContentToWire();
                }, 50); // 短い遅延で確実に実行
            });
            
            // 保存時のみコンテンツを同期
            document.addEventListener('livewire:before-dom-update', () => {
                if (!this.isComposing) {
                    this.syncContentToWire();
                }
            });
        },
        
        // wire:ignoreされたエディタの内容を手動同期
        syncContentToWire() {
            if (this.editorInstance && this.$wire) {
                const editorContent = this.getEditorContent();
                this.$wire.set('data.content', editorContent, false); // defer
            }
        },
        
        getEditorContent() {
            // TiptapEditorのコンテンツを取得
            const editor = this.$el.querySelector('[data-tiptap-editor]');
            if (editor && editor.__tiptapEditor) {
                return editor.__tiptapEditor.getHTML();
            }
            return this.editorInstance.innerHTML;
        },
        
        // CTAボタンや比較表の挿入処理
        handleContentInsertion() {
            // セッションからの挿入コンテンツをチェック
            const insertData = this.getSessionInsertData();
            if (insertData) {
                this.insertContent(insertData.content);
                this.clearSessionInsertData();
            }
        },
        
        insertContent(content) {
            if (!this.editorInstance) return;
            
            // TiptapEditorのcommands APIを使用
            const editor = this.$el.querySelector('[data-tiptap-editor]');
            if (editor && editor.__tiptapEditor) {
                editor.__tiptapEditor.commands.insertContent(content);
            } else {
                // フォールバック: 直接HTML挿入
                const selection = window.getSelection();
                if (selection.rangeCount > 0) {
                    const range = selection.getRangeAt(0);
                    const node = document.createElement('div');
                    node.innerHTML = content;
                    range.insertNode(node);
                    range.collapse(false);
                }
            }
            
            // 挿入後に同期
            this.syncContentToWire();
        },
        
        getSessionInsertData() {
            // Laravel sessionからデータを取得（実装は環境に依存）
            return null; // プレースホルダー
        },
        
        clearSessionInsertData() {
            // セッションデータをクリア
        }
    };
}

// グローバルにAlpine.jsコンポーネントを登録
document.addEventListener('alpine:init', () => {
    Alpine.data('imeContentEditor', imeContentEditor);
});

// DOM読み込み完了時の初期化
document.addEventListener('DOMContentLoaded', function() {
    // IME状態のグローバル管理
    window.globalIMEState = {
        isComposing: false,
        activeEditor: null
    };
    
    // 保存操作の最適化
    document.addEventListener('keydown', function(e) {
        // Ctrl+S での保存時は強制同期
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            syncAllEditorsBeforeSave();
        }
    });
});

// 保存前にすべてのエディタを同期
function syncAllEditorsBeforeSave() {
    const editors = document.querySelectorAll('.ime-safe-content-editor');
    editors.forEach(editorEl => {
        const alpineData = Alpine.$data(editorEl);
        if (alpineData && typeof alpineData.syncContentToWire === 'function') {
            alpineData.syncContentToWire();
        }
    });
    
    // 同期完了後に保存実行
    setTimeout(() => {
        const saveButton = document.querySelector('[wire\\:click*="save"]');
        if (saveButton) {
            saveButton.click();
        }
    }, 100);
}