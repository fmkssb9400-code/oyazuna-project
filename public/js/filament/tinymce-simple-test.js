/**
 * TinyMCE画像アップロード機能テスト版 - シンプル版
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('🧪 TinyMCEテスト版 開始');
    
    const setupTestTinyMCE = function() {
        if (typeof tinymce === 'undefined') {
            setTimeout(setupTestTinyMCE, 500);
            return;
        }
        
        console.log('✅ TinyMCE検出 - テスト設定適用中');
        
        const originalInit = tinymce.init;
        tinymce.init = function(config) {
            console.log('🔧 TinyMCE初期化 - テスト版');
            
            const testConfig = Object.assign({}, config, {
                // 基本設定
                height: 400,
                menubar: true,
                branding: false,
                language: 'ja',
                
                // プラグイン
                plugins: 'lists link image table code preview emoticons fullscreen insertdatetime searchreplace directionality paste textcolor colorpicker textpattern imagetools nonbreaking',
                
                // ツールバー
                toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image table | code preview',
                
                // 重要: 画像アップロード設定
                images_upload_url: '/admin/editor/upload-image',
                images_upload_credentials: true,
                automatic_uploads: true,
                paste_data_images: true,
                image_uploadtab: true,
                file_picker_types: 'image',
                images_file_types: 'jpg,jpeg,png,gif,webp',
                convert_urls: false,
                relative_urls: false,
                
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
                
                // カスタムアップロードハンドラー
                images_upload_handler: function(blobInfo, success, failure) {
                    console.log('📤 画像アップロード開始 (テスト版)');
                    console.log('ファイル名:', blobInfo.filename());
                    console.log('ファイルサイズ:', blobInfo.blob().size);
                    
                    const formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    
                    const token = document.querySelector('meta[name="csrf-token"]');
                    if (!token) {
                        console.error('❌ CSRF token not found');
                        failure('CSRF token not found');
                        return;
                    }
                    
                    console.log('🔑 CSRF token found:', token.getAttribute('content').substring(0, 10) + '...');
                    
                    fetch('/admin/editor/upload-image', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': token.getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        console.log('📥 レスポンス受信:', response.status);
                        console.log('レスポンスヘッダー:', Object.fromEntries(response.headers));
                        
                        return response.text().then(text => {
                            console.log('📄 レスポンスボディ:', text);
                            
                            if (response.ok) {
                                try {
                                    const data = JSON.parse(text);
                                    if (data.location) {
                                        console.log('✅ アップロード成功:', data.location);
                                        success(data.location);
                                    } else {
                                        console.error('❌ locationが見つかりません:', data);
                                        failure(data.error || 'No location in response');
                                    }
                                } catch (e) {
                                    console.error('❌ JSON解析エラー:', e);
                                    failure('Response parse error');
                                }
                            } else {
                                console.error('❌ HTTP エラー:', response.status, text);
                                
                                let errorMessage = `HTTP ${response.status}`;
                                try {
                                    const errorData = JSON.parse(text);
                                    errorMessage = errorData.error || errorMessage;
                                } catch (e) {
                                    // JSONでない場合はそのまま
                                    errorMessage = text || errorMessage;
                                }
                                
                                failure(errorMessage);
                            }
                        });
                    })
                    .catch(error => {
                        console.error('❌ ネットワークエラー:', error);
                        failure('Network error: ' + error.message);
                    });
                },
                
                setup: function(editor) {
                    console.log('🔧 エディターセットアップ:', editor.id);
                    
                    editor.on('init', function() {
                        console.log('🎨 エディター初期化完了:', editor.id);
                        
                        // エディターbodyの確認
                        const body = editor.getBody();
                        if (body) {
                            console.log('📝 エディターbody検出');
                            body.style.direction = 'ltr';
                            body.style.textAlign = 'left';
                        }
                    });
                    
                    // 画像挿入イベント監視
                    editor.on('NodeChange', function(e) {
                        const imgs = editor.dom.select('img');
                        console.log('🖼️ 現在の画像数:', imgs.length);
                    });
                }
            });
            
            console.log('🔧 テスト設定:', testConfig);
            console.log('🔧 アップロードURL:', testConfig.images_upload_url);
            console.log('🔧 アップロードタブ:', testConfig.image_uploadtab);
            
            return originalInit.call(this, testConfig);
        };
    };
    
    setupTestTinyMCE();
    
    // 遅延実行も追加
    setTimeout(setupTestTinyMCE, 2000);
});