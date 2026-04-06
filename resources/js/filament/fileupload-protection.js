// Filament FileUpload保護スクリプト - 恒久対応版

document.addEventListener('DOMContentLoaded', function() {
    console.log('📁 FileUpload保護スクリプト開始');
    
    // FileUploadコンポーネント進行中の検出用クラス
    let uploadInProgress = false;
    let uploadStartTime = null;
    
    // FileUpload進行状況をグローバルに管理
    window.fileUploadState = {
        isUploading: false,
        uploadCount: 0,
        errors: []
    };
    
    // MutationObserverでFileUpload要素の変化を監視
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            // アップロード中のローディング要素を検出
            if (mutation.type === 'childList') {
                const addedNodes = Array.from(mutation.addedNodes);
                
                addedNodes.forEach(node => {
                    if (node.nodeType === Node.ELEMENT_NODE) {
                        // Livewireのローディング要素をチェック
                        const loadingElements = node.querySelectorAll ? 
                            node.querySelectorAll('[wire\\:loading]') : [];
                        
                        if (loadingElements.length > 0 || node.matches('[wire\\:loading]')) {
                            detectUploadStart();
                        }
                        
                        // FileUpload progress要素を検出
                        const progressElements = node.querySelectorAll ? 
                            node.querySelectorAll('[role="progressbar"], .filepond--progress') : [];
                            
                        if (progressElements.length > 0 || node.matches('[role="progressbar"]')) {
                            detectUploadStart();
                        }
                    }
                });
            }
            
            // 属性変化でアップロード状況をチェック
            if (mutation.type === 'attributes') {
                const target = mutation.target;
                
                // wire:loading要素の表示状態変化
                if (target.hasAttribute('wire:loading')) {
                    const isVisible = target.style.display !== 'none' && !target.hidden;
                    if (isVisible) {
                        detectUploadStart();
                    } else {
                        detectUploadEnd();
                    }
                }
                
                // data-loading属性の変化
                if (mutation.attributeName === 'data-loading' || 
                    mutation.attributeName === 'wire:loading.delay') {
                    const isLoading = target.getAttribute('data-loading') === 'true' ||
                                    target.hasAttribute('wire:loading.delay');
                    if (isLoading) {
                        detectUploadStart();
                    } else {
                        detectUploadEnd();
                    }
                }
            }
        });
    });
    
    // body全体を詳細に監視
    observer.observe(document.body, {
        childList: true,
        subtree: true,
        attributes: true,
        attributeFilter: ['style', 'class', 'data-loading', 'wire:loading.delay', 'hidden']
    });
    
    // アップロード開始検出
    function detectUploadStart() {
        if (!uploadInProgress) {
            uploadInProgress = true;
            uploadStartTime = Date.now();
            window.fileUploadState.isUploading = true;
            window.fileUploadState.uploadCount++;
            
            console.log('📤 FileUpload開始検出');
            
            // 他のJavaScriptにアップロード開始を通知
            document.dispatchEvent(new CustomEvent('fileupload:start', {
                detail: { startTime: uploadStartTime }
            }));
        }
    }
    
    // アップロード終了検出
    function detectUploadEnd() {
        if (uploadInProgress) {
            const duration = Date.now() - uploadStartTime;
            uploadInProgress = false;
            window.fileUploadState.isUploading = false;
            
            console.log(`✅ FileUpload終了検出 (${duration}ms)`);
            
            // アップロード終了を通知
            document.dispatchEvent(new CustomEvent('fileupload:end', {
                detail: { duration: duration }
            }));
            
            // アップロードタイムアウトをクリア
            clearTimeout(uploadTimeoutId);
        }
    }
    
    // アップロードタイムアウト監視（30秒）
    let uploadTimeoutId;
    
    document.addEventListener('fileupload:start', function() {
        uploadTimeoutId = setTimeout(function() {
            if (uploadInProgress) {
                console.warn('⚠️ FileUploadタイムアウト - 強制終了');
                window.fileUploadState.errors.push('Upload timeout after 30 seconds');
                detectUploadEnd();
                
                // エラー通知を表示
                showUploadError('アップロードがタイムアウトしました。再試行してください。');
            }
        }, 30000);
    });
    
    // エラー表示関数
    function showUploadError(message) {
        // Filament通知がある場合はそれを使用
        if (window.$wire && typeof window.$wire.notify === 'function') {
            window.$wire.notify('error', message);
        } else {
            // フォールバック: アラート
            alert(message);
        }
    }
    
    // Livewire/Alpineイベントリスナー
    document.addEventListener('livewire:init', function() {
        console.log('🔥 Livewire初期化後 - FileUpload保護を強化');
        
        // Livewireのアップロードイベントをリッスン
        if (window.Livewire) {
            Livewire.hook('morph.updated', () => {
                // DOM更新後にアップロード状況を再チェック
                setTimeout(checkUploadStatus, 100);
            });
            
            Livewire.hook('request', ({ uri, options, payload, respond, succeed, fail }) => {
                // FileUpload関連のリクエストを検出
                if (payload.updates && payload.updates.some(update => 
                    update.type === 'callMethod' && 
                    (update.payload.method.includes('upload') || 
                     update.payload.method.includes('file'))
                )) {
                    console.log('📡 FileUpload関連リクエスト検出');
                    detectUploadStart();
                    
                    // レスポンス後にアップロード終了を検出
                    succeed(({ status, response }) => {
                        setTimeout(detectUploadEnd, 500);
                    });
                    
                    fail(({ status, content, preventDefault }) => {
                        setTimeout(detectUploadEnd, 500);
                        window.fileUploadState.errors.push(`Upload failed: ${status}`);
                    });
                }
            });
        }
    });
    
    // アップロード状況の定期チェック
    function checkUploadStatus() {
        const loadingElements = document.querySelectorAll('[wire\\:loading]:not([style*="display: none"])');
        const progressElements = document.querySelectorAll('[role="progressbar"], .filepond--progress');
        const hasActiveUpload = loadingElements.length > 0 || progressElements.length > 0;
        
        if (hasActiveUpload && !uploadInProgress) {
            detectUploadStart();
        } else if (!hasActiveUpload && uploadInProgress) {
            detectUploadEnd();
        }
    }
    
    // 定期的なステータスチェック（500ms間隔）
    setInterval(checkUploadStatus, 500);
    
    console.log('✅ FileUpload保護スクリプト初期化完了');
});

// IMEスクリプトとの連携用のグローバル関数
window.isFileUploadInProgress = function() {
    return window.fileUploadState && window.fileUploadState.isUploading;
};

// ページアンロード時の警告
window.addEventListener('beforeunload', function(e) {
    if (window.fileUploadState && window.fileUploadState.isUploading) {
        e.preventDefault();
        e.returnValue = 'ファイルアップロード中です。ページを離れますか？';
        return 'ファイルアップロード中です。ページを離れますか？';
    }
});