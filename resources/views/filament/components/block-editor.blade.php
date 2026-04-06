<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div
        x-data="fullBlockEditor({
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$getStatePath()}')") }},
            uploadUrl: '{{ $getUploadUrl() }}',
            deleteUrl: '{{ $getDeleteUrl() }}',
            csrfToken: '{{ $getCsrfToken() }}'
        })"
        x-init="init()"
        {{ $attributes->merge($getExtraAttributes())->class([
            'full-block-editor-wrapper border border-gray-300 rounded-lg bg-white'
        ]) }}
    >
        <!-- ツールバー -->
        <div class="toolbar bg-gray-50 border-b border-gray-300 p-3">
            <div class="flex flex-wrap items-center gap-2">
                <!-- 見出し -->
                <div class="flex items-center gap-1 pr-3 border-r border-gray-300">
                    <button type="button" @click="setHeading(2)" 
                            :class="{ 'bg-blue-600 text-white': activeStates.heading2, 'bg-white text-gray-700': !activeStates.heading2 }"
                            class="px-2 py-1.5 text-sm font-medium border border-gray-300 rounded hover:bg-gray-50 transition-colors">
                        H2
                    </button>
                    <button type="button" @click="setHeading(3)"
                            :class="{ 'bg-blue-600 text-white': activeStates.heading3, 'bg-white text-gray-700': !activeStates.heading3 }"
                            class="px-2 py-1.5 text-sm font-medium border border-gray-300 rounded hover:bg-gray-50 transition-colors">
                        H3
                    </button>
                    <button type="button" @click="setParagraph()"
                            :class="{ 'bg-blue-600 text-white': activeStates.paragraph, 'bg-white text-gray-700': !activeStates.paragraph }"
                            class="px-2 py-1.5 text-sm font-medium border border-gray-300 rounded hover:bg-gray-50 transition-colors">
                        P
                    </button>
                </div>
                
                <!-- 文字装飾 -->
                <div class="flex items-center gap-1 pr-3 border-r border-gray-300">
                    <button type="button" @click="toggleBold()"
                            :class="{ 'bg-blue-600 text-white': activeStates.bold, 'bg-white text-gray-700': !activeStates.bold }"
                            class="px-2 py-1.5 text-sm font-bold border border-gray-300 rounded hover:bg-gray-50 transition-colors">
                        B
                    </button>
                    <button type="button" @click="toggleItalic()"
                            :class="{ 'bg-blue-600 text-white': activeStates.italic, 'bg-white text-gray-700': !activeStates.italic }"
                            class="px-2 py-1.5 text-sm italic border border-gray-300 rounded hover:bg-gray-50 transition-colors">
                        I
                    </button>
                    <button type="button" @click="addLink()"
                            :class="{ 'bg-blue-600 text-white': activeStates.link, 'bg-white text-gray-700': !activeStates.link }"
                            class="px-2 py-1.5 text-sm border border-gray-300 rounded hover:bg-gray-50 transition-colors">
                        🔗
                    </button>
                </div>
                
                <!-- リスト -->
                <div class="flex items-center gap-1 pr-3 border-r border-gray-300">
                    <button type="button" @click="toggleBulletList()"
                            :class="{ 'bg-blue-600 text-white': activeStates.bulletList, 'bg-white text-gray-700': !activeStates.bulletList }"
                            class="px-2 py-1.5 text-xs border border-gray-300 rounded hover:bg-gray-50 transition-colors">
                        • リスト
                    </button>
                    <button type="button" @click="toggleOrderedList()"
                            :class="{ 'bg-blue-600 text-white': activeStates.orderedList, 'bg-white text-gray-700': !activeStates.orderedList }"
                            class="px-2 py-1.5 text-xs border border-gray-300 rounded hover:bg-gray-50 transition-colors">
                        1. リスト
                    </button>
                    <button type="button" @click="toggleBlockquote()"
                            :class="{ 'bg-blue-600 text-white': activeStates.blockquote, 'bg-white text-gray-700': !activeStates.blockquote }"
                            class="px-2 py-1.5 text-xs border border-gray-300 rounded hover:bg-gray-50 transition-colors">
                        " 引用
                    </button>
                </div>
                
                <!-- メディア・その他 -->
                <div class="flex items-center gap-1">
                    <button type="button" @click="addImage()" 
                            class="px-3 py-1.5 text-sm bg-green-600 text-white border border-green-600 rounded hover:bg-green-700 transition-colors">
                        📷 画像
                    </button>
                    <button type="button" @click="addHorizontalRule()"
                            class="px-2 py-1.5 text-sm bg-white text-gray-700 border border-gray-300 rounded hover:bg-gray-50 transition-colors">
                        ———
                    </button>
                </div>
            </div>
        </div>
        
        <!-- エディタ本体 -->
        <div x-ref="editorContainer" class="editor-content min-h-[400px] p-6">
            <div x-show="!editorReady" class="text-gray-500 text-center py-8">
                エディタを読み込み中...
            </div>
        </div>
        
        <!-- 画像アップロード用の隠しファイル入力 -->
        <input type="file" x-ref="imageInput" @change="handleImageUpload($event)" 
               accept="image/*" style="display: none;">
    </div>
    
    @once
        @push('scripts')
            <script>
                window.fullBlockEditor = function(config) {
                    return {
                        state: config.state || { type: 'doc', content: [] },
                        uploadUrl: config.uploadUrl,
                        deleteUrl: config.deleteUrl,
                        csrfToken: config.csrfToken,
                        editorReady: false,
                        activeStates: {
                            heading2: false,
                            heading3: false,
                            paragraph: false,
                            bold: false,
                            italic: false,
                            link: false,
                            bulletList: false,
                            orderedList: false,
                            blockquote: false
                        },
                        
                        init() {
                            console.log('ブロックエディタ初期化開始');
                            console.log('設定値:', {
                                uploadUrl: this.uploadUrl,
                                deleteUrl: this.deleteUrl, 
                                csrfToken: this.csrfToken,
                                state: this.state
                            });
                            this.initializeEditor();
                        },
                        
                        initializeEditor() {
                            // 基本的なリッチテキストエディタを作成
                            const container = this.$refs.editorContainer;
                            
                            // 編集可能なdivを作成
                            const editor = document.createElement('div');
                            editor.contentEditable = true;
                            editor.className = 'prose prose-lg max-w-none focus:outline-none';
                            editor.style.minHeight = '300px';
                            editor.innerHTML = this.convertStateToHtml(this.state);
                            
                            // イベントリスナーを追加
                            editor.addEventListener('input', () => {
                                this.updateStateFromHtml(editor.innerHTML);
                            });
                            
                            editor.addEventListener('keyup', () => {
                                this.updateActiveStates(editor);
                            });
                            
                            editor.addEventListener('mouseup', () => {
                                this.updateActiveStates(editor);
                            });
                            
                            // プレースホルダー
                            if (!editor.innerHTML.trim()) {
                                editor.innerHTML = '<p>記事の本文を入力してください...</p>';
                            }
                            
                            container.innerHTML = '';
                            container.appendChild(editor);
                            
                            this.editor = editor;
                            this.editorReady = true;
                        },
                        
                        convertStateToHtml(state) {
                            if (!state || !state.content) {
                                return '<p>記事の本文を入力してください...</p>';
                            }
                            
                            let html = '';
                            for (const block of state.content) {
                                html += this.convertBlockToHtml(block);
                            }
                            return html || '<p>記事の本文を入力してください...</p>';
                        },
                        
                        convertBlockToHtml(block) {
                            switch (block.type) {
                                case 'paragraph':
                                    return `<p>${this.convertInlineContent(block.content || [])}</p>`;
                                case 'heading':
                                    const level = block.attrs?.level || 2;
                                    return `<h${level}>${this.convertInlineContent(block.content || [])}</h${level}>`;
                                case 'bulletList':
                                    return `<ul>${(block.content || []).map(item => this.convertBlockToHtml(item)).join('')}</ul>`;
                                case 'orderedList':
                                    return `<ol>${(block.content || []).map(item => this.convertBlockToHtml(item)).join('')}</ol>`;
                                case 'listItem':
                                    return `<li>${(block.content || []).map(item => this.convertBlockToHtml(item)).join('')}</li>`;
                                case 'blockquote':
                                    return `<blockquote>${(block.content || []).map(item => this.convertBlockToHtml(item)).join('')}</blockquote>`;
                                case 'image':
                                    return `<img src="${block.attrs?.src || ''}" alt="${block.attrs?.alt || ''}" style="max-width: 100%; height: auto;">`;
                                case 'horizontalRule':
                                    return '<hr>';
                                default:
                                    return '';
                            }
                        },
                        
                        convertInlineContent(content) {
                            return (content || []).map(item => {
                                if (item.type === 'text') {
                                    let text = item.text || '';
                                    if (item.marks) {
                                        for (const mark of item.marks) {
                                            switch (mark.type) {
                                                case 'bold':
                                                    text = `<strong>${text}</strong>`;
                                                    break;
                                                case 'italic':
                                                    text = `<em>${text}</em>`;
                                                    break;
                                                case 'link':
                                                    text = `<a href="${mark.attrs?.href || '#'}">${text}</a>`;
                                                    break;
                                            }
                                        }
                                    }
                                    return text;
                                }
                                return '';
                            }).join('');
                        },
                        
                        updateStateFromHtml(html) {
                            // シンプルなHTML→JSONコンバート（基本的な構造のみ）
                            const tempDiv = document.createElement('div');
                            tempDiv.innerHTML = html;
                            
                            const content = [];
                            for (const child of tempDiv.children) {
                                content.push(this.convertElementToBlock(child));
                            }
                            
                            this.state = { type: 'doc', content };
                        },
                        
                        convertElementToBlock(element) {
                            const tagName = element.tagName.toLowerCase();
                            
                            switch (tagName) {
                                case 'p':
                                    return {
                                        type: 'paragraph',
                                        content: [{ type: 'text', text: element.textContent || '' }]
                                    };
                                case 'h1':
                                case 'h2':
                                case 'h3':
                                case 'h4':
                                case 'h5':
                                case 'h6':
                                    return {
                                        type: 'heading',
                                        attrs: { level: parseInt(tagName.charAt(1)) },
                                        content: [{ type: 'text', text: element.textContent || '' }]
                                    };
                                default:
                                    return {
                                        type: 'paragraph',
                                        content: [{ type: 'text', text: element.textContent || '' }]
                                    };
                            }
                        },
                        
                        updateActiveStates(editor) {
                            // 現在の選択状態を更新
                            this.activeStates.bold = document.queryCommandState('bold');
                            this.activeStates.italic = document.queryCommandState('italic');
                            // 他の状態も必要に応じて更新
                        },
                        
                        // ツールバーアクション
                        setHeading(level) {
                            document.execCommand('formatBlock', false, `h${level}`);
                            this.updateStateFromHtml(this.editor.innerHTML);
                        },
                        
                        setParagraph() {
                            document.execCommand('formatBlock', false, 'p');
                            this.updateStateFromHtml(this.editor.innerHTML);
                        },
                        
                        toggleBold() {
                            document.execCommand('bold');
                            this.updateStateFromHtml(this.editor.innerHTML);
                        },
                        
                        toggleItalic() {
                            document.execCommand('italic');
                            this.updateStateFromHtml(this.editor.innerHTML);
                        },
                        
                        addLink() {
                            const url = prompt('リンクURLを入力してください:');
                            if (url) {
                                document.execCommand('createLink', false, url);
                                this.updateStateFromHtml(this.editor.innerHTML);
                            }
                        },
                        
                        toggleBulletList() {
                            document.execCommand('insertUnorderedList');
                            this.updateStateFromHtml(this.editor.innerHTML);
                        },
                        
                        toggleOrderedList() {
                            document.execCommand('insertOrderedList');
                            this.updateStateFromHtml(this.editor.innerHTML);
                        },
                        
                        toggleBlockquote() {
                            document.execCommand('formatBlock', false, 'blockquote');
                            this.updateStateFromHtml(this.editor.innerHTML);
                        },
                        
                        addImage() {
                            console.log('画像追加ボタンがクリックされました');
                            console.log('imageInput ref:', this.$refs.imageInput);
                            console.log('uploadUrl:', this.uploadUrl);
                            console.log('csrfToken:', this.csrfToken);
                            this.$refs.imageInput.click();
                        },
                        
                        addHorizontalRule() {
                            document.execCommand('insertHorizontalRule');
                            this.updateStateFromHtml(this.editor.innerHTML);
                        },
                        
                        async handleImageUpload(event) {
                            console.log('handleImageUpload が呼ばれました', event);
                            const file = event.target.files[0];
                            console.log('選択されたファイル:', file);
                            if (!file) {
                                console.log('ファイルが選択されていません');
                                return;
                            }
                            
                            // ファイルサイズチェック (10MB)
                            if (file.size > 10 * 1024 * 1024) {
                                alert('ファイルサイズが大きすぎます（10MB以下にしてください）');
                                event.target.value = '';
                                return;
                            }
                            
                            // ファイル形式チェック
                            if (!file.type.match(/image\/(jpeg|jpg|png|webp|gif)/)) {
                                alert('対応していないファイル形式です（JPEG, PNG, WebP, GIFのみ）');
                                event.target.value = '';
                                return;
                            }
                            
                            try {
                                console.log('アップロード開始:', {
                                    url: this.uploadUrl,
                                    csrf: this.csrfToken ? '設定済み' : '未設定',
                                    fileName: file.name,
                                    fileSize: file.size,
                                    fileType: file.type
                                });
                                
                                const formData = new FormData();
                                formData.append('image', file);
                                
                                const response = await fetch('/admin/test/upload', {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-CSRF-TOKEN': this.csrfToken,
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                });
                                
                                console.log('レスポンス:', response.status, response.statusText);
                                
                                if (!response.ok) {
                                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                                }
                                
                                const result = await response.json();
                                console.log('✅ アップロードAPI成功:', result);
                                
                                if (result.success) {
                                    console.log('📤 画像挿入処理開始:', {
                                        url: result.url,
                                        path: result.path,
                                        filename: result.filename
                                    });
                                    
                                    try {
                                        // 安全な画像挿入処理
                                        this.insertImageSafely(result);
                                        console.log('✅ 画像挿入完了');
                                    } catch (insertError) {
                                        console.error('❌ 画像挿入エラー:', insertError);
                                        console.error('挿入エラー詳細:', {
                                            selection: window.getSelection(),
                                            editorContent: this.editor ? this.editor.innerHTML : 'editor not found',
                                            error: insertError
                                        });
                                        throw new Error('画像挿入に失敗しました: ' + insertError.message);
                                    }
                                } else {
                                    throw new Error(result.message || result.error || 'アップロードに失敗しました');
                                }
                            } catch (error) {
                                console.error('画像アップロードエラー:', error);
                                alert('画像のアップロードに失敗しました: ' + error.message);
                            }
                            
                            // ファイル入力をクリア
                            event.target.value = '';
                        },
                        
                        // 安全な画像挿入処理（必ず末尾に挿入）
                        insertImageSafely(imageData) {
                            if (!this.editor) {
                                throw new Error('エディタが初期化されていません');
                            }
                            
                            console.log('画像挿入処理開始:', {
                                editor: !!this.editor,
                                editorContent: this.editor.innerHTML.length,
                                selection: window.getSelection().toString()
                            });
                            
                            // エディタにフォーカスを当てる
                            this.editor.focus();
                            
                            // 確実に末尾に挿入する方式
                            const imageWrapper = this.createImageElement(imageData);
                            this.editor.appendChild(imageWrapper);
                            
                            // カーソルを画像の後に移動
                            this.moveCursorAfterElement(imageWrapper);
                            
                            // 状態を更新
                            this.updateStateFromHtml(this.editor.innerHTML);
                        },
                        
                        // 旧メソッドを維持（互換性のため）
                        insertAdvancedImage(imageData) {
                            return this.insertImageSafely(imageData);
                        },
                        
                        createImageElement(imageData) {
                            
                            // 画像ラッパーを作成
                            const imageWrapper = document.createElement('div');
                            imageWrapper.className = 'advanced-image-block';
                            imageWrapper.dataset.imagePath = imageData.path;
                            
                            // 画像要素を作成
                            const img = document.createElement('img');
                            img.src = imageData.url;
                            img.alt = imageData.filename || '';
                            img.style.maxWidth = '100%';
                            img.style.height = 'auto';
                            img.style.display = 'block';
                            img.style.margin = '0 auto';
                            img.dataset.originalWidth = imageData.width || '';
                            img.dataset.originalHeight = imageData.height || '';
                            
                            // コントロールパネルを作成
                            const controls = document.createElement('div');
                            controls.className = 'image-controls';
                            controls.innerHTML = `
                                <div class="control-panel" style="
                                    position: absolute;
                                    top: 10px;
                                    right: 10px;
                                    background: rgba(0,0,0,0.8);
                                    padding: 8px;
                                    border-radius: 6px;
                                    display: none;
                                    gap: 4px;
                                ">
                                    <button type="button" class="control-btn size-btn" title="サイズ調整">
                                        📏
                                    </button>
                                    <button type="button" class="control-btn align-btn" title="配置変更">
                                        ↔️
                                    </button>
                                    <button type="button" class="control-btn replace-btn" title="画像変更">
                                        🔄
                                    </button>
                                    <button type="button" class="control-btn delete-btn" title="削除">
                                        🗑️
                                    </button>
                                </div>
                                <div class="size-controls" style="
                                    position: absolute;
                                    top: 50px;
                                    right: 10px;
                                    background: white;
                                    padding: 12px;
                                    border-radius: 6px;
                                    border: 1px solid #ccc;
                                    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                                    display: none;
                                    flex-direction: column;
                                    gap: 8px;
                                    min-width: 200px;
                                ">
                                    <div>
                                        <label style="font-size: 12px; color: #666;">幅 (px):</label>
                                        <input type="number" class="width-input" value="${imageData.width || 600}" 
                                               style="width: 100%; padding: 4px; border: 1px solid #ccc; border-radius: 3px;" 
                                               min="100" max="1200" step="10">
                                    </div>
                                    <div>
                                        <label style="font-size: 12px; color: #666;">配置:</label>
                                        <select class="align-select" style="width: 100%; padding: 4px; border: 1px solid #ccc; border-radius: 3px;">
                                            <option value="center">中央</option>
                                            <option value="left">左寄せ</option>
                                            <option value="right">右寄せ</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label style="font-size: 12px; color: #666;">キャプション:</label>
                                        <input type="text" class="caption-input" placeholder="画像の説明..."
                                               style="width: 100%; padding: 4px; border: 1px solid #ccc; border-radius: 3px;">
                                    </div>
                                    <div style="display: flex; gap: 4px;">
                                        <button type="button" class="apply-btn" style="
                                            flex: 1; padding: 6px; background: #007cba; color: white; 
                                            border: none; border-radius: 3px; cursor: pointer;
                                        ">適用</button>
                                        <button type="button" class="cancel-btn" style="
                                            flex: 1; padding: 6px; background: #666; color: white; 
                                            border: none; border-radius: 3px; cursor: pointer;
                                        ">キャンセル</button>
                                    </div>
                                </div>
                            `;
                            
                            // キャプション要素を作成
                            const caption = document.createElement('div');
                            caption.className = 'image-caption';
                            caption.style.textAlign = 'center';
                            caption.style.fontSize = '14px';
                            caption.style.color = '#666';
                            caption.style.marginTop = '8px';
                            caption.style.fontStyle = 'italic';
                            
                            // ラッパーのスタイル
                            imageWrapper.style.position = 'relative';
                            imageWrapper.style.display = 'block';
                            imageWrapper.style.margin = '20px 0';
                            imageWrapper.style.textAlign = 'center';
                            
                            // 要素を組み立て
                            imageWrapper.appendChild(img);
                            imageWrapper.appendChild(controls);
                            imageWrapper.appendChild(caption);
                            
                            // イベントリスナーを追加
                            this.setupImageControls(imageWrapper);
                            
                            // DOMに挿入
                            range.insertNode(imageWrapper);
                            
                            // 選択を画像の後に移動
                            range.setStartAfter(imageWrapper);
                            range.setEndAfter(imageWrapper);
                            selection.removeAllRanges();
                            selection.addRange(range);
                            
                            this.updateStateFromHtml(this.editor.innerHTML);
                        },
                        
                        setupImageControls(imageWrapper) {
                            const img = imageWrapper.querySelector('img');
                            const controlPanel = imageWrapper.querySelector('.control-panel');
                            const sizeControls = imageWrapper.querySelector('.size-controls');
                            const caption = imageWrapper.querySelector('.image-caption');
                            
                            // ホバー時にコントロールを表示
                            imageWrapper.addEventListener('mouseenter', () => {
                                controlPanel.style.display = 'flex';
                            });
                            
                            imageWrapper.addEventListener('mouseleave', () => {
                                if (!sizeControls.style.display || sizeControls.style.display === 'none') {
                                    controlPanel.style.display = 'none';
                                }
                            });
                            
                            // サイズ調整ボタン
                            imageWrapper.querySelector('.size-btn').addEventListener('click', (e) => {
                                e.stopPropagation();
                                const isVisible = sizeControls.style.display === 'flex';
                                sizeControls.style.display = isVisible ? 'none' : 'flex';
                                if (!isVisible) {
                                    // 現在の値を設定
                                    const widthInput = sizeControls.querySelector('.width-input');
                                    const alignSelect = sizeControls.querySelector('.align-select');
                                    const captionInput = sizeControls.querySelector('.caption-input');
                                    
                                    widthInput.value = img.offsetWidth || img.dataset.originalWidth || 600;
                                    alignSelect.value = imageWrapper.style.textAlign || 'center';
                                    captionInput.value = caption.textContent || '';
                                }
                            });
                            
                            // 適用ボタン
                            imageWrapper.querySelector('.apply-btn').addEventListener('click', (e) => {
                                e.stopPropagation();
                                const widthInput = sizeControls.querySelector('.width-input');
                                const alignSelect = sizeControls.querySelector('.align-select');
                                const captionInput = sizeControls.querySelector('.caption-input');
                                
                                // サイズ適用
                                const newWidth = parseInt(widthInput.value);
                                if (newWidth && newWidth >= 100 && newWidth <= 1200) {
                                    img.style.width = newWidth + 'px';
                                    img.style.maxWidth = newWidth + 'px';
                                }
                                
                                // 配置適用
                                imageWrapper.style.textAlign = alignSelect.value;
                                img.style.margin = alignSelect.value === 'center' ? '0 auto' : 
                                                 alignSelect.value === 'left' ? '0 auto 0 0' : '0 0 0 auto';
                                
                                // キャプション適用
                                caption.textContent = captionInput.value;
                                caption.style.display = captionInput.value ? 'block' : 'none';
                                
                                sizeControls.style.display = 'none';
                                controlPanel.style.display = 'none';
                                
                                this.updateStateFromHtml(this.editor.innerHTML);
                            });
                            
                            // キャンセルボタン
                            imageWrapper.querySelector('.cancel-btn').addEventListener('click', (e) => {
                                e.stopPropagation();
                                sizeControls.style.display = 'none';
                                controlPanel.style.display = 'none';
                            });
                            
                            // 画像変更ボタン
                            imageWrapper.querySelector('.replace-btn').addEventListener('click', (e) => {
                                e.stopPropagation();
                                const fileInput = document.createElement('input');
                                fileInput.type = 'file';
                                fileInput.accept = 'image/*';
                                fileInput.onchange = async (event) => {
                                    const file = event.target.files[0];
                                    if (file) {
                                        try {
                                            const formData = new FormData();
                                            formData.append('image', file);
                                            
                                            const response = await fetch('/admin/test/upload', {
                                                method: 'POST',
                                                body: formData,
                                                headers: {
                                                    'X-CSRF-TOKEN': this.csrfToken,
                                                    'X-Requested-With': 'XMLHttpRequest'
                                                }
                                            });
                                            
                                            const result = await response.json();
                                            
                                            if (response.ok) {
                                                // 既存の画像を新しい画像に置き換え
                                                img.src = result.url;
                                                img.alt = result.filename || img.alt;
                                                img.dataset.originalWidth = result.width || '';
                                                img.dataset.originalHeight = result.height || '';
                                                imageWrapper.dataset.imagePath = result.path;
                                                
                                                this.updateStateFromHtml(this.editor.innerHTML);
                                            } else {
                                                alert('画像の変更に失敗しました: ' + (result.message || '不明なエラー'));
                                            }
                                        } catch (error) {
                                            console.error('画像変更エラー:', error);
                                            alert('画像の変更に失敗しました。');
                                        }
                                    }
                                };
                                fileInput.click();
                            });
                            
                            // 削除ボタン
                            imageWrapper.querySelector('.delete-btn').addEventListener('click', (e) => {
                                e.stopPropagation();
                                if (confirm('この画像を削除しますか？')) {
                                    imageWrapper.remove();
                                    this.updateStateFromHtml(this.editor.innerHTML);
                                }
                            });
                            
                            return imageWrapper;
                        },
                        
                        // カーソルを要素の後に移動（安全な方式）
                        moveCursorAfterElement(element) {
                            try {
                                // 要素の後に段落を追加（カーソル位置確保）
                                const nextParagraph = document.createElement('p');
                                nextParagraph.innerHTML = '<br>'; // 空段落に必要
                                
                                // 要素の次に挿入
                                if (element.nextSibling) {
                                    this.editor.insertBefore(nextParagraph, element.nextSibling);
                                } else {
                                    this.editor.appendChild(nextParagraph);
                                }
                                
                                // カーソルを段落に移動
                                const selection = window.getSelection();
                                const range = document.createRange();
                                range.setStart(nextParagraph, 0);
                                range.setEnd(nextParagraph, 0);
                                selection.removeAllRanges();
                                selection.addRange(range);
                                
                                console.log('✅ カーソル移動完了');
                            } catch (error) {
                                console.error('カーソル移動エラー:', error);
                                // エラーでも処理を継続
                            }
                        }
                    }
                }
            </script>
        @endpush
    @endonce
</x-dynamic-component>