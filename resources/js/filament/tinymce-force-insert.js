/**
 * TinyMCE 画像挿入強制実行版
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 TinyMCE 強制画像挿入版 開始');
    
    const setupForceInsertTinyMCE = function() {
        if (typeof tinymce === 'undefined') {
            setTimeout(setupForceInsertTinyMCE, 500);
            return;
        }
        
        console.log('✅ TinyMCE検出 - 強制挿入設定適用中');
        
        const originalInit = tinymce.init;
        tinymce.init = function(config) {
            console.log('🔧 TinyMCE初期化 - 強制挿入版');
            
            const forceConfig = Object.assign({}, config, {
                height: 400,
                menubar: true,
                branding: false,
                language: 'ja',
                
                plugins: 'lists link image table code preview emoticons fullscreen insertdatetime searchreplace directionality paste textcolor colorpicker textpattern imagetools nonbreaking',
                toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image table | code preview',
                
                // RTL修正
                directionality: 'ltr',
                content_style: `
                    body { 
                        direction: ltr !important; 
                        text-align: left !important; 
                        font-family: Arial, sans-serif; 
                        margin: 16px; 
                    }
                    * { direction: ltr !important; text-align: left !important; }
                `,
                
                // 画像アップロード設定（標準のを無効化）
                images_upload_url: false,
                automatic_uploads: false,
                paste_data_images: false,
                image_uploadtab: true,
                file_picker_types: 'image',
                
                // カスタムファイルピッカー（強制挿入版）
                file_picker_callback: function(callback, value, meta) {
                    console.log('🎯 ファイルピッカー呼び出し:', meta.filetype);
                    
                    if (meta.filetype === 'image') {
                        // ファイル選択input作成
                        const input = document.createElement('input');
                        input.setAttribute('type', 'file');
                        input.setAttribute('accept', 'image/*');
                        
                        input.onchange = function() {
                            const file = this.files[0];
                            console.log('📁 ファイル選択:', file.name);
                            
                            if (file) {
                                // 強制アップロード実行
                                forceUploadAndInsert(file);
                            }
                        };
                        
                        input.click();
                    }
                },
                
                setup: function(editor) {
                    console.log('🔧 エディターセットアップ (強制版):', editor.id);
                    
                    // エディター初期化完了時
                    editor.on('init', function() {
                        console.log('🎨 エディター初期化完了:', editor.id);
                        
                        // 5秒後に画像ボタンの動作をカスタマイズ
                        setTimeout(function() {
                            customizeImageButton(editor);
                        }, 2000);
                    });
                    
                    // 画像挿入テスト関数をグローバルに追加
                    window.testImageInsert = function(imageUrl) {
                        console.log('🧪 テスト画像挿入:', imageUrl);
                        forceInsertImage(editor, imageUrl || 'https://via.placeholder.com/300x200');
                    };
                }
            });
            
            console.log('🔧 強制挿入設定完了');
            return originalInit.call(this, forceConfig);
        };
        
        // 強制アップロード関数
        function forceUploadAndInsert(file) {
            console.log('📤 強制アップロード開始:', file.name);
            
            const formData = new FormData();
            formData.append('file', file);
            
            const token = document.querySelector('meta[name="csrf-token"]');
            if (!token) {
                alert('CSRF token not found');
                return;
            }
            
            // アップロード処理
            fetch('/admin/editor/upload-image', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': token.getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('📥 アップロードレスポンス:', data);
                
                if (data.location) {
                    console.log('✅ アップロード成功 - 強制挿入実行:', data.location);
                    
                    // すべてのTinyMCEエディターに画像を挿入
                    Object.keys(tinymce.editors).forEach(editorId => {
                        const editor = tinymce.editors[editorId];
                        if (editor && !editor.destroyed) {
                            forceInsertImage(editor, data.location);
                        }
                    });
                } else {
                    alert('アップロード失敗: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('❌ アップロードエラー:', error);
                alert('アップロードエラー: ' + error.message);
            });
        }
        
        // 強制画像挿入関数
        function forceInsertImage(editor, imageUrl) {
            console.log('🖼️ 強制画像挿入開始:', imageUrl);
            
            try {
                // 方法1: insertContent
                const imgHtml = `<p><img src="${imageUrl}" alt="アップロード画像" style="max-width: 600px; height: auto;" /></p>`;
                editor.insertContent(imgHtml);
                console.log('✅ insertContent実行');
                
                // 方法2: DOM直接操作（バックアップ）
                setTimeout(function() {
                    const body = editor.getBody();
                    if (body) {
                        const img = editor.dom.create('img', {
                            src: imageUrl,
                            alt: 'アップロード画像',
                            style: 'max-width: 600px; height: auto; display: block; margin: 10px 0;'
                        });
                        
                        const p = editor.dom.create('p');
                        p.appendChild(img);
                        body.appendChild(p);
                        
                        console.log('✅ DOM直接挿入実行');
                        
                        // エディターに変更を通知
                        editor.nodeChanged();
                        editor.fire('change');
                    }
                }, 100);
                
                // 方法3: setContent（最終手段）
                setTimeout(function() {
                    const currentContent = editor.getContent();
                    const newContent = currentContent + `<p><img src="${imageUrl}" alt="アップロード画像" style="max-width: 600px; height: auto;" /></p>`;
                    editor.setContent(newContent);
                    console.log('✅ setContent実行');
                }, 200);
                
            } catch (error) {
                console.error('❌ 画像挿入エラー:', error);
            }
        }
        
        // 画像ボタンのカスタマイズ
        function customizeImageButton(editor) {
            console.log('🔧 画像ボタンカスタマイズ開始');
            
            // 画像ボタンを探す
            const imageButtons = document.querySelectorAll('[aria-label*="画像"], [aria-label*="Image"], [title*="画像"], [title*="Image"]');
            console.log('🔍 画像ボタン検索結果:', imageButtons.length);
            
            imageButtons.forEach((btn, index) => {
                if (!btn.hasAttribute('data-custom-upload')) {
                    console.log(`🔄 ボタン ${index + 1} をカスタマイズ中`);
                    
                    btn.setAttribute('data-custom-upload', 'true');
                    
                    // 既存イベントを削除
                    const newBtn = btn.cloneNode(true);
                    btn.parentNode.replaceChild(newBtn, btn);
                    
                    // カスタムクリックイベント追加
                    newBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        console.log('📷 カスタム画像ボタンクリック');
                        
                        const input = document.createElement('input');
                        input.type = 'file';
                        input.accept = 'image/*';
                        
                        input.onchange = function() {
                            const file = this.files[0];
                            if (file) {
                                console.log('📁 ファイル選択 (カスタムボタン):', file.name);
                                forceUploadAndInsert(file);
                            }
                        };
                        
                        input.click();
                    });
                    
                    // 視覚的にカスタム化されたことを示す
                    newBtn.style.border = '2px solid #ff6b6b';
                    newBtn.style.borderRadius = '3px';
                    newBtn.title = 'カスタム画像アップロード (強制挿入版)';
                    
                    console.log(`✅ ボタン ${index + 1} カスタマイズ完了`);
                }
            });
        }
    };
    
    setupForceInsertTinyMCE();
    setTimeout(setupForceInsertTinyMCE, 3000);
    
    // テスト用コンソールコマンド追加
    window.testTinyMCEImageInsert = function(url) {
        const testUrl = url || 'https://via.placeholder.com/400x300/ff6b6b/ffffff?text=TEST+IMAGE';
        console.log('🧪 テスト画像挿入実行:', testUrl);
        
        Object.keys(tinymce.editors).forEach(editorId => {
            const editor = tinymce.editors[editorId];
            if (editor && !editor.destroyed) {
                const imgHtml = `<p><img src="${testUrl}" alt="テスト画像" style="max-width: 400px; height: auto; border: 3px solid #ff6b6b;" /></p>`;
                editor.insertContent(imgHtml);
                console.log('✅ テスト画像挿入完了:', editorId);
            }
        });
    };
    
    console.log('🎮 テストコマンド: window.testTinyMCEImageInsert() でテスト画像を挿入できます');
});

// 即座にスタイル修正も適用
(function() {
    const style = document.createElement('style');
    style.textContent = `
        .tox-tinymce { direction: ltr !important; text-align: left !important; }
        .tox-edit-area { direction: ltr !important; text-align: left !important; }
        .fi-fo-rich-editor { direction: ltr !important; text-align: left !important; }
    `;
    document.head.appendChild(style);
})();