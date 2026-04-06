/**
 * TinyMCE 確実動作版 - 画像アップロード + RTL修正
 */
console.log('🔧 TinyMCE確実動作版 開始');

// TinyMCE設定の確実な上書き
document.addEventListener('DOMContentLoaded', function() {
    let attempts = 0;
    const maxAttempts = 20;
    
    function setupWorkingTinyMCE() {
        attempts++;
        console.log(`🔄 TinyMCE設定試行 ${attempts}/${maxAttempts}`);
        
        if (typeof tinymce === 'undefined') {
            if (attempts < maxAttempts) {
                setTimeout(setupWorkingTinyMCE, 500);
            }
            return;
        }
        
        console.log('✅ TinyMCE検出 - 確実設定適用');
        
        // 既存エディターがあれば削除
        if (tinymce.editors) {
            Object.keys(tinymce.editors).forEach(id => {
                if (tinymce.editors[id]) {
                    tinymce.editors[id].destroy();
                }
            });
        }
        
        // TinyMCE初期化を完全に置き換え
        const originalInit = tinymce.init;
        tinymce.init = function(config) {
            console.log('🚀 TinyMCE初期化実行 - 確実動作版');
            
            // 基本設定
            const workingConfig = {
                // エディター基本設定
                height: 400,
                menubar: true,
                branding: false,
                language: 'ja',
                
                // プラグイン（必要最小限）
                plugins: 'lists link image table code preview fullscreen paste',
                
                // ツールバー（シンプル）
                toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image table | code preview',
                
                // ====== 画像アップロード設定 ======
                images_upload_url: '/admin/editor/upload-image',
                images_upload_credentials: true,
                automatic_uploads: true,
                paste_data_images: true,
                image_uploadtab: true,
                file_picker_types: 'image',
                images_file_types: 'jpg,jpeg,png,gif,webp',
                convert_urls: false,
                relative_urls: false,
                
                // ====== RTL修正設定 ======
                directionality: 'ltr',
                content_style: `
                    body { 
                        direction: ltr !important; 
                        text-align: left !important; 
                        font-family: Arial, sans-serif; 
                        margin: 16px; 
                        background: white;
                    }
                    p, div, h1, h2, h3, h4, h5, h6 { 
                        direction: ltr !important; 
                        text-align: left !important; 
                    }
                `,
                
                // ====== 画像アップロードハンドラー（Promise版） ======
                images_upload_handler: (blobInfo, progress) => {
                    return new Promise((resolve, reject) => {
                        console.log('📤 Promise版画像アップロード開始:', blobInfo.filename());
                        
                        const formData = new FormData();
                        formData.append('file', blobInfo.blob(), blobInfo.filename());
                        
                        // CSRF token取得
                        const token = document.querySelector('meta[name="csrf-token"]');
                        if (!token) {
                            console.error('❌ CSRF token not found');
                            reject('CSRF token not found. Please refresh the page.');
                            return;
                        }
                        
                        console.log('🔑 CSRF token確認済み');
                        
                        // XMLHttpRequestでアップロード
                        const xhr = new XMLHttpRequest();
                        xhr.withCredentials = true;
                        xhr.open('POST', '/admin/editor/upload-image');
                        xhr.setRequestHeader('X-CSRF-TOKEN', token.getAttribute('content'));
                        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                        
                        // プログレス処理
                        xhr.upload.onprogress = function(e) {
                            if (e.lengthComputable && progress) {
                                const percent = Math.round((e.loaded / e.total) * 100);
                                progress(percent);
                                console.log(`📊 アップロード進捗: ${percent}%`);
                            }
                        };
                        
                        xhr.onload = function() {
                            console.log('📥 Promise版アップロードレスポンス:', xhr.status, xhr.responseText);
                            
                            if (xhr.status === 200) {
                                try {
                                    const response = JSON.parse(xhr.responseText);
                                    if (response.location) {
                                        console.log('✅ Promise版アップロード成功:', response.location);
                                        resolve(response.location);
                                    } else {
                                        console.error('❌ locationなし:', response);
                                        reject(response.error || 'No location in response');
                                    }
                                } catch (e) {
                                    console.error('❌ JSON解析エラー:', e, xhr.responseText);
                                    reject('Response parse error');
                                }
                            } else {
                                console.error('❌ HTTP エラー:', xhr.status, xhr.responseText);
                                let errorMsg = `HTTP ${xhr.status}`;
                                
                                if (xhr.status === 419) {
                                    errorMsg = 'CSRF token expired. Please refresh the page.';
                                } else if (xhr.status === 401) {
                                    errorMsg = 'Authentication required. Please login.';
                                } else if (xhr.status === 403) {
                                    errorMsg = 'Access forbidden. Check permissions.';
                                } else if (xhr.status === 422) {
                                    try {
                                        const errorData = JSON.parse(xhr.responseText);
                                        errorMsg = errorData.error || 'Validation failed';
                                    } catch (e) {
                                        errorMsg = 'Validation failed';
                                    }
                                }
                                
                                reject(errorMsg);
                            }
                        };
                        
                        xhr.onerror = function() {
                            console.error('❌ ネットワークエラー');
                            reject('Network error occurred');
                        };
                        
                        xhr.send(formData);
                    });
                },
                
                // ====== セットアップ ======
                setup: function(editor) {
                    console.log('🔧 エディターセットアップ:', editor.id);
                    
                    editor.on('init', function() {
                        console.log('🎨 エディター初期化完了:', editor.id);
                        
                        // エディターbodyの設定
                        const body = editor.getBody();
                        if (body) {
                            body.style.direction = 'ltr';
                            body.style.textAlign = 'left';
                            body.setAttribute('dir', 'ltr');
                            console.log('✅ エディターbody LTR設定完了');
                        }
                        
                        // コンテンツ読み込み確認
                        const content = editor.getContent();
                        console.log('📄 読み込まれたコンテンツ長:', content.length);
                        if (content.length === 0) {
                            console.warn('⚠️ コンテンツが空です');
                        }
                    });
                    
                    // 画像挿入イベント監視
                    editor.on('NodeChange', function() {
                        const images = editor.dom.select('img');
                        if (images.length > 0) {
                            console.log('🖼️ 画像検出:', images.length, '個');
                        }
                    });
                    
                    // 空コンテンツ保存防止
                    editor.on('BeforeSetContent', function(e) {
                        if (e.content === '' && !e.initial) {
                            console.warn('⚠️ 空コンテンツ設定をブロック');
                            e.preventDefault();
                            return false;
                        }
                    });
                }
            };
            
            // 元の設定と統合（workingConfigを優先）
            const finalConfig = Object.assign({}, config, workingConfig);
            
            console.log('🔧 最終設定:', finalConfig);
            console.log('🔧 アップロードURL:', finalConfig.images_upload_url);
            console.log('🔧 アップロードタブ:', finalConfig.image_uploadtab);
            
            return originalInit.call(this, finalConfig);
        };
        
        console.log('✅ TinyMCE確実動作版設定完了');
    }
    
    setupWorkingTinyMCE();
});

// ====== レイアウト修正CSS（即座に適用） ======
(function() {
    const fixStyle = document.createElement('style');
    fixStyle.id = 'tinymce-working-fix';
    fixStyle.textContent = `
        /* TinyMCE コンテナ修正 */
        .tox-tinymce {
            direction: ltr !important;
            text-align: left !important;
            max-width: none !important;
        }
        
        .tox-edit-area {
            direction: ltr !important;
            text-align: left !important;
        }
        
        /* Filament specific */
        .fi-fo-rich-editor {
            direction: ltr !important;
            text-align: left !important;
        }
        
        .fi-fo-rich-editor .tox-tinymce {
            direction: ltr !important;
            text-align: left !important;
        }
    `;
    
    // 既存修正スタイルを削除
    const existingFix = document.getElementById('tinymce-working-fix');
    if (existingFix) {
        existingFix.remove();
    }
    
    document.head.appendChild(fixStyle);
    console.log('✅ TinyMCEレイアウト修正CSS適用');
})();