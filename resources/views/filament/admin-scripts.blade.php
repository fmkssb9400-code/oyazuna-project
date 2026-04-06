<script>
// TinyMCE設定の強制適用
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎯 TinyMCE設定適用開始');
    
    // TinyMCEが読み込まれるまで待機
    function waitForTinyMCE() {
        if (typeof tinymce !== 'undefined') {
            console.log('✅ TinyMCE検出 - 設定適用中');
            
            // グローバル設定を事前に設定
            if (!window.tinyMCEGlobalSettings) {
                window.tinyMCEGlobalSettings = {
                    images_upload_url: '/admin/upload-image',
                    images_upload_credentials: true,
                    automatic_uploads: true,
                    paste_data_images: true,
                    image_uploadtab: true,
                    file_picker_types: 'image',
                    images_file_types: 'jpg,jpeg,png,gif,webp',
                    convert_urls: false,
                    relative_urls: false,
                    
                    // 画像ダイアログの設定
                    image_advtab: true,
                    image_title: true,
                    image_description: false,
                    image_dimensions: false,
                    
                    // カスタムアップロードハンドラー
                    images_upload_handler: function(blobInfo, success, failure) {
                        console.log('📤 TinyMCE upload handler called:', blobInfo.filename());
                        
                        var xhr = new XMLHttpRequest();
                        var formData = new FormData();
                        
                        xhr.withCredentials = true;
                        xhr.open('POST', '/admin/upload-image');
                        
                        var token = document.querySelector('meta[name="csrf-token"]');
                        if (token) {
                            xhr.setRequestHeader('X-CSRF-TOKEN', token.getAttribute('content'));
                        } else {
                            failure('CSRF token not found');
                            return;
                        }
                        
                        xhr.onload = function() {
                            console.log('📥 Upload response status:', xhr.status);
                            if (xhr.status === 200) {
                                try {
                                    var json = JSON.parse(xhr.responseText);
                                    console.log('📥 Upload response data:', json);
                                    if (json.location) {
                                        console.log('✅ Upload success:', json.location);
                                        success(json.location);
                                    } else if (json.error) {
                                        console.error('❌ Server error:', json.error);
                                        failure(json.error);
                                    } else {
                                        console.error('❌ Invalid response format:', json);
                                        failure('Invalid response format');
                                    }
                                } catch (e) {
                                    console.error('❌ JSON parse error:', e, xhr.responseText);
                                    failure('JSON parse error: ' + e.message);
                                }
                            } else {
                                console.error('❌ HTTP Error:', xhr.status, xhr.responseText);
                                failure('HTTP Error: ' + xhr.status + ' - ' + xhr.responseText);
                            }
                        };
                        
                        xhr.onerror = function() {
                            console.error('❌ Network error');
                            failure('Network error occurred');
                        };
                        
                        formData.append('file', blobInfo.blob(), blobInfo.filename());
                        xhr.send(formData);
                    }
                };
            }
            
            // TinyMCEの初期化をフック
            var originalInit = tinymce.init;
            tinymce.init = function(config) {
                console.log('🔧 TinyMCE.init intercepted');
                // 設定をマージ - window.tinymceConfigも含める
                var mergedConfig = Object.assign({}, config, window.tinyMCEGlobalSettings, window.tinymceConfig);
                
                // アップロードタブの設定を強制
                mergedConfig.image_uploadtab = true;
                mergedConfig.file_picker_types = 'image';
                mergedConfig.automatic_uploads = true;
                mergedConfig.paste_data_images = true;
                mergedConfig.images_upload_url = '/admin/upload-image';
                mergedConfig.images_upload_credentials = true;
                
                // 強制的にツールバーとボタンを設定
                mergedConfig.toolbar = 'undo redo | formatselect | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image table media | insertCTA insertTable | code preview fullscreen';
                
                console.log('🔧 Merged config with upload tab:', mergedConfig);
                console.log('🔧 Upload tab enabled:', mergedConfig.image_uploadtab);
                console.log('🔧 Upload URL:', mergedConfig.images_upload_url);
                
                return originalInit.call(this, mergedConfig);
            };
            
            console.log('🎯 TinyMCE設定フック完了');
        } else {
            console.log('⏳ TinyMCE未検出 - 再試行中...');
            setTimeout(waitForTinyMCE, 100);
        }
    }
    
    waitForTinyMCE();
});

(function() {
    console.log('🖼️ 画像サイズ変更機能 ver2.0 開始');
    
    let activeToolbar = null;
    
    // === 画像サイズ変更機能 ===
    
    // サイズ変更ツールバーを作成
    function createSizeToolbar(img) {
        console.log('🔧 サイズツールバー作成中...', img.src);
        
        // 既存のツールバーを削除
        removeSizeToolbar();
        
        const toolbar = document.createElement('div');
        toolbar.className = 'image-resize-toolbar';
        toolbar.innerHTML = `
            <div style="display: flex; gap: 4px; align-items: center;">
                <span style="color: white; font-size: 11px; margin-right: 6px;">サイズ:</span>
                <button type="button" class="size-btn" data-size="sm" style="background: ${getCurrentSize(img) === 'sm' ? '#3b82f6' : 'rgba(255,255,255,0.2)'}; color: white; border: none; padding: 4px 8px; border-radius: 3px; font-size: 11px; cursor: pointer;">小</button>
                <button type="button" class="size-btn" data-size="md" style="background: ${getCurrentSize(img) === 'md' ? '#3b82f6' : 'rgba(255,255,255,0.2)'}; color: white; border: none; padding: 4px 8px; border-radius: 3px; font-size: 11px; cursor: pointer;">中</button>
                <button type="button" class="size-btn" data-size="lg" style="background: ${getCurrentSize(img) === 'lg' ? '#3b82f6' : 'rgba(255,255,255,0.2)'}; color: white; border: none; padding: 4px 8px; border-radius: 3px; font-size: 11px; cursor: pointer;">大</button>
            </div>
        `;
        
        // ツールバーのスタイル
        toolbar.style.cssText = `
            position: fixed;
            background: rgba(0, 0, 0, 0.8);
            border-radius: 6px;
            padding: 8px;
            z-index: 9999;
            backdrop-filter: blur(4px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            font-family: system-ui, sans-serif;
        `;
        
        // 画像の位置を取得してツールバーを配置
        const rect = img.getBoundingClientRect();
        toolbar.style.left = (rect.left + rect.width / 2 - 80) + 'px';
        toolbar.style.top = (rect.top - 45) + 'px';
        
        // ボタンにイベントリスナーを追加
        toolbar.querySelectorAll('.size-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const newSize = this.dataset.size;
                console.log('🔄 サイズ変更実行:', newSize);
                
                changeImageSize(img, newSize);
                
                // ボタンの見た目を更新
                toolbar.querySelectorAll('.size-btn').forEach(b => {
                    b.style.background = 'rgba(255,255,255,0.2)';
                });
                this.style.background = '#3b82f6';
            });
        });
        
        document.body.appendChild(toolbar);
        activeToolbar = toolbar;
        
        console.log('✅ ツールバー表示完了');
        return toolbar;
    }
    
    // ツールバーを削除
    function removeSizeToolbar() {
        if (activeToolbar) {
            activeToolbar.remove();
            activeToolbar = null;
        }
        document.querySelectorAll('.image-resize-toolbar').forEach(t => t.remove());
    }
    
    // 現在のサイズを取得
    function getCurrentSize(img) {
        // 1. data-size属性から取得を試行
        const dataSize = img.getAttribute('data-size');
        if (dataSize && ['sm', 'md', 'lg'].includes(dataSize)) {
            return dataSize;
        }
        
        // 2. Trix attributesから取得を試行
        const figure = img.closest('figure[data-trix-attributes]');
        if (figure) {
            try {
                const attributes = JSON.parse(figure.getAttribute('data-trix-attributes'));
                if (attributes.imageSize && ['sm', 'md', 'lg'].includes(attributes.imageSize)) {
                    return attributes.imageSize;
                }
            } catch (e) {
                console.log('⚠️ Trix attributes読み取りエラー:', e);
            }
        }
        
        // 3. CSSクラスから取得を試行
        if (img.classList.contains('img-sm') || figure?.classList.contains('img-sm')) return 'sm';
        if (img.classList.contains('img-lg') || figure?.classList.contains('img-lg')) return 'lg';
        
        // 4. デフォルトは中サイズ
        return 'md';
    }
    
    // 画像サイズを変更
    function changeImageSize(img, size) {
        console.log('🚀 === 画像サイズ変更処理開始 ===');
        console.log('📐 サイズ:', size);
        console.log('🖼️ 画像:', img.src.substring(img.src.lastIndexOf('/') + 1));
        
        // 変更マークを付ける（フォーム送信時の検出用）
        img.setAttribute('data-size-modified', 'true');
        
        // サイズスタイルを適用
        applySizeStyles(img, size);
        
        // TRIX対応: data-trix-attributesにサイズ情報を保存
        updateTrixAttributes(img, size);
        
        // 最終チェック：実際に更新されたか確認
        setTimeout(() => {
            const figure = img.closest('figure[data-trix-attributes]');
            if (figure) {
                const attr = figure.getAttribute('data-trix-attributes');
                console.log('🔍 最終確認 - data-trix-attributes:', attr);
                try {
                    const parsed = JSON.parse(attr);
                    if (parsed.imageSize === size) {
                        console.log('✅ 確認成功: imageSize =', parsed.imageSize);
                    } else {
                        console.log('❌ 確認失敗: imageSize =', parsed.imageSize, '期待値:', size);
                    }
                } catch (e) {
                    console.log('❌ JSON解析エラー:', e);
                }
            }
        }, 100);
        
        // IMPORTANT: Filamentのフィールドを強制的に更新してHTMLコンテンツを保存
        triggerEditorUpdate();
        
        // 追加の確実な同期処理
        setTimeout(() => {
            // Filamentのフォーム状態も強制更新
            const form = document.querySelector('form');
            if (form) {
                // フォームのLivewireコンポーネントを探して状態更新
                const livewireEl = form.closest('[wire\\:id]');
                if (livewireEl && window.Livewire) {
                    const componentId = livewireEl.getAttribute('wire:id');
                    if (componentId) {
                        try {
                            // Livewireコンポーネントの状態を強制更新
                            window.Livewire.find(componentId).call('$refresh');
                            console.log('🔄 Livewire状態強制更新実行');
                        } catch (e) {
                            console.log('⚠️ Livewire更新エラー:', e);
                        }
                    }
                }
            }
        }, 200);
        
        // 通知表示
        showSaveReminder();
        
        console.log('✅ サイズ変更処理完了:', size);
    }
    
    // 保存リマインダー表示
    function showSaveReminder() {
        // 既存の通知を削除
        const existingReminder = document.querySelector('.size-save-reminder');
        if (existingReminder) {
            existingReminder.remove();
        }
        
        // 新しい通知を作成
        const reminder = document.createElement('div');
        reminder.className = 'size-save-reminder';
        reminder.innerHTML = '💾 画像サイズを変更しました。保存してください。';
        reminder.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #f59e0b;
            color: white;
            padding: 12px 16px;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            z-index: 10000;
            font-weight: 500;
            animation: slideIn 0.3s ease-out;
        `;
        
        document.body.appendChild(reminder);
        
        // 5秒後に自動で消す
        setTimeout(() => {
            reminder.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => reminder.remove(), 300);
        }, 5000);
    }
    
    // Trixのattributesにサイズ情報を保存し、確実にTrixに反映する
    function updateTrixAttributes(img, size) {
        try {
            console.log('🎯 updateTrixAttributes開始:', size);
            
            // 親のfigure要素を取得（Trixの画像構造）
            const figure = img.closest('figure[data-trix-attributes]');
            if (!figure) {
                console.log('❌ Trix figure要素が見つかりません');
                return;
            }
            
            let attributes = {};
            
            // 既存のattributesを解析
            try {
                const existingAttr = figure.getAttribute('data-trix-attributes');
                if (existingAttr) {
                    attributes = JSON.parse(existingAttr);
                    console.log('📖 既存attributes:', attributes);
                }
            } catch (e) {
                console.log('⚠️ 既存attributes解析エラー:', e);
                attributes = {};
            }
            
            // サイズ情報を追加
            attributes.imageSize = size;
            console.log('📝 新しいattributes:', attributes);
            
            // data-trix-attributesを更新
            const newAttributesJson = JSON.stringify(attributes);
            figure.setAttribute('data-trix-attributes', newAttributesJson);
            
            console.log('✅ DOM属性更新完了');
            
            // figureにもクラスを追加（CSS用）
            figure.classList.remove('img-sm', 'img-md', 'img-lg');
            figure.classList.add(`img-${size}`);
            
            // === ここが重要：Trixエディターの内部HTMLを直接書き換える ===
            const trixEditor = document.querySelector('trix-editor');
            if (trixEditor && trixEditor.editor) {
                console.log('🔧 Trix内部HTML直接更新開始');
                
                try {
                    // Trixの内部コンテンツを取得
                    const trixContent = trixEditor.querySelector('.trix-content');
                    if (trixContent) {
                        // 現在のHTMLを取得
                        let currentHTML = trixContent.innerHTML;
                        console.log('📄 現在のTrix HTML長さ:', currentHTML.length);
                        
                        // 古いdata-trix-attributes を新しいものに置換
                        const oldPattern = /data-trix-attributes="[^"]*"/g;
                        const newAttributesEscaped = newAttributesJson.replace(/"/g, '&quot;');
                        const newHTML = currentHTML.replace(oldPattern, `data-trix-attributes="${newAttributesEscaped}"`);
                        
                        if (newHTML !== currentHTML) {
                            // HTMLを直接更新
                            trixContent.innerHTML = newHTML;
                            console.log('✅ Trix内部HTML更新完了');
                            
                            // === CRITICAL: 確実にTrixの状態を更新する ===
                            
                            // 1. Trixエディターのdocumentを強制リフレッシュ
                            if (trixEditor.editor && trixEditor.editor.reparse) {
                                try {
                                    trixEditor.editor.reparse();
                                    console.log('🔄 Trix reparse 実行');
                                } catch (e) {
                                    console.log('⚠️ Trix reparse エラー:', e);
                                }
                            }
                            
                            // 2. 複数のイベントを発火してFilamentに確実に通知
                            ['input', 'change', 'blur', 'trix-change', 'trix-attachment-add'].forEach(eventType => {
                                trixEditor.dispatchEvent(new Event(eventType, { bubbles: true }));
                            });
                            
                            // 3. Trixの内部値も直接更新
                            if (trixEditor.value !== undefined) {
                                trixEditor.value = newHTML;
                            }
                            
                            // 4. 隠しフィールドも直接更新
                            const hiddenField = document.querySelector('input[name="data.content"], textarea[name="data.content"], input[name="content"], textarea[name="content"]');
                            if (hiddenField) {
                                hiddenField.value = newHTML;
                                hiddenField.dispatchEvent(new Event('change', { bubbles: true }));
                                hiddenField.dispatchEvent(new Event('input', { bubbles: true }));
                                console.log('✅ 隠しフィールド直接更新');
                            }
                            
                            console.log('🎯 Trix完全同期処理実行完了');
                            
                        } else {
                            console.log('⚠️ HTML変更なし - 正規表現マッチしませんでした');
                        }
                    }
                } catch (updateError) {
                    console.error('❌ Trix内部更新エラー:', updateError);
                }
                
            } else {
                console.log('⚠️ Trixエディターインスタンスが見つかりません');
            }
            
            console.log('🎉 updateTrixAttributes完了');
            
        } catch (error) {
            console.error('❌ updateTrixAttributes全体エラー:', error);
        }
    }
    
    // 画像にサイズスタイルを適用
    function applySizeStyles(img, size) {
        // 既存のサイズクラスを削除
        img.classList.remove('img-sm', 'img-md', 'img-lg');
        
        // 新しいサイズクラスを追加
        img.classList.add(`img-${size}`);
        
        // data属性も更新
        img.setAttribute('data-size', size);
        
        // figure要素にもクラスを適用
        const figure = img.closest('figure');
        if (figure) {
            figure.classList.remove('img-sm', 'img-md', 'img-lg');
            figure.classList.add(`img-${size}`);
        }
        
        // 見た目を反映
        switch(size) {
            case 'sm':
                img.style.maxWidth = '320px';
                img.style.width = '320px';
                break;
            case 'lg':
                img.style.maxWidth = '900px';
                img.style.width = '100%';
                break;
            default: // md
                img.style.maxWidth = '600px';
                img.style.width = '600px';
                break;
        }
    }
    
    // エディタの内容を強制的に保存させる関数（Trix専用）
    function triggerEditorUpdate() {
        try {
            const trixEditor = document.querySelector('trix-editor');
            const proseMirror = document.querySelector('.ProseMirror');
            
            if (trixEditor && trixEditor.editor) {
                console.log('🎯 Trixエディター検出 - 直接操作開始');
                
                // 1. Trixエディターの現在のHTML取得
                const currentHTML = trixEditor.editor.getDocument().toString();
                console.log('📝 現在のTrix内容:', currentHTML.substring(0, 300) + '...');
                
                // 2. TrixのDOMを直接更新（重要：これが保存される）
                const trixElement = trixEditor.querySelector('.trix-content');
                if (trixElement) {
                    const updatedHTML = trixElement.innerHTML;
                    console.log('🔧 Trix DOM直接更新:', updatedHTML.substring(0, 200) + '...');
                    
                    // 3. Trixの内部状態を強制同期
                    try {
                        // Trixエディターに微細な変更を加えて内部状態を更新
                        trixEditor.editor.insertHTML(' ');
                        trixEditor.editor.deleteInDirection('backward');
                        
                        console.log('✅ Trix内部状態同期完了');
                    } catch (e) {
                        console.warn('⚠️ Trix内部状態同期警告:', e);
                    }
                }
                
                // 4. すべての関連イベントを発火
                const events = ['trix-change', 'input', 'change'];
                events.forEach(eventType => {
                    trixEditor.dispatchEvent(new Event(eventType, { bubbles: true }));
                });
                
                // 5. 隠しフィールドも直接更新
                const hiddenField = document.querySelector('input[name="data.content"], textarea[name="data.content"], input[name="content"], textarea[name="content"]');
                if (hiddenField) {
                    const finalHTML = trixEditor.querySelector('.trix-content').innerHTML;
                    hiddenField.value = finalHTML;
                    hiddenField.dispatchEvent(new Event('change', { bubbles: true }));
                    console.log('✅ 隠しフィールド直接更新完了');
                }
                
                // 6. Livewireコンポーネントがあれば更新
                const livewireComponent = trixEditor.closest('[wire\\:id]');
                if (livewireComponent) {
                    // Alpine.jsのdata更新も試行
                    if (window.Alpine) {
                        const alpineData = window.Alpine.$data(livewireComponent);
                        if (alpineData && alpineData.state && alpineData.state.content !== undefined) {
                            alpineData.state.content = trixEditor.querySelector('.trix-content').innerHTML;
                            console.log('✅ Alpine.js state更新完了');
                        }
                    }
                }
                
                console.log('🎉 Trix完全同期処理完了');
                
            } else if (proseMirror) {
                console.log('🎯 ProseMirror検出 - 従来処理');
                // 従来のProseMirror処理...
                proseMirror.dispatchEvent(new Event('input', { bubbles: true }));
            } else {
                console.log('❌ エディターが見つかりません');
            }
            
        } catch (error) {
            console.error('❌ エディタ更新エラー:', error);
        }
    }
    
    // 画像にクリックイベントを設定
    function setupImageClick(img) {
        // 重複防止
        if (img.dataset.resizeEnabled) {
            console.log('⚠️ 既に機能追加済み:', img.src);
            return;
        }
        
        console.log('🔧 画像にクリック機能追加中:', img.src);
        
        // 古いイベントリスナーを削除
        const oldHandler = img._clickHandler;
        if (oldHandler) {
            img.removeEventListener('click', oldHandler);
        }
        
        // 新しいクリックハンドラー（保存時に混入しないよう文字列化を避ける）
        const clickHandler = function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('📷 画像クリック検知!!! src:', this.src.substring(this.src.lastIndexOf('/') + 1));
            
            // 全ての画像から選択状態を削除
            document.querySelectorAll('img').forEach(function(i) {
                i.style.border = '';
                i.style.boxShadow = '';
            });
            
            // この画像を選択状態に
            this.style.border = '3px solid #3b82f6';
            this.style.boxShadow = '0 0 0 6px rgba(59, 130, 246, 0.2)';
            
            // ツールバー表示
            createSizeToolbar(this);
        };
        
        // イベントリスナー登録
        img.addEventListener('click', clickHandler, { passive: false });
        
        // 参照を保存（削除用）- ただし文字列化は避ける
        img._clickHandler = clickHandler; // 直接プロパティとして保存（データセットに入れない）
        img.setAttribute('data-resize-enabled', 'true'); // HTMLに保存されないよう注意
        
        // スタイル設定
        img.style.cursor = 'pointer';
        img.style.transition = 'all 0.2s ease';
        
        // 保存されたサイズ情報を復元
        const savedSize = getCurrentSize(img);
        applySizeStyles(img, savedSize);
        
        console.log('📐 サイズ復元完了:', savedSize);
        
        console.log('✅ 画像クリック機能追加完了:', img.src.substring(img.src.lastIndexOf('/') + 1));
    }
    
    // 画像の下の不要なテキストを削除
    function removeImageMetaText() {
        console.log('🧹 画像の下のテキスト削除開始');
        
        // 画像要素を取得
        const images = document.querySelectorAll('.ProseMirror img');
        
        images.forEach(img => {
            // 画像の直後の兄弟要素をチェック
            let nextElement = img.nextElementSibling;
            let textNodesToRemove = [];
            
            // 画像の後の要素を最大5個まで確認
            for (let i = 0; i < 5 && nextElement; i++) {
                const text = nextElement.textContent;
                
                // ファイル関連のテキストパターンをチェック
                if (text && (
                    text.includes('blog') ||
                    text.includes('.png') ||
                    text.includes('.jpg') ||
                    text.includes('.jpeg') ||
                    text.includes('KB') ||
                    text.includes('MB') ||
                    text.includes('createSizeToolbar') ||
                    text.includes('data-resize-enabled') ||
                    text.match(/\d+\.\d+\s*KB/) ||
                    text.match(/\d+\.\d+\s*MB/) ||
                    text.match(/img\d+\./) ||
                    text.match(/blog\d+/)
                )) {
                    console.log('🗑️ 削除対象テキスト:', text);
                    textNodesToRemove.push(nextElement);
                }
                
                nextElement = nextElement.nextElementSibling;
            }
            
            // 画像直後のテキストノードもチェック
            let textNode = img.nextSibling;
            while (textNode && textNode.nodeType === Node.TEXT_NODE) {
                const text = textNode.textContent.trim();
                if (text && (
                    text.includes('blog') ||
                    text.includes('.png') ||
                    text.includes('KB') ||
                    text.includes('createSizeToolbar')
                )) {
                    console.log('🗑️ 削除対象テキストノード:', text);
                    const nodeToRemove = textNode;
                    textNode = textNode.nextSibling;
                    nodeToRemove.remove();
                } else {
                    textNode = textNode.nextSibling;
                }
            }
            
            // 要素を削除
            textNodesToRemove.forEach(element => {
                element.remove();
            });
        });
        
        console.log('✅ 画像テキスト削除完了');
    }

    // すべての画像に機能を追加
    function setupAllImages() {
        // より幅広い画像セレクタで検索
        const selectors = [
            '.ProseMirror img',
            '.fi-fo-rich-editor img', 
            '[data-tiptap-attachment] img',
            '.tiptap-editor img'
        ];
        
        let allImages = [];
        selectors.forEach(selector => {
            const images = document.querySelectorAll(selector);
            console.log(`🔍 ${selector} 検索結果:`, images.length, '個');
            allImages = [...allImages, ...images];
        });
        
        // 重複を除去
        const uniqueImages = [...new Set(allImages)];
        console.log('🔍 全画像検索結果:', uniqueImages.length, '個（重複除去後）');
        
        uniqueImages.forEach((img, index) => {
            console.log(`📷 画像 ${index + 1}:`, img.src, '| クラス:', img.className);
            setupImageClick(img);
        });
        
        return uniqueImages.length;
    }
    
    // 外部クリックでツールバーを非表示
    function setupDocumentClick() {
        document.addEventListener('click', function(e) {
            console.log('🖱️ ドキュメントクリック:', e.target.tagName, e.target.className);
            
            if (!e.target.closest('.image-resize-toolbar') && e.target.tagName !== 'IMG') {
                console.log('🚮 ツールバー削除 & 選択解除');
                removeSizeToolbar();
                
                // 選択状態も解除
                document.querySelectorAll('img').forEach(img => {
                    img.style.border = '';
                    img.style.boxShadow = '';
                });
            }
        });
    }
    
    // === カスタム画像アップロード機能 ===
    
    // モーダルを作成
    function createUploadModal() {
        const modal = document.createElement('div');
        modal.innerHTML = `
            <div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); z-index: 10000; display: flex; align-items: center; justify-content: center;">
                <div style="background: white; border-radius: 8px; padding: 24px; width: 400px; max-width: 90vw;">
                    <h3 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 600;">📷 画像を挿入</h3>
                    
                    <div style="margin-bottom: 16px;">
                        <input type="file" id="modal-file-input" accept="image/*" style="width: 100%; padding: 12px; border: 2px dashed #ddd; border-radius: 6px; cursor: pointer;">
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500;">サイズを選択:</label>
                        <div style="display: flex; gap: 12px;">
                            <label style="display: flex; align-items: center; cursor: pointer;">
                                <input type="radio" name="upload-size" value="sm" style="margin-right: 6px;">
                                小 (320px)
                            </label>
                            <label style="display: flex; align-items: center; cursor: pointer;">
                                <input type="radio" name="upload-size" value="md" checked style="margin-right: 6px;">
                                中 (600px)
                            </label>
                            <label style="display: flex; align-items: center; cursor: pointer;">
                                <input type="radio" name="upload-size" value="lg" style="margin-right: 6px;">
                                大 (900px)
                            </label>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 12px; justify-content: flex-end;">
                        <button type="button" id="modal-cancel" style="padding: 8px 16px; background: #f5f5f5; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;">キャンセル</button>
                        <button type="button" id="modal-upload" style="padding: 8px 16px; background: #3b82f6; color: white; border: none; border-radius: 4px; cursor: pointer;">アップロード</button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // イベントリスナー
        modal.querySelector('#modal-cancel').onclick = () => modal.remove();
        modal.querySelector('#modal-upload').onclick = function() {
            const file = modal.querySelector('#modal-file-input').files[0];
            const size = modal.querySelector('input[name="upload-size"]:checked').value;
            
            if (!file) {
                alert('ファイルを選択してください');
                return;
            }
            
            uploadAndInsert(file, size);
            modal.remove();
        };
        
        return modal;
    }
    
    // アップロードして挿入
    function uploadAndInsert(file, size) {
        console.log('📤 アップロード開始:', file.name, size);
        
        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');
        
        fetch('/admin/upload-image', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.url) {
                insertImage(data.url, size);
                console.log('✅ アップロード成功');
            } else {
                throw new Error(data.message || 'アップロード失敗');
            }
        })
        .catch(error => {
            console.error('❌ アップロードエラー:', error);
            alert('アップロードに失敗しました: ' + error.message);
        });
    }
    
    // 画像を挿入
    function insertImage(url, size) {
        const editor = document.querySelector('.ProseMirror');
        if (!editor) return;
        
        const img = document.createElement('img');
        img.src = url;
        img.alt = '';
        img.classList.add(`img-${size}`);
        img.setAttribute('data-size', size);
        img.style.cssText = 'display: block; margin: 16px auto; cursor: pointer;';
        
        // エディターに挿入
        const selection = window.getSelection();
        if (selection.rangeCount > 0) {
            const range = selection.getRangeAt(0);
            range.insertNode(img);
        } else {
            editor.appendChild(img);
        }
        
        // 機能を追加
        setupImageClick(img);
        
        console.log('✅ 画像挿入完了:', size);
    }
    
    // カスタムボタンを追加
    function addCustomButton() {
        const toolbars = document.querySelectorAll('.fi-fo-rich-editor [role="toolbar"]');
        
        toolbars.forEach(toolbar => {
            if (toolbar.querySelector('.custom-image-btn')) return;
            
            const btn = document.createElement('button');
            btn.className = 'custom-image-btn';
            btn.type = 'button';
            btn.innerHTML = '📷';
            btn.title = '画像挿入（サイズ選択）';
            btn.style.cssText = `
                background: #00a32a;
                color: white;
                border: none;
                border-radius: 3px;
                padding: 6px 8px;
                margin-right: 4px;
                cursor: pointer;
                font-size: 14px;
            `;
            
            btn.onclick = function(e) {
                e.preventDefault();
                createUploadModal();
            };
            
            toolbar.insertBefore(btn, toolbar.firstChild);
            
            console.log('✅ カスタムボタン追加');
        });
    }
    
    // === 初期化 ===
    
    function initialize() {
        console.log('🚀 画像機能初期化中...');
        
        // 短時間で初期化試行
        let initCount = 0;
        const tryInit = () => {
            initCount++;
            console.log(`🔄 初期化試行 ${initCount}/10`);
            
            addCustomButton();
            const imageCount = setupAllImages();
            removeImageMetaText();
            setupDocumentClick();
            
            if (imageCount > 0 || initCount >= 10) {
                console.log(`✅ 初期化完了 (${imageCount}個の画像を処理)`);
                
                // 定期的に新しい画像をチェックとテキスト削除
                setInterval(() => {
                    const newCount = setupAllImages();
                    removeImageMetaText();
                    if (newCount > 0) {
                        console.log(`🔄 新しい画像を検出: ${newCount}個`);
                    }
                }, 2000);
            } else {
                setTimeout(tryInit, 500);
            }
        };
        
        setTimeout(tryInit, 100);
    }
    
    // フォーム送信時の確実な更新処理
    function setupFormSubmissionHandler() {
        // NOTE: Filamentはlivewireで動作するため、通常のform submitイベントをインターセプトしない
        // 代わりにlivewireイベントとボタンクリックで同期処理を行う
        console.log('ℹ️ フォーム送信ハンドラーはFilament/Livewire対応のため無効化');
        
        // Filamentの保存ボタン監視（Livewire対応）
        document.addEventListener('click', function(e) {
            // Filamentの保存/更新ボタンを検出
            if (e.target.matches('button[type="submit"]') || 
                e.target.closest('button[type="submit"]') ||
                e.target.matches('[wire\\:click*="save"]') ||
                e.target.closest('[wire\\:click*="save"]') ||
                e.target.matches('[wire\\:click*="update"]') ||
                e.target.closest('[wire\\:click*="update"]')) {
                
                console.log('💾 Filament保存ボタンクリック検出');
                
                // 画像サイズ変更があった場合のみ同期処理
                const modifiedImages = document.querySelectorAll('img[data-size-modified="true"]');
                if (modifiedImages.length > 0) {
                    console.log('🔄 画像サイズ変更を同期中...');
                    triggerEditorUpdate();
                    modifiedImages.forEach(img => img.removeAttribute('data-size-modified'));
                }
            }
        });
        
        // Livewireイベント監視
        document.addEventListener('livewire:before', function() {
            console.log('🔄 Livewire処理開始前 - 画像データ同期');
            triggerEditorUpdate();
        });
        
        console.log('✅ Filament/Livewire対応保存ハンドラー設定完了');
    }
    
    // 実行
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initialize();
            setupFormSubmissionHandler();
        });
    } else {
        initialize();
        setupFormSubmissionHandler();
    }
    
    // ページ変更時も実行
    document.addEventListener('livewire:navigated', function() {
        initialize();
        setupFormSubmissionHandler();
    });
    
})();
</script>

<!-- カスタム画像ボタン上書き処理を削除 - TinyMCEの標準機能を使用 -->

<style>
/* === 管理画面用スタイル === */

/* 1) 画像に対してのみ不要なUI文言を選択的に非表示 */
.fi-fo-rich-editor .ProseMirror img + .ProseMirror-selectednode::after,
.fi-fo-rich-editor [data-tiptap-attachment]:has(img)::after,
.fi-fo-rich-editor [data-tiptap-attachment]:has(img) .fi-attachment-actions,
.fi-fo-rich-editor [data-tiptap-attachment]:has(img) .fi-attachment-info {
    display: none !important;
    visibility: hidden !important;
}

/* 画像の Add a caption... のみ非表示 */
.fi-fo-rich-editor .ProseMirror img::after,
.fi-fo-rich-editor .ProseMirror [data-placeholder]:has(img)::after {
    display: none !important;
}

/* 画像のRemove/削除ボタンのみ非表示 */
.fi-fo-rich-editor img ~ button[title*="Remove"],
.fi-fo-rich-editor img ~ button[title*="削除"],
.fi-fo-rich-editor [data-tiptap-attachment]:has(img) button[title*="Remove"],
.fi-fo-rich-editor [data-tiptap-attachment]:has(img) button[title*="削除"] {
    display: none !important;
}

/* 画像のファイル名・サイズ表示のみ非表示 */
.fi-fo-rich-editor [data-tiptap-attachment]:has(img) .attachment-filename,
.fi-fo-rich-editor [data-tiptap-attachment]:has(img) .attachment-filesize,
.fi-fo-rich-editor [data-tiptap-attachment]:has(img) .fi-attachment-info {
    display: none !important;
}

/* 2) WordPress風画像サイズクラス（管理画面） */
.ProseMirror img {
    display: block !important;
    margin: 16px auto !important;
    height: auto !important;
    border-radius: 4px !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
    cursor: pointer !important;
}

/* 小サイズ (320px) */
.ProseMirror img.img-sm {
    max-width: 320px !important;
    width: 320px !important;
}

/* 中サイズ (600px) - デフォルト */
.ProseMirror img.img-md,
.ProseMirror img:not(.img-sm):not(.img-lg) {
    max-width: 600px !important;
    width: 600px !important;
}

/* 大サイズ (100% / 900px max) */
.ProseMirror img.img-lg {
    max-width: 900px !important;
    width: 100% !important;
}

/* Figure要素用のサイズクラス（Trix対応） */
.ProseMirror figure.img-sm img {
    max-width: 320px !important;
    width: 320px !important;
}

.ProseMirror figure.img-md img,
.ProseMirror figure:not(.img-sm):not(.img-lg) img {
    max-width: 600px !important;
    width: 600px !important;
}

.ProseMirror figure.img-lg img {
    max-width: 900px !important;
    width: 100% !important;
}

/* 3) 画像サイズツールバースタイル */
.image-size-toolbar {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
}

/* 4) レスポンシブ対応（管理画面） */
@media (max-width: 1024px) {
    .ProseMirror img.img-sm,
    .ProseMirror img.img-md,
    .ProseMirror img.img-lg {
        max-width: 100% !important;
        width: 100% !important;
    }
}

/* === 管理画面でのFilament固有の隠しスタイル === */

/* TiptapのNode Viewの余計な要素を隠す */
.ProseMirror .node-view-wrapper::after,
.ProseMirror .node-view-content::after,
.ProseMirror [data-node-view-wrapper]::after {
    display: none !important;
}

/* プレースホルダーテキスト全般を隠す */
.ProseMirror [data-placeholder]:empty::before,
.ProseMirror .is-editor-empty::before {
    opacity: 0 !important;
}

/* Tiptapの選択状態での不要な要素 */
.ProseMirror .ProseMirror-selectednode > *:not(img) {
    display: none !important;
}

/* ファイル情報表示の完全削除 */
.fi-fo-rich-editor .tiptap-file-attachment,
.fi-fo-rich-editor .file-info,
.fi-fo-rich-editor .file-meta {
    display: none !important;
}

/* 画像下のファイルパス、ファイル名、サイズ情報を完全非表示 */
.ProseMirror img::after,
.ProseMirror img + *,
.ProseMirror img ~ .file-path,
.ProseMirror img ~ .file-name,
.ProseMirror img ~ .file-size,
.ProseMirror img ~ .attachment-meta,
.ProseMirror img ~ .image-meta,
.ProseMirror [data-tiptap-attachment] .attachment-filename,
.ProseMirror [data-tiptap-attachment] .attachment-size,
.ProseMirror [data-tiptap-attachment] .file-details,
.ProseMirror [data-tiptap-attachment]:has(img)::after {
    display: none !important;
    visibility: hidden !important;
    content: none !important;
}

/* Figureキャプション要素で画像の場合のみ非表示 */
.ProseMirror figure:has(img) figcaption,
.ProseMirror figure:has(img) .figure-caption,
.ProseMirror figure:has(img) .image-caption {
    display: none !important;
}

/* createSizeToolbarで生成される要素も非表示 */
.ProseMirror [data-resize-enabled]::after {
    display: none !important;
}

/* 画像要素の直後に生成される可能性のあるテキストノードを隠す */
.ProseMirror img + [data-click-handler]::after,
.ProseMirror img + span,
.ProseMirror img + div.file-info {
    display: none !important;
}

/* より強力な画像関連テキスト非表示 - ファイル名やURLを含むあらゆるテキスト */
.ProseMirror p:has(img) + p:not(:has(img)),
.ProseMirror div:has(img) + div:not(:has(img)),
.ProseMirror img + p,
.ProseMirror img + div,
.ProseMirror img + span,
.ProseMirror img ~ p:empty,
.ProseMirror img ~ div:empty,
.ProseMirror [data-tiptap-attachment] + *:not(img),
.ProseMirror [data-tiptap-attachment] ~ *:not(img):not(p:has(*)) {
    display: none !important;
}

/* 画像の直後の段落でファイルパスやファイル名を含むものを隠す */
.ProseMirror img + p[style*="cursor"],
.ProseMirror img + p[data-resize-enabled] {
    display: none !important;
    visibility: hidden !important;
    height: 0 !important;
    overflow: hidden !important;
}
</style>