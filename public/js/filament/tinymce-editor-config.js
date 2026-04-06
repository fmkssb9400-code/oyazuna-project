// Filament TinyMCE用の画像アップロード設定
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎯 TinyMCE画像アップロード設定開始');
    
    // TinyMCE設定を上書き
    const setupTinyMCE = function() {
        if (typeof tinymce === 'undefined') {
            console.log('⏳ TinyMCE未読み込み - 再試行中...');
            setTimeout(setupTinyMCE, 500);
            return;
        }
        
        console.log('✅ TinyMCE検出 - 設定適用中');
        
        // TinyMCE初期化時の設定上書き
        const originalInit = tinymce.init;
        tinymce.init = function(config) {
            console.log('🔧 TinyMCE初期化開始');
            
            // 画像アップロード設定を強制適用
            const uploadConfig = {
                // プラグインに image を確実に追加
                plugins: [
                    'lists', 'link', 'image', 'table', 'code', 'preview', 
                    'emoticons', 'fullscreen', 'insertdatetime', 'searchreplace', 
                    'directionality', 'paste', 'textcolor', 'colorpicker', 
                    'textpattern', 'imagetools', 'nonbreaking'
                ].join(' '),
                
                // ツールバーに image ボタンを追加
                toolbar: 'undo redo | formatselect | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image table | code preview fullscreen',
                
                // 画像アップロード設定
                images_upload_url: '/admin/editor/upload-image',
                images_upload_credentials: true,
                automatic_uploads: true,
                paste_data_images: true,
                
                // 画像ダイアログでアップロードタブを表示
                image_uploadtab: true,
                file_picker_types: 'image',
                
                // 画像ファイル制限
                images_file_types: 'jpg,jpeg,png,gif,webp',
                
                // URL変換無効化
                convert_urls: false,
                relative_urls: false,
                
                // 画像アップロードハンドラー
                images_upload_handler: function(blobInfo, success, failure) {
                    console.log('📤 画像アップロード開始:', blobInfo.filename());
                    
                    const formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    
                    // CSRF トークン取得
                    const token = document.querySelector('meta[name="csrf-token"]');
                    if (!token) {
                        failure('CSRF token not found');
                        return;
                    }
                    
                    // XMLHttpRequest でアップロード
                    const xhr = new XMLHttpRequest();
                    xhr.withCredentials = true;
                    xhr.open('POST', '/admin/editor/upload-image');
                    xhr.setRequestHeader('X-CSRF-TOKEN', token.getAttribute('content'));
                    
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                if (response.location) {
                                    console.log('✅ アップロード成功:', response.location);
                                    success(response.location);
                                } else {
                                    console.error('❌ レスポンスにlocationがありません:', response);
                                    failure(response.error || 'Upload failed');
                                }
                            } catch (e) {
                                console.error('❌ JSONパースエラー:', e);
                                failure('Response parse error: ' + e.message);
                            }
                        } else {
                            console.error('❌ HTTPエラー:', xhr.status, xhr.responseText);
                            failure('HTTP Error: ' + xhr.status);
                        }
                    };
                    
                    xhr.onerror = function() {
                        console.error('❌ ネットワークエラー');
                        failure('Network error');
                    };
                    
                    xhr.send(formData);
                },
                
                // 日本語設定
                language: 'ja',
                
                // その他設定
                height: 400,
                menubar: true,
                branding: false,
                block_formats: '段落=p; 見出し1=h1; 見出し2=h2; 見出し3=h3; 見出し4=h4; 見出し5=h5; 見出し6=h6; プリフォーマット=pre'
            };
            
            // 元の設定と結合
            const finalConfig = Object.assign({}, config, uploadConfig);
            
            console.log('🔧 最終設定:', finalConfig);
            console.log('🔧 画像アップロードURL:', finalConfig.images_upload_url);
            console.log('🔧 アップロードタブ有効:', finalConfig.image_uploadtab);
            
            return originalInit.call(this, finalConfig);
        };
        
        console.log('✅ TinyMCE設定完了');
    };
    
    setupTinyMCE();
});