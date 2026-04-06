// TinyMCE用のCTA/比較表挿入機能とIME最適化

// TinyMCE初期化の追加設定
window.tinymceConfig = {
    height: 400,
    menubar: true,
    plugins: 'lists link image table code preview media emoticons fullscreen insertdatetime searchreplace directionality paste textcolor colorpicker textpattern imagetools nonbreaking',
    toolbar: 'undo redo | formatselect | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image table media | insertCTA insertTable | code preview fullscreen',
    language: 'ja',
    branding: false,
    block_formats: '段落=p; 見出し1=h1; 見出し2=h2; 見出し3=h3; 見出し4=h4; 見出し5=h5; 見出し6=h6; プリフォーマット=pre',
    paste_data_images: true,
    automatic_uploads: true,
    images_reuse_filename: true,
    images_upload_url: '/admin/upload-image',
    images_upload_credentials: true,
    convert_urls: false,
    relative_urls: false,
    
    // 重要: アップロードタブを有効化
    image_uploadtab: true,
    file_picker_types: 'image',
    images_upload_handler: function (blobInfo, progress) {
        return new Promise(function(resolve, reject) {
            var xhr = new XMLHttpRequest();
            var formData = new FormData();
            
            xhr.withCredentials = true;
            xhr.open('POST', '/admin/upload-image');
            
            // CSRF token (必須)
            var token = document.querySelector('meta[name="csrf-token"]');
            if (token) {
                xhr.setRequestHeader('X-CSRF-TOKEN', token.getAttribute('content'));
            } else {
                reject('CSRF token not found');
                return;
            }
            
            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable && typeof progress === 'function') {
                    progress(e.loaded / e.total * 100);
                }
            };
            
            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        var json = JSON.parse(xhr.responseText);
                        if (json.location) {
                            resolve(json.location);
                        } else if (json.error) {
                            reject(json.error);
                        } else {
                            reject('Invalid response format');
                        }
                    } catch (e) {
                        reject('JSON parse error: ' + e.message);
                    }
                } else {
                    reject('HTTP Error: ' + xhr.status + ' - ' + xhr.responseText);
                }
            };
            
            xhr.onerror = function() {
                reject('Network error occurred');
            };
            
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            xhr.send(formData);
        });
    },
    contextmenu: false,
    directionality: 'ltr',
    image_title: true,
    image_description: false,
    image_dimensions: false,
    image_advtab: true,
    image_uploadtab: true,
    images_file_types: 'jpg,jpeg,png,gif,webp',
    images_upload_base_path: '/storage/articles/',
    file_picker_types: 'image',
    images_dataimg_filter: function(img) {
        return img.hasAttribute('data-mce-src');
    },
    content_style: `
        body { 
            font-family: "Hiragino Sans", "Yu Gothic UI", "Meiryo UI", sans-serif !important; 
            direction: ltr !important; 
            text-align: left !important;
            ime-mode: active !important;
            -webkit-ime-mode: active !important;
            -moz-ime-mode: active !important;
        }
        * { text-align: left !important; direction: ltr !important; }
        p, div, h1, h2, h3, h4, h5, h6, li, td, th { 
            text-align: left !important; 
            direction: ltr !important; 
        }
        h1 { font-size: 2em; font-weight: bold; margin: 0.67em 0; }
        h2 { font-size: 1.5em; font-weight: bold; margin: 0.75em 0; }
        h3 { font-size: 1.17em; font-weight: bold; margin: 0.83em 0; }
        h4 { font-size: 1em; font-weight: bold; margin: 1em 0; }
        h5 { font-size: 0.83em; font-weight: bold; margin: 1.17em 0; }
        h6 { font-size: 0.75em; font-weight: bold; margin: 1.33em 0; }
    `,
    table_default_styles: {
        'width': '100%',
        'border-collapse': 'collapse',
        'border': '1px solid #e5e7eb'
    },
    setup: function(editor) {
        // 初期化時のIME設定と左揃え強制
        editor.on('init', function() {
            var body = editor.getBody();
            
            // IME設定
            body.style.imeMode = 'active';
            body.style.webkitImeMode = 'active';
            body.style.mozImeMode = 'active';
            body.style.fontFamily = '"Hiragino Sans", "Yu Gothic UI", "Meiryo UI", sans-serif';
            body.style.direction = 'ltr';
            body.style.textAlign = 'left';
            body.setAttribute('lang', 'ja');
            body.setAttribute('dir', 'ltr');
            
            // 全要素の左揃え強制
            function forceLeftAlign() {
                const allElements = body.querySelectorAll('*');
                allElements.forEach(el => {
                    if (el.style) {
                        el.style.textAlign = 'left';
                        el.style.direction = 'ltr';
                    }
                    el.setAttribute('dir', 'ltr');
                });
                
                // body自体も再設定
                body.style.textAlign = 'left';
                body.style.direction = 'ltr';
            }
            
            // 初回実行
            forceLeftAlign();
            
            // コンテンツロード後にも実行
            setTimeout(forceLeftAlign, 100);
            setTimeout(forceLeftAlign, 500);
        });
        
        
        // CTA挿入ボタン追加
        editor.ui.registry.addButton('insertCTA', {
            text: 'CTA',
            tooltip: 'CTAボタン挿入',
            onAction: function() {
                window.insertCTAContent(editor);
            }
        });
        
        // 比較表挿入ボタン追加
        editor.ui.registry.addButton('insertTable', {
            text: '比較表',
            tooltip: '比較表テンプレート挿入',
            onAction: function() {
                window.insertCompareTable(editor);
            }
        });
        
        // 画像アップロードボタン追加
        editor.ui.registry.addButton('uploadImage', {
            text: '画像',
            tooltip: '画像アップロード',
            onAction: function() {
                window.showUploadForm(editor);
            }
        });
        
        // 標準のimageボタンも完全にオーバーライド
        editor.ui.registry.addButton('image', {
            text: '画像',
            tooltip: '画像アップロード',
            onAction: function() {
                window.showUploadForm(editor);
            }
        });
        
        // IME入力中の保護
        editor.on('compositionstart', function() {
            editor.getBody().setAttribute('data-ime-composing', 'true');
        });
        
        editor.on('compositionend', function() {
            editor.getBody().removeAttribute('data-ime-composing');
        });
        
        // 新しいコンテンツが追加された時の左揃え強制
        editor.on('NodeChange', function() {
            var body = editor.getBody();
            
            // body自体の設定
            if (body.style.textAlign !== 'left') {
                body.style.textAlign = 'left';
                body.style.direction = 'ltr';
                body.setAttribute('dir', 'ltr');
            }
            
            // 新しく追加された要素の左揃え
            const allElements = body.querySelectorAll('*');
            allElements.forEach(el => {
                if (el.style.textAlign !== 'left' || el.style.direction !== 'ltr') {
                    el.style.textAlign = 'left';
                    el.style.direction = 'ltr';
                    el.setAttribute('dir', 'ltr');
                }
            });
        });
        
        // キーボード入力時の左揃え維持
        editor.on('KeyDown', function() {
            setTimeout(function() {
                var body = editor.getBody();
                body.style.textAlign = 'left';
                body.style.direction = 'ltr';
            }, 1);
        });
        
        // 画像ダイアログの設定確認
        editor.on('init', function() {
            console.log('TinyMCE initialized with image upload settings');
            console.log('Upload URL:', editor.settings.images_upload_url);
            console.log('Upload tab enabled:', editor.settings.image_uploadtab);
        });
    }
};


// CTAボタン挿入関数
window.insertCTAContent = function(editor) {
    // モーダルダイアログでCTAの詳細を入力
    editor.windowManager.open({
        title: 'CTAボタン挿入',
        body: {
            type: 'panel',
            items: [
                {
                    type: 'input',
                    name: 'button_text',
                    label: 'ボタン表示テキスト',
                    placeholder: '例: 今すぐ資料請求'
                },
                {
                    type: 'input',
                    name: 'button_url',
                    label: 'URL',
                    placeholder: 'https://example.com'
                },
                {
                    type: 'selectbox',
                    name: 'button_color',
                    label: '色',
                    items: [
                        { text: 'オレンジ', value: 'orange' },
                        { text: '青', value: 'blue' }
                    ]
                }
            ]
        },
        buttons: [
            {
                type: 'cancel',
                text: 'キャンセル'
            },
            {
                type: 'submit',
                text: '挿入',
                primary: true
            }
        ],
        onSubmit: function(api) {
            const data = api.getData();
            
            // バリデーション
            if (!data.button_text || !data.button_url || !data.button_color) {
                editor.notificationManager.open({
                    text: 'すべての項目を入力してください。',
                    type: 'error'
                });
                return;
            }
            
            // URLバリデーション
            if (!data.button_url.match(/^https?:\/\//)) {
                editor.notificationManager.open({
                    text: 'URLはhttp://またはhttps://で始まる必要があります。',
                    type: 'error'
                });
                return;
            }
            
            // CTAトークンを生成してエディタに挿入
            const ctaToken = `[[CTA|${data.button_color}|${data.button_url}|${data.button_text}]]`;
            editor.insertContent(`<p>${ctaToken}</p>`);
            
            api.close();
            
            editor.notificationManager.open({
                text: 'CTAボタンを挿入しました。',
                type: 'success'
            });
        }
    });
};

// 比較表テンプレート挿入関数
window.insertCompareTable = function(editor) {
    // 比較表のHTMLテンプレート
    const compareTableHTML = `
        <table class="custom-compare-table" style="width: 100%; border-collapse: collapse; border: 1px solid #e5e7eb; margin: 20px 0;">
            <thead>
                <tr style="background-color: #f3f4f6;">
                    <th style="border: 1px solid #e5e7eb; padding: 12px; font-weight: bold; text-align: left;">項目</th>
                    <th style="border: 1px solid #e5e7eb; padding: 12px; font-weight: bold; text-align: left;">比較1</th>
                    <th style="border: 1px solid #e5e7eb; padding: 12px; font-weight: bold; text-align: left;">比較2</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th style="border: 1px solid #e5e7eb; padding: 12px; font-weight: bold; text-align: left; background-color: #f9fafb;">項目1</th>
                    <td style="border: 1px solid #e5e7eb; padding: 12px;">データ</td>
                    <td style="border: 1px solid #e5e7eb; padding: 12px;">データ</td>
                </tr>
                <tr>
                    <th style="border: 1px solid #e5e7eb; padding: 12px; font-weight: bold; text-align: left; background-color: #f9fafb;">項目2</th>
                    <td style="border: 1px solid #e5e7eb; padding: 12px;">データ</td>
                    <td style="border: 1px solid #e5e7eb; padding: 12px;">データ</td>
                </tr>
                <tr>
                    <th style="border: 1px solid #e5e7eb; padding: 12px; font-weight: bold; text-align: left; background-color: #f9fafb;">項目3</th>
                    <td style="border: 1px solid #e5e7eb; padding: 12px;">データ</td>
                    <td style="border: 1px solid #e5e7eb; padding: 12px;">データ</td>
                </tr>
            </tbody>
        </table>
    `;
    
    // エディタに比較表を挿入
    editor.insertContent(compareTableHTML);
    
    editor.notificationManager.open({
        text: '比較表テンプレートを挿入しました。セルをクリックして編集できます。',
        type: 'success'
    });
};

// TinyMCE初期化後の追加設定
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, setting up TinyMCE configuration');
    
    // 強制的にTinyMCEの設定を上書きする関数
    const forceCustomTinyMCE = function() {
        console.log('🔧 強制的なTinyMCE設定開始');
        
        // 既存のTinyMCEインスタンスがあれば削除
        if (typeof tinymce !== 'undefined' && tinymce.editors) {
            Object.keys(tinymce.editors).forEach(editorId => {
                const editor = tinymce.editors[editorId];
                if (editor) {
                    editor.destroy();
                    console.log('🗑️ 既存エディター削除:', editorId);
                }
            });
        }
        
        // TinyMCE初期化を完全に上書き
        if (typeof tinymce !== 'undefined') {
            const originalInit = tinymce.init;
            
            tinymce.init = function(config) {
                console.log('🎯 TinyMCE.init完全上書き実行');
                console.log('📋 元の設定:', config);
                
                // 完全にカスタム設定を適用
                const customConfig = Object.assign({}, config, window.tinymceConfig);
                
                // 確実にカスタムボタンを含むツールバーを設定
                customConfig.toolbar = 'undo redo | formatselect | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image uploadImage table | insertCTA insertTable | code preview fullscreen';
                customConfig.plugins = 'lists link image table code preview emoticons fullscreen insertdatetime searchreplace directionality paste textcolor colorpicker textpattern imagetools nonbreaking';
                
                // 標準のimageダイアログを無効化
                customConfig.image_advtab = false;
                customConfig.image_title = false;
                customConfig.image_description = false;
                customConfig.image_dimensions = false;
                
                console.log('🔧 カスタム設定適用完了:', customConfig);
                console.log('🔧 ツールバー確認:', customConfig.toolbar);
                
                return originalInit.call(this, customConfig);
            };
            
            console.log('✅ TinyMCE.init上書き完了');
        }
    };
    
    // FilamentTinyEditorの設定を上書き
    const waitForFilamentTinyEditor = function() {
        if (typeof window.filamentTinyEditorOptions !== 'undefined') {
            console.log('📝 FilamentTinyEditorOptions検出 - 設定マージ');
            // 既存設定を完全に上書き
            Object.assign(window.filamentTinyEditorOptions, window.tinymceConfig);
            window.filamentTinyEditorOptions.toolbar = 'undo redo | formatselect | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link uploadImage table media | insertCTA insertTable | code preview fullscreen';
        } else if (typeof tinymce !== 'undefined') {
            console.log('🎯 TinyMCE検出 - 直接設定');
            forceCustomTinyMCE();
            
            // エディター追加時のフック
            tinymce.on('AddEditor', function(e) {
                const editor = e.editor;
                console.log('✨ エディター追加検出:', editor.id);
                
                editor.on('init', function() {
                    console.log('🎨 エディター初期化完了:', editor.id);
                    const body = editor.getBody();
                    if (body) {
                        // 日本語入力最適化
                        body.style.imeMode = 'active';
                        body.style.webkitImeMode = 'active';
                        body.style.fontFamily = '"Hiragino Sans", "Yu Gothic UI", "Meiryo UI", sans-serif';
                        body.setAttribute('lang', 'ja');
                    }
                });
            });
        } else {
            console.log('⏳ TinyMCE未検出 - 再試行中...');
            setTimeout(waitForFilamentTinyEditor, 100);
        }
    };
    
    // 即座に実行 + 遅延実行で確実に
    waitForFilamentTinyEditor();
    setTimeout(waitForFilamentTinyEditor, 500);
    setTimeout(waitForFilamentTinyEditor, 1000);
    setTimeout(waitForFilamentTinyEditor, 2000);
    
    // Livewire/Filamentのページ読み込み完了後にも実行
    document.addEventListener('livewire:init', function() {
        console.log('🔥 Livewire初期化検出 - TinyMCE再設定');
        setTimeout(waitForFilamentTinyEditor, 500);
    });
    
    // MutationObserverでTinyMCEエディターの動的な出現を監視
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType === Node.ELEMENT_NODE) {
                    // TinyMCEエディター要素を検出
                    if (node.matches && (node.matches('textarea[data-tiny-editor]') || node.querySelector('textarea[data-tiny-editor]'))) {
                        console.log('🔍 TinyMCEエディター要素検出 - 設定適用');
                        setTimeout(waitForFilamentTinyEditor, 100);
                    }
                }
            });
        });
    });
    
    // body全体を監視
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    
    // フォールバック: TinyMCEツールバーに直接ボタンを挿入
    const addDirectToolbarButton = function() {
        console.log('🔧 直接ツールバーボタン挿入試行');
        
        // TinyMCEのツールバーを探す
        const toolbars = document.querySelectorAll('.tox-toolbar, .mce-toolbar');
        
        toolbars.forEach((toolbar, index) => {
            // すでにボタンがあるかチェック
            if (toolbar.querySelector('.custom-upload-btn')) {
                return;
            }
            
            console.log(`📎 ツールバー ${index + 1} にボタン追加中`);
            
            // カスタムアップロードボタンを作成
            const uploadBtn = document.createElement('button');
            uploadBtn.className = 'custom-upload-btn tox-tbtn';
            uploadBtn.type = 'button';
            uploadBtn.title = '画像アップロード';
            uploadBtn.innerHTML = '<span class="tox-icon"><svg width="24" height="24" viewBox="0 0 24 24"><path d="M19 7v2.99s-1.99.01-2 0V7h-3s.01-1.99 0-2h3V2h2v3h3v2h-3zm-3 4V9h-3V7H5c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2v-6h-1zm-2-2H5V7h9v2z" fill="currentColor"/></svg></span>';
            uploadBtn.style.cssText = `
                background: #f8f9fa;
                border: 1px solid #ddd;
                border-radius: 3px;
                padding: 6px;
                margin: 2px;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            `;
            
            uploadBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('🖱️ カスタムアップロードボタンクリック');
                
                // 対応するTinyMCEエディターインスタンスを取得
                const editorContainer = this.closest('.tox-editor-container');
                let editor = null;
                
                if (editorContainer && typeof tinymce !== 'undefined') {
                    // エディターIDを取得
                    const iframe = editorContainer.querySelector('iframe');
                    if (iframe && iframe.id) {
                        const editorId = iframe.id.replace('_ifr', '');
                        editor = tinymce.get(editorId);
                    }
                }
                
                if (editor) {
                    console.log('✅ エディターインスタンス取得成功');
                    window.uploadImageToEditor(editor);
                } else {
                    console.log('⚠️ エディターインスタンス取得失敗 - 汎用アップロード関数実行');
                    // フォールバック: 汎用アップロード
                    window.genericImageUpload();
                }
            });
            
            // ツールバーの最初に挿入
            if (toolbar.firstChild) {
                toolbar.insertBefore(uploadBtn, toolbar.firstChild);
                console.log(`✅ ツールバー ${index + 1} にボタン追加完了`);
            }
        });
    };
    
    // 定期的にツールバーボタン挿入を試行
    setTimeout(addDirectToolbarButton, 1000);
    setTimeout(addDirectToolbarButton, 2000);
    setTimeout(addDirectToolbarButton, 3000);
    setInterval(addDirectToolbarButton, 5000);
});

// 画像アップロード関数
window.uploadImageToEditor = function(editor) {
    // ファイル選択用input要素を作成
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/jpeg,image/jpg,image/png,image/webp,image/gif';
    input.style.display = 'none';
    
    input.onchange = function(event) {
        const file = event.target.files[0];
        if (!file) return;
        
        // ファイルサイズチェック（5MB制限）
        if (file.size > 5 * 1024 * 1024) {
            editor.notificationManager.open({
                text: 'ファイルサイズが5MBを超えています。',
                type: 'error'
            });
            return;
        }
        
        // FormDataでアップロード
        const formData = new FormData();
        formData.append('file', file);
        
        // CSRF token取得
        const token = document.querySelector('meta[name="csrf-token"]');
        if (!token) {
            editor.notificationManager.open({
                text: 'CSRF token not found',
                type: 'error'
            });
            return;
        }
        
        // アップロード中の通知
        const notification = editor.notificationManager.open({
            text: '画像をアップロード中...',
            type: 'info',
            timeout: 0
        });
        
        // XMLHttpRequestでアップロード
        const xhr = new XMLHttpRequest();
        xhr.withCredentials = true;
        xhr.open('POST', '/admin/upload-image');
        xhr.setRequestHeader('X-CSRF-TOKEN', token.getAttribute('content'));
        
        xhr.onload = function() {
            notification.close();
            
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.location) {
                        // エディタに画像を挿入
                        const img = `<img src="${response.location}" alt="${file.name}" style="max-width: 100%; height: auto;" />`;
                        editor.insertContent(img);
                        
                        editor.notificationManager.open({
                            text: '画像をアップロードしました。',
                            type: 'success'
                        });
                    } else {
                        editor.notificationManager.open({
                            text: 'アップロードに失敗しました: ' + (response.error || 'Unknown error'),
                            type: 'error'
                        });
                    }
                } catch (e) {
                    editor.notificationManager.open({
                        text: 'レスポンス解析エラー: ' + e.message,
                        type: 'error'
                    });
                }
            } else {
                let errorMsg = 'HTTP Error: ' + xhr.status;
                try {
                    const errorResponse = JSON.parse(xhr.responseText);
                    errorMsg = errorResponse.error || errorMsg;
                } catch (e) {
                    // JSON解析に失敗した場合はそのままのエラーメッセージを使用
                }
                
                editor.notificationManager.open({
                    text: 'アップロードエラー: ' + errorMsg,
                    type: 'error'
                });
            }
        };
        
        xhr.onerror = function() {
            notification.close();
            editor.notificationManager.open({
                text: 'ネットワークエラーが発生しました。',
                type: 'error'
            });
        };
        
        xhr.send(formData);
    };
    
    // ファイル選択ダイアログを開く
    document.body.appendChild(input);
    input.click();
    document.body.removeChild(input);
};

// アップロードフォームを表示する関数
window.showUploadForm = function(editor) {
    console.log('🖼️ アップロードフォーム表示開始');
    
    // モーダル要素を作成
    const modal = document.createElement('div');
    modal.id = 'upload-modal';
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
    `;
    
    modal.innerHTML = `
        <div style="
            background: white;
            border-radius: 8px;
            padding: 24px;
            width: 480px;
            max-width: 90vw;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            font-family: system-ui, -apple-system, sans-serif;
        ">
            <h3 style="margin: 0 0 20px 0; font-size: 18px; color: #333;">画像をアップロード</h3>
            
            <div style="margin-bottom: 20px;">
                <div style="
                    border: 2px dashed #ddd;
                    border-radius: 6px;
                    padding: 40px 20px;
                    text-align: center;
                    background: #f9f9f9;
                    cursor: pointer;
                    transition: all 0.3s ease;
                " id="drop-zone">
                    <div style="font-size: 48px; margin-bottom: 12px;">📷</div>
                    <p style="margin: 0 0 8px 0; font-size: 16px; color: #555;">ファイルを選択またはドラッグ&ドロップ</p>
                    <p style="margin: 0; font-size: 12px; color: #888;">JPG, PNG, WebP, GIF (最大5MB)</p>
                </div>
                
                <input type="file" id="file-input" accept="image/jpeg,image/jpg,image/png,image/webp,image/gif" style="display: none;">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">画像サイズ:</label>
                <div style="display: flex; gap: 12px;">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="radio" name="image-size" value="sm" style="margin-right: 6px;">
                        小 (320px)
                    </label>
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="radio" name="image-size" value="md" checked style="margin-right: 6px;">
                        中 (600px)
                    </label>
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="radio" name="image-size" value="lg" style="margin-right: 6px;">
                        大 (100%)
                    </label>
                </div>
            </div>
            
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" id="cancel-btn" style="
                    padding: 10px 20px;
                    background: #f5f5f5;
                    border: 1px solid #ddd;
                    border-radius: 4px;
                    cursor: pointer;
                ">キャンセル</button>
                <button type="button" id="upload-btn" style="
                    padding: 10px 20px;
                    background: #3b82f6;
                    color: white;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                " disabled>アップロード</button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    const fileInput = modal.querySelector('#file-input');
    const dropZone = modal.querySelector('#drop-zone');
    const cancelBtn = modal.querySelector('#cancel-btn');
    const uploadBtn = modal.querySelector('#upload-btn');
    
    let selectedFile = null;
    
    // ドロップゾーンクリックでファイル選択
    dropZone.addEventListener('click', function() {
        fileInput.click();
    });
    
    // ファイル選択時
    fileInput.addEventListener('change', function(e) {
        handleFileSelect(e.target.files[0]);
    });
    
    // ドラッグ&ドロップ
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropZone.style.borderColor = '#3b82f6';
        dropZone.style.backgroundColor = '#eff6ff';
    });
    
    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        dropZone.style.borderColor = '#ddd';
        dropZone.style.backgroundColor = '#f9f9f9';
    });
    
    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropZone.style.borderColor = '#ddd';
        dropZone.style.backgroundColor = '#f9f9f9';
        
        const file = e.dataTransfer.files[0];
        if (file) {
            handleFileSelect(file);
        }
    });
    
    // ファイル選択処理
    function handleFileSelect(file) {
        if (!file) return;
        
        // ファイル形式チェック
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            alert('サポートされていないファイル形式です。JPG, PNG, WebP, GIFのみ対応しています。');
            return;
        }
        
        // ファイルサイズチェック
        if (file.size > 5 * 1024 * 1024) {
            alert('ファイルサイズが5MBを超えています。');
            return;
        }
        
        selectedFile = file;
        
        // プレビュー表示
        dropZone.innerHTML = `
            <div style="font-size: 32px; margin-bottom: 8px;">✅</div>
            <p style="margin: 0; font-size: 14px; color: #555;">${file.name}</p>
            <p style="margin: 4px 0 0 0; font-size: 12px; color: #888;">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
        `;
        
        uploadBtn.disabled = false;
        uploadBtn.style.background = '#3b82f6';
    }
    
    // キャンセルボタン
    cancelBtn.addEventListener('click', function() {
        document.body.removeChild(modal);
    });
    
    // モーダル外クリックで閉じる
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            document.body.removeChild(modal);
        }
    });
    
    // アップロードボタン
    uploadBtn.addEventListener('click', function() {
        if (!selectedFile) return;
        
        const size = modal.querySelector('input[name="image-size"]:checked').value;
        
        uploadBtn.disabled = true;
        uploadBtn.textContent = 'アップロード中...';
        uploadBtn.style.background = '#94a3b8';
        
        performUpload(selectedFile, size, editor, function(success) {
            if (success) {
                document.body.removeChild(modal);
            } else {
                uploadBtn.disabled = false;
                uploadBtn.textContent = 'アップロード';
                uploadBtn.style.background = '#3b82f6';
            }
        });
    });
    
    console.log('✅ アップロードフォーム表示完了');
};

// アップロード実行関数
function performUpload(file, size, editor, callback) {
    console.log('📤 アップロード実行:', file.name, size);
    
    const formData = new FormData();
    formData.append('file', file);
    
    // CSRF token取得
    const token = document.querySelector('meta[name="csrf-token"]');
    if (!token) {
        alert('CSRF token not found');
        callback(false);
        return;
    }
    
    const xhr = new XMLHttpRequest();
    xhr.withCredentials = true;
    xhr.open('POST', '/admin/upload-image');
    xhr.setRequestHeader('X-CSRF-TOKEN', token.getAttribute('content'));
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.location) {
                    // サイズクラスに基づいてスタイルを設定
                    let style = 'max-width: 100%; height: auto;';
                    switch(size) {
                        case 'sm':
                            style = 'max-width: 320px; width: 320px; height: auto;';
                            break;
                        case 'lg':
                            style = 'max-width: 900px; width: 100%; height: auto;';
                            break;
                        default: // md
                            style = 'max-width: 600px; width: 600px; height: auto;';
                            break;
                    }
                    
                    const img = `<p><img src="${response.location}" alt="${file.name}" class="img-${size}" data-size="${size}" style="${style}" /></p>`;
                    editor.insertContent(img);
                    
                    console.log('✅ アップロード成功:', response.location);
                    callback(true);
                } else {
                    alert('アップロードに失敗しました: ' + (response.error || 'Unknown error'));
                    callback(false);
                }
            } catch (e) {
                alert('レスポンス解析エラー: ' + e.message);
                callback(false);
            }
        } else {
            let errorMsg = 'HTTP Error: ' + xhr.status;
            try {
                const errorResponse = JSON.parse(xhr.responseText);
                errorMsg = errorResponse.error || errorMsg;
            } catch (e) {
                // JSON解析に失敗した場合はそのままのエラーメッセージを使用
            }
            
            alert('アップロードエラー: ' + errorMsg);
            callback(false);
        }
    };
    
    xhr.onerror = function() {
        alert('ネットワークエラーが発生しました。');
        callback(false);
    };
    
    xhr.send(formData);
}

// 汎用画像アップロード関数（エディターインスタンスが取得できない場合のフォールバック）
window.genericImageUpload = function() {
    console.log('🔧 汎用画像アップロード開始');
    
    // ファイル選択用input要素を作成
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/jpeg,image/jpg,image/png,image/webp,image/gif';
    input.style.display = 'none';
    
    input.onchange = function(event) {
        const file = event.target.files[0];
        if (!file) return;
        
        // ファイルサイズチェック（5MB制限）
        if (file.size > 5 * 1024 * 1024) {
            alert('ファイルサイズが5MBを超えています。');
            return;
        }
        
        // FormDataでアップロード
        const formData = new FormData();
        formData.append('file', file);
        
        // CSRF token取得
        const token = document.querySelector('meta[name="csrf-token"]');
        if (!token) {
            alert('CSRF token not found');
            return;
        }
        
        // アップロード中の表示
        console.log('📤 汎用アップロード実行中...');
        
        // XMLHttpRequestでアップロード
        const xhr = new XMLHttpRequest();
        xhr.withCredentials = true;
        xhr.open('POST', '/admin/upload-image');
        xhr.setRequestHeader('X-CSRF-TOKEN', token.getAttribute('content'));
        
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.location) {
                        // TinyMCEエディターに直接挿入を試行
                        insertImageToActiveEditor(response.location);
                        console.log('✅ 汎用アップロード成功');
                        alert('画像をアップロードしました！');
                    } else {
                        alert('アップロードに失敗しました: ' + (response.error || 'Unknown error'));
                    }
                } catch (e) {
                    alert('レスポンス解析エラー: ' + e.message);
                }
            } else {
                let errorMsg = 'HTTP Error: ' + xhr.status;
                try {
                    const errorResponse = JSON.parse(xhr.responseText);
                    errorMsg = errorResponse.error || errorMsg;
                } catch (e) {
                    // JSON解析に失敗した場合はそのままのエラーメッセージを使用
                }
                alert('アップロードエラー: ' + errorMsg);
            }
        };
        
        xhr.onerror = function() {
            alert('ネットワークエラーが発生しました。');
        };
        
        xhr.send(formData);
    };
    
    // ファイル選択ダイアログを開く
    document.body.appendChild(input);
    input.click();
    document.body.removeChild(input);
};

// アクティブなTinyMCEエディターに画像を挿入
function insertImageToActiveEditor(imageUrl) {
    console.log('🖼️ アクティブエディターに画像挿入試行:', imageUrl);
    
    // すべてのTinyMCEエディターインスタンスを試行
    if (typeof tinymce !== 'undefined' && tinymce.editors) {
        const editors = Object.values(tinymce.editors);
        
        for (const editor of editors) {
            if (editor && !editor.destroyed) {
                try {
                    const img = `<img src="${imageUrl}" alt="アップロード画像" style="max-width: 100%; height: auto;" />`;
                    editor.insertContent(img);
                    console.log('✅ エディターに画像挿入成功:', editor.id);
                    return true;
                } catch (e) {
                    console.log('⚠️ エディター挿入失敗:', editor.id, e);
                }
            }
        }
    }
    
    // フォールバック: クリップボードにURLをコピー
    try {
        const textArea = document.createElement('textarea');
        textArea.value = `<img src="${imageUrl}" alt="アップロード画像" style="max-width: 100%; height: auto;" />`;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        
        alert('画像をアップロードしました！\n画像タグをクリップボードにコピーしましたので、エディターに貼り付けてください。');
        console.log('📋 クリップボードにHTMLコピー完了');
        return true;
    } catch (e) {
        console.error('❌ クリップボードコピー失敗:', e);
        alert(`画像をアップロードしました！\n以下のタグを手動でエディターに貼り付けてください：\n<img src="${imageUrl}" alt="アップロード画像" style="max-width: 100%; height: auto;" />`);
        return false;
    }
};