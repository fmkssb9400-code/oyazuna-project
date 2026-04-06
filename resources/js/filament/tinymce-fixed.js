/**
 * Filament TinyMCE - 画像アップロード機能とRTL/レイアウト問題の完全修正
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎯 TinyMCE完全修正版 開始');
    
    // TinyMCE設定の完全上書き
    const setupFixedTinyMCE = function() {
        if (typeof tinymce === 'undefined') {
            console.log('⏳ TinyMCE未読み込み - 再試行中...');
            setTimeout(setupFixedTinyMCE, 500);
            return;
        }
        
        console.log('✅ TinyMCE検出 - 修正設定適用中');
        
        // TinyMCE初期化の完全オーバーライド
        const originalInit = tinymce.init;
        tinymce.init = function(config) {
            console.log('🔧 TinyMCE初期化 - 修正版適用');
            
            // 完全修正設定
            const fixedConfig = {
                // 基本設定
                height: 400,
                menubar: true,
                branding: false,
                language: 'ja',
                
                // プラグイン（画像プラグイン必須）
                plugins: [
                    'lists', 'link', 'image', 'table', 'code', 'preview', 
                    'emoticons', 'fullscreen', 'insertdatetime', 'searchreplace', 
                    'directionality', 'paste', 'textcolor', 'colorpicker', 
                    'textpattern', 'imagetools', 'nonbreaking'
                ].join(' '),
                
                // ツールバー（画像ボタン確実に含む）
                toolbar: 'undo redo | formatselect | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image table | code preview fullscreen',
                
                // ====== 画像アップロード設定 ======
                images_upload_url: '/admin/editor/upload-image',
                images_upload_credentials: true,
                automatic_uploads: true,
                paste_data_images: true,
                
                // 画像ダイアログでアップロードタブを表示
                image_uploadtab: true,
                file_picker_types: 'image',
                
                // 画像ファイル制限
                images_file_types: 'jpg,jpeg,png,gif,webp',
                
                // URL変換設定
                convert_urls: false,
                relative_urls: false,
                
                // ====== RTL/レイアウト修正設定 ======
                
                // 文字方向を強制的にLTRに固定
                directionality: 'ltr',
                
                // iframe内のCSS（最重要：RTL問題解決）
                content_style: `
                    body { 
                        font-family: "Hiragino Sans", "Yu Gothic UI", "Meiryo UI", sans-serif !important;
                        direction: ltr !important;
                        text-align: left !important;
                        margin: 16px !important;
                        padding: 0 !important;
                        background: white !important;
                    }
                    * { 
                        direction: ltr !important; 
                        text-align: left !important; 
                    }
                    p, div, h1, h2, h3, h4, h5, h6, li, td, th, span { 
                        direction: ltr !important; 
                        text-align: left !important; 
                    }
                    h1 { font-size: 2em; font-weight: bold; margin: 0.67em 0; }
                    h2 { font-size: 1.5em; font-weight: bold; margin: 0.75em 0; }
                    h3 { font-size: 1.17em; font-weight: bold; margin: 0.83em 0; }
                    h4 { font-size: 1em; font-weight: bold; margin: 1em 0; }
                    h5 { font-size: 0.83em; font-weight: bold; margin: 1.17em 0; }
                    h6 { font-size: 0.75em; font-weight: bold; margin: 1.33em 0; }
                `,
                
                // ブロックフォーマット
                block_formats: '段落=p; 見出し1=h1; 見出し2=h2; 見出し3=h3; 見出し4=h4; 見出し5=h5; 見出し6=h6; プリフォーマット=pre',
                
                // ====== 画像アップロードハンドラー ======
                images_upload_handler: function(blobInfo, success, failure) {
                    console.log('📤 画像アップロード開始:', blobInfo.filename());
                    
                    const formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    
                    // CSRF トークン取得
                    const token = document.querySelector('meta[name="csrf-token"]');
                    if (!token) {
                        const errorMsg = 'CSRF token not found. Please refresh the page.';
                        console.error('❌', errorMsg);
                        failure(errorMsg);
                        return;
                    }
                    
                    // XMLHttpRequest でアップロード
                    const xhr = new XMLHttpRequest();
                    xhr.withCredentials = true;
                    xhr.open('POST', '/admin/editor/upload-image');
                    xhr.setRequestHeader('X-CSRF-TOKEN', token.getAttribute('content'));
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    
                    xhr.onload = function() {
                        console.log('📥 アップロードレスポンス:', xhr.status, xhr.responseText);
                        
                        if (xhr.status === 200) {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                if (response.location) {
                                    console.log('✅ アップロード成功:', response.location);
                                    success(response.location);
                                } else {
                                    const errorMsg = response.error || 'No location in response';
                                    console.error('❌ レスポンスエラー:', response);
                                    failure(errorMsg);
                                }
                            } catch (e) {
                                const errorMsg = 'Response parse error: ' + e.message;
                                console.error('❌ JSONパースエラー:', e, xhr.responseText);
                                failure(errorMsg);
                            }
                        } else if (xhr.status === 419) {
                            const errorMsg = 'CSRF token mismatch (419). Please refresh the page and try again.';
                            console.error('❌ CSRF エラー');
                            failure(errorMsg);
                        } else if (xhr.status === 401) {
                            const errorMsg = 'Authentication required (401). Please login and try again.';
                            console.error('❌ 認証エラー');
                            failure(errorMsg);
                        } else if (xhr.status === 403) {
                            const errorMsg = 'Access forbidden (403). Please check your permissions.';
                            console.error('❌ 権限エラー');
                            failure(errorMsg);
                        } else if (xhr.status === 422) {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                const errorMsg = response.error || 'Validation failed (422)';
                                console.error('❌ バリデーションエラー:', response);
                                failure(errorMsg);
                            } catch (e) {
                                failure('Validation failed (422)');
                            }
                        } else {
                            const errorMsg = `HTTP Error ${xhr.status}: ${xhr.responseText}`;
                            console.error('❌ HTTPエラー:', xhr.status, xhr.responseText);
                            failure(errorMsg);
                        }
                    };
                    
                    xhr.onerror = function() {
                        const errorMsg = 'Network error occurred. Please check your connection.';
                        console.error('❌ ネットワークエラー');
                        failure(errorMsg);
                    };
                    
                    xhr.send(formData);
                },
                
                // ====== セットアップフック（RTL修正の追加処理） ======
                setup: function(editor) {
                    console.log('🔧 TinyMCEセットアップ開始:', editor.id);
                    
                    // エディター初期化時の処理
                    editor.on('init', function() {
                        console.log('🎨 エディター初期化完了:', editor.id);
                        
                        const body = editor.getBody();
                        if (body) {
                            // body要素のLTR強制設定
                            body.style.direction = 'ltr';
                            body.style.textAlign = 'left';
                            body.style.fontFamily = '"Hiragino Sans", "Yu Gothic UI", "Meiryo UI", sans-serif';
                            body.setAttribute('dir', 'ltr');
                            body.setAttribute('lang', 'ja');
                            
                            console.log('✅ エディターbody LTR設定完了');
                        }
                    });
                    
                    // コンテンツ変更時にLTRを維持
                    editor.on('NodeChange', function() {
                        const body = editor.getBody();
                        if (body && body.style.direction !== 'ltr') {
                            body.style.direction = 'ltr';
                            body.style.textAlign = 'left';
                            body.setAttribute('dir', 'ltr');
                        }
                    });
                    
                    // 既存のCTA/比較表ボタンを再実装（壊さないように）
                    if (typeof window.insertCTAContent === 'function') {
                        editor.ui.registry.addButton('insertCTA', {
                            text: 'CTA',
                            tooltip: 'CTAボタン挿入',
                            onAction: function() {
                                window.insertCTAContent(editor);
                            }
                        });
                    }
                    
                    if (typeof window.insertCompareTable === 'function') {
                        editor.ui.registry.addButton('insertTable', {
                            text: '比較表',
                            tooltip: '比較表テンプレート挿入',
                            onAction: function() {
                                window.insertCompareTable(editor);
                            }
                        });
                    }
                }
            };
            
            // 元の設定と結合（修正設定を優先）
            const finalConfig = Object.assign({}, config, fixedConfig);
            
            console.log('🔧 最終TinyMCE設定:', finalConfig);
            console.log('🔧 画像アップロードURL:', finalConfig.images_upload_url);
            console.log('🔧 アップロードタブ有効:', finalConfig.image_uploadtab);
            console.log('🔧 文字方向:', finalConfig.directionality);
            
            return originalInit.call(this, finalConfig);
        };
        
        console.log('✅ TinyMCE修正設定完了');
    };
    
    // 即座に実行 + 遅延実行で確実に
    setupFixedTinyMCE();
    setTimeout(setupFixedTinyMCE, 1000);
    setTimeout(setupFixedTinyMCE, 3000);
    
    // Livewire/Filament対応
    document.addEventListener('livewire:init', function() {
        console.log('🔥 Livewire初期化検出 - TinyMCE再設定');
        setTimeout(setupFixedTinyMCE, 500);
    });
});

// ====== TinyMCE外側コンテナのCSS修正（即座に実行） ======
(function() {
    console.log('🎨 TinyMCEコンテナCSS修正開始');
    
    const fixTinyMCELayout = function() {
        const style = document.createElement('style');
        style.id = 'tinymce-layout-fix';
        style.textContent = `
            /* TinyMCEコンテナのレイアウト修正 */
            .tox-tinymce {
                direction: ltr !important;
                text-align: left !important;
                max-width: none !important;
                width: 100% !important;
                margin: 0 !important;
            }
            
            .tox-edit-area {
                direction: ltr !important;
                text-align: left !important;
            }
            
            .tox-edit-area iframe {
                direction: ltr !important;
            }
            
            /* Filament specific fixes */
            .fi-fo-rich-editor .tox-tinymce {
                direction: ltr !important;
                text-align: left !important;
            }
            
            .fi-fo-rich-editor .tox-edit-area {
                direction: ltr !important;
                text-align: left !important;
            }
            
            /* Force LTR for all text content */
            .tox-tinymce * {
                direction: ltr !important;
                text-align: left !important;
            }
        `;
        
        // 既存のスタイルを削除
        const existingStyle = document.getElementById('tinymce-layout-fix');
        if (existingStyle) {
            existingStyle.remove();
        }
        
        document.head.appendChild(style);
        console.log('✅ TinyMCEレイアウト修正CSS適用完了');
    };
    
    // 即座に実行
    fixTinyMCELayout();
    
    // DOM変更監視で定期的に実行
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', fixTinyMCELayout);
    }
    
    // Filament/Livewire環境対応
    setTimeout(fixTinyMCELayout, 1000);
    setTimeout(fixTinyMCELayout, 3000);
    setTimeout(fixTinyMCELayout, 5000);
})();