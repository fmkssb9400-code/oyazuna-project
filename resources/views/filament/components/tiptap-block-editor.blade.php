<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div
        x-data="tiptapBlockEditor({
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$getStatePath()}')") }},
            uploadUrl: '{{ $getUploadUrl() }}',
            deleteUrl: '{{ $getDeleteUrl() }}',
            csrfToken: '{{ $getCsrfToken() }}'
        })"
        x-init="init()"
        {{ $attributes->merge($getExtraAttributes())->class([
            'tiptap-block-editor-wrapper border border-gray-300 rounded-lg bg-white'
        ]) }}
    >
        <!-- ツールバー -->
        <div class="toolbar bg-gray-50 border-b border-gray-300 p-3">
            <div class="flex flex-wrap items-center gap-2">
                <!-- 基本フォーマット -->
                <div class="flex items-center gap-1 pr-3 border-r border-gray-300">
                    <button type="button" @click="toggleHeading(2)" 
                            :class="{ 'bg-blue-600 text-white': activeStates.heading2, 'bg-white text-gray-700': !activeStates.heading2 }"
                            class="px-2 py-1.5 text-sm font-medium border border-gray-300 rounded hover:bg-gray-50 transition-colors">
                        H2
                    </button>
                    <button type="button" @click="toggleHeading(3)"
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
                
                <!-- テキストスタイル -->
                <div class="flex items-center gap-1 pr-3 border-r border-gray-300">
                    <button type="button" @click="toggleBold()"
                            :class="{ 'bg-blue-600 text-white': activeStates.bold, 'bg-white text-gray-700': !activeStates.bold }"
                            class="px-2 py-1.5 text-sm font-medium border border-gray-300 rounded hover:bg-gray-50 transition-colors">
                        <strong>B</strong>
                    </button>
                    <button type="button" @click="toggleItalic()"
                            :class="{ 'bg-blue-600 text-white': activeStates.italic, 'bg-white text-gray-700': !activeStates.italic }"
                            class="px-2 py-1.5 text-sm font-medium border border-gray-300 rounded hover:bg-gray-50 transition-colors">
                        <em>I</em>
                    </button>
                </div>
                
                <!-- リスト -->
                <div class="flex items-center gap-1 pr-3 border-r border-gray-300">
                    <button type="button" @click="toggleBulletList()"
                            :class="{ 'bg-blue-600 text-white': activeStates.bulletList, 'bg-white text-gray-700': !activeStates.bulletList }"
                            class="px-2 py-1.5 text-sm font-medium border border-gray-300 rounded hover:bg-gray-50 transition-colors">
                        • List
                    </button>
                    <button type="button" @click="toggleOrderedList()"
                            :class="{ 'bg-blue-600 text-white': activeStates.orderedList, 'bg-white text-gray-700': !activeStates.orderedList }"
                            class="px-2 py-1.5 text-sm font-medium border border-gray-300 rounded hover:bg-gray-50 transition-colors">
                        1. List
                    </button>
                </div>
                
                <!-- メディア・その他 -->
                <div class="flex items-center gap-1">
                    <button type="button" @click="openImageUpload()" 
                            class="px-3 py-1.5 text-sm bg-green-600 text-white border border-green-600 rounded hover:bg-green-700 transition-colors">
                        📷 画像
                    </button>
                    <button type="button" @click="toggleBlockquote()"
                            :class="{ 'bg-blue-600 text-white': activeStates.blockquote, 'bg-white text-gray-700': !activeStates.blockquote }"
                            class="px-2 py-1.5 text-sm font-medium border border-gray-300 rounded hover:bg-gray-50 transition-colors">
                        Quote
                    </button>
                    <button type="button" @click="insertHorizontalRule()"
                            class="px-2 py-1.5 text-sm bg-white text-gray-700 border border-gray-300 rounded hover:bg-gray-50 transition-colors">
                        ———
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Tiptapエディタコンテナ -->
        <div x-ref="editorElement" class="prose max-w-none p-6 min-h-[400px] focus:outline-none"></div>
        
        <!-- 隠しファイル入力 -->
        <input type="file" x-ref="imageInput" @change="handleImageUpload($event)" 
               accept="image/*" style="display: none;">
    </div>
    
    @once
        @push('scripts')
            <!-- Tiptap CDN -->
            <script src="https://unpkg.com/@tiptap/core@2.0.4/dist/index.umd.js"></script>
            <script src="https://unpkg.com/@tiptap/starter-kit@2.0.4/dist/index.umd.js"></script>
            <script src="https://unpkg.com/@tiptap/extension-image@2.0.4/dist/index.umd.js"></script>
            
            <script>
                window.tiptapBlockEditor = function(config) {
                    return {
                        state: config.state || { type: 'doc', content: [] },
                        uploadUrl: config.uploadUrl,
                        deleteUrl: config.deleteUrl,
                        csrfToken: config.csrfToken,
                        editor: null,
                        activeStates: {
                            heading2: false,
                            heading3: false,
                            paragraph: false,
                            bold: false,
                            italic: false,
                            bulletList: false,
                            orderedList: false,
                            blockquote: false
                        },
                        
                        init() {
                            console.log('🚀 Tiptapエディタ初期化開始');
                            console.log('設定値:', {
                                uploadUrl: this.uploadUrl,
                                csrfToken: this.csrfToken ? '設定済み' : '未設定',
                                initialState: this.state
                            });
                            this.initTiptapEditor();
                        },
                        
                        initTiptapEditor() {
                            const { Editor } = Tiptap;
                            const { StarterKit } = TiptapStarterKit;
                            const { Image } = TiptapImage;
                            
                            this.editor = new Editor({
                                element: this.$refs.editorElement,
                                extensions: [
                                    StarterKit,
                                    Image.configure({
                                        inline: false,
                                        HTMLAttributes: {
                                            class: 'max-w-full h-auto rounded-lg shadow-sm my-4',
                                        },
                                    }),
                                ],
                                content: this.generateHtmlFromState(this.state),
                                onUpdate: ({ editor }) => {
                                    this.updateStateFromEditor(editor);
                                    this.updateActiveStates(editor);
                                },
                                onSelectionUpdate: ({ editor }) => {
                                    this.updateActiveStates(editor);
                                },
                            });
                            
                            console.log('✅ Tiptapエディタ初期化完了');
                            
                            // 空ドキュメント対策：最低1つのparagraphを確保
                            if (this.editor.isEmpty) {
                                this.editor.commands.setContent('<p></p>');
                            }
                        },
                        
                        // === アップロード関数（まるごと書き換え） ===
                        async handleImageUpload(event) {
                            console.log('📤 画像アップロード開始', {
                                timestamp: new Date().toISOString(),
                                hasFile: !!event.target.files[0],
                                editorExists: !!this.editor,
                                editorDestroyed: this.editor?.isDestroyed,
                                uploadUrl: this.uploadUrl,
                                csrfToken: this.csrfToken ? '設定済み' : '未設定'
                            });
                            
                            const file = event.target.files[0];
                            if (!file) {
                                console.warn('❌ ファイルが選択されていません');
                                return;
                            }
                            
                            console.log('📁 選択されたファイル:', {
                                name: file.name,
                                size: file.size,
                                type: file.type,
                                lastModified: file.lastModified
                            });
                            
                            // バリデーション
                            if (file.size > 10 * 1024 * 1024) {
                                const errorMsg = 'ファイルサイズが10MBを超えています';
                                console.error('❌ ' + errorMsg);
                                alert(errorMsg);
                                event.target.value = '';
                                return;
                            }
                            
                            if (!file.type.match(/image\/(jpeg|jpg|png|webp|gif)/)) {
                                const errorMsg = '対応していないファイル形式です';
                                console.error('❌ ' + errorMsg);
                                alert(errorMsg);
                                event.target.value = '';
                                return;
                            }
                            
                            try {
                                // FormData作成
                                const formData = new FormData();
                                formData.append('image', file);
                                
                                console.log('🌐 APIリクエスト送信:', {
                                    url: this.uploadUrl,
                                    method: 'POST',
                                    hasCSRF: !!this.csrfToken,
                                    fileSize: file.size
                                });
                                
                                // Fetch実行（必須ヘッダー付き）
                                const response = await fetch(this.uploadUrl, {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-CSRF-TOKEN': this.csrfToken,
                                        'X-Requested-With': 'XMLHttpRequest',
                                    },
                                    credentials: 'same-origin' // 必須
                                });
                                
                                console.log('📡 APIレスポンス受信:', {
                                    status: response.status,
                                    statusText: response.statusText,
                                    ok: response.ok,
                                    headers: Object.fromEntries(response.headers.entries())
                                });
                                
                                const responseData = await response.json();
                                
                                console.log('📋 APIレスポンスボディ:', responseData);
                                
                                if (!response.ok) {
                                    throw new Error(`HTTP ${response.status}: ${responseData.message || response.statusText}`);
                                }
                                
                                if (!responseData.success) {
                                    throw new Error(responseData.message || 'アップロードに失敗しました');
                                }
                                
                                console.log('✅ アップロードAPI成功');
                                
                                // === Tiptap画像挿入（POS事故完全排除版） ===
                                await this.insertImageToTiptap(responseData);
                                
                            } catch (error) {
                                console.error('❌ アップロードエラー:', {
                                    error: error,
                                    message: error.message,
                                    stack: error.stack,
                                    response_status: error.response?.status,
                                    response_data: error.response?.data
                                });
                                
                                // UIトーストに詳細表示
                                let errorMessage = '画像アップロードに失敗しました';
                                if (error.message.includes('HTTP')) {
                                    errorMessage += `\n${error.message}`;
                                } else {
                                    errorMessage += `\nエラー: ${error.message}`;
                                }
                                alert(errorMessage);
                                
                            } finally {
                                // 必ずクリア
                                event.target.value = '';
                            }
                        },
                        
                        // === Tiptap挿入処理（POS事故ゼロ版） ===
                        async insertImageToTiptap(imageData) {
                            console.log('🖼️ Tiptap画像挿入開始:', {
                                imageUrl: imageData.url,
                                editorExists: !!this.editor,
                                editorDestroyed: this.editor?.isDestroyed,
                                isEmpty: this.editor?.isEmpty
                            });
                            
                            if (!this.editor || this.editor.isDestroyed) {
                                throw new Error('エディタが利用できません');
                            }
                            
                            try {
                                // 1. 必ずフォーカスを当てる
                                this.editor.chain().focus().run();
                                
                                // 2. 現在のselection状態をログ
                                const state = this.editor.state;
                                const selection = state.selection;
                                const docSize = state.doc.content.size;
                                
                                console.log('📍 現在のエディタ状態:', {
                                    docSize: docSize,
                                    selectionFrom: selection.from,
                                    selectionTo: selection.to,
                                    selectionEmpty: selection.empty,
                                    selectionAnchor: selection.anchor,
                                    selectionHead: selection.head,
                                    selectionType: selection.constructor.name
                                });
                                
                                // 3. 安全な挿入位置を計算（末尾基準）
                                const insertPos = Math.min(docSize, Math.max(0, docSize));
                                
                                console.log('📌 挿入位置決定:', {
                                    insertPos: insertPos,
                                    docSize: docSize,
                                    strategy: '末尾挿入'
                                });
                                
                                // 4. 末尾に強制選択してから画像挿入
                                const { TextSelection } = this.editor.state.schema;
                                const endPos = this.editor.state.doc.content.size;
                                
                                // TextSelectionで末尾に移動
                                this.editor
                                    .chain()
                                    .focus()
                                    .command(({ tr, dispatch }) => {
                                        if (dispatch) {
                                            const selection = TextSelection.create(tr.doc, endPos);
                                            tr.setSelection(selection);
                                        }
                                        return true;
                                    })
                                    .setImage({ src: imageData.url })
                                    .run();
                                
                                console.log('✅ Tiptap画像挿入完了:', {
                                    finalDocSize: this.editor.state.doc.content.size,
                                    finalSelection: this.editor.state.selection.from + '-' + this.editor.state.selection.to
                                });
                                
                            } catch (insertError) {
                                console.error('❌ Tiptap挿入エラー:', {
                                    error: insertError,
                                    message: insertError.message,
                                    stack: insertError.stack,
                                    editorState: {
                                        exists: !!this.editor,
                                        destroyed: this.editor?.isDestroyed,
                                        isEmpty: this.editor?.isEmpty,
                                        docSize: this.editor?.state?.doc?.content?.size,
                                        selection: this.editor?.state?.selection ? {
                                            from: this.editor.state.selection.from,
                                            to: this.editor.state.selection.to
                                        } : null
                                    }
                                });
                                throw new Error('画像挿入に失敗しました: ' + insertError.message);
                            }
                        },
                        
                        // 既存のメソッド群
                        openImageUpload() {
                            console.log('📷 画像アップロードダイアログを開く');
                            this.$refs.imageInput.click();
                        },
                        
                        toggleHeading(level) {
                            this.editor.chain().focus().toggleHeading({ level }).run();
                        },
                        
                        setParagraph() {
                            this.editor.chain().focus().setParagraph().run();
                        },
                        
                        toggleBold() {
                            this.editor.chain().focus().toggleBold().run();
                        },
                        
                        toggleItalic() {
                            this.editor.chain().focus().toggleItalic().run();
                        },
                        
                        toggleBulletList() {
                            this.editor.chain().focus().toggleBulletList().run();
                        },
                        
                        toggleOrderedList() {
                            this.editor.chain().focus().toggleOrderedList().run();
                        },
                        
                        toggleBlockquote() {
                            this.editor.chain().focus().toggleBlockquote().run();
                        },
                        
                        insertHorizontalRule() {
                            this.editor.chain().focus().setHorizontalRule().run();
                        },
                        
                        updateActiveStates(editor) {
                            this.activeStates = {
                                heading2: editor.isActive('heading', { level: 2 }),
                                heading3: editor.isActive('heading', { level: 3 }),
                                paragraph: editor.isActive('paragraph'),
                                bold: editor.isActive('bold'),
                                italic: editor.isActive('italic'),
                                bulletList: editor.isActive('bulletList'),
                                orderedList: editor.isActive('orderedList'),
                                blockquote: editor.isActive('blockquote'),
                            };
                        },
                        
                        updateStateFromEditor(editor) {
                            const json = editor.getJSON();
                            this.state = json;
                        },
                        
                        generateHtmlFromState(state) {
                            if (!state || !state.content || state.content.length === 0) {
                                return '<p></p>'; // 空ドキュメント対策
                            }
                            
                            // 簡易的なJSON→HTML変換
                            let html = '';
                            for (const node of state.content) {
                                html += this.nodeToHtml(node);
                            }
                            return html || '<p></p>';
                        },
                        
                        nodeToHtml(node) {
                            switch (node.type) {
                                case 'paragraph':
                                    return `<p>${this.contentToHtml(node.content || [])}</p>`;
                                case 'heading':
                                    const level = node.attrs?.level || 2;
                                    return `<h${level}>${this.contentToHtml(node.content || [])}</h${level}>`;
                                case 'image':
                                    const src = node.attrs?.src || '';
                                    return `<img src="${src}" alt="" />`;
                                case 'bulletList':
                                    const listItems = node.content?.map(item => this.nodeToHtml(item)).join('') || '';
                                    return `<ul>${listItems}</ul>`;
                                case 'listItem':
                                    return `<li>${this.contentToHtml(node.content || [])}</li>`;
                                case 'blockquote':
                                    return `<blockquote>${this.contentToHtml(node.content || [])}</blockquote>`;
                                case 'horizontalRule':
                                    return '<hr />';
                                default:
                                    return '';
                            }
                        },
                        
                        contentToHtml(content) {
                            return content.map(node => {
                                if (node.type === 'text') {
                                    let text = node.text || '';
                                    if (node.marks) {
                                        for (const mark of node.marks) {
                                            switch (mark.type) {
                                                case 'bold':
                                                    text = `<strong>${text}</strong>`;
                                                    break;
                                                case 'italic':
                                                    text = `<em>${text}</em>`;
                                                    break;
                                            }
                                        }
                                    }
                                    return text;
                                }
                                return this.nodeToHtml(node);
                            }).join('');
                        },
                        
                        destroy() {
                            if (this.editor) {
                                this.editor.destroy();
                                console.log('🗑️ Tiptapエディタ破棄完了');
                            }
                        }
                    }
                }
            </script>
        @endpush
    @endonce
</x-dynamic-component>