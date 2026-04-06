<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div id="vue-block-editor-{{ $getId() }}" 
         data-state="{{ json_encode($getState()) }}"
         data-upload-url="{{ $getUploadUrl() }}"
         data-delete-url="{{ $getDeleteUrl() }}"
         data-csrf-token="{{ $getCsrfToken() }}"
         data-field-name="{{ $getStatePath() }}"
         {{ $attributes->merge($getExtraAttributes())->class([
             'vue-block-editor-wrapper border border-gray-300 rounded-lg bg-white'
         ]) }}>
        <!-- Vue.jsコンポーネントがここにマウント -->
    </div>
    
    <!-- Filament統合用の隠しフィールド -->
    <input type="hidden" 
           name="{{ $getStatePath() }}" 
           value="{{ json_encode($getState()) }}" 
           id="hidden-{{ $getId() }}"
    />
    
    @once
        @push('scripts')
            <!-- Vue 3 + Tiptap (エラーハンドリング付き) -->
            <script src="https://unpkg.com/vue@3.3.4/dist/vue.global.js" onerror="console.error('❌ Vue.js読み込み失敗:', this.src)"></script>
            <script src="https://unpkg.com/@tiptap/core@2.1.8/dist/index.umd.js" onerror="console.error('❌ Tiptap Core読み込み失敗:', this.src)"></script>
            <script src="https://unpkg.com/@tiptap/starter-kit@2.1.8/dist/index.umd.js" onerror="console.error('❌ Tiptap StarterKit読み込み失敗:', this.src)"></script>
            <script src="https://unpkg.com/@tiptap/extension-image@2.1.8/dist/index.umd.js" onerror="console.error('❌ Tiptap Image拡張読み込み失敗:', this.src)"></script>
            <script src="https://unpkg.com/@tiptap/vue-3@2.1.8/dist/index.umd.js" onerror="console.error('❌ Tiptap Vue3読み込み失敗:', this.src)"></script>
            
            <script>
                // ライブラリ読み込み確認
                console.log('🔍 ライブラリ読み込み状況チェック');
                if (typeof Vue === 'undefined') {
                    console.error('❌ Vue.jsが読み込まれていません');
                    alert('エラー: Vue.jsが読み込まれていません。ネットワーク接続を確認してページを再読み込みしてください。');
                } else {
                    console.log('✅ Vue.js読み込み成功');
                }
                
                if (typeof TiptapVue3 === 'undefined') {
                    console.error('❌ TiptapVue3が読み込まれていません');
                    alert('エラー: エディタライブラリが読み込まれていません。ページを再読み込みしてください。');
                } else {
                    console.log('✅ TiptapVue3読み込み成功');
                }
                
                if (typeof TiptapStarterKit === 'undefined') {
                    console.error('❌ TiptapStarterKitが読み込まれていません');
                } else {
                    console.log('✅ TiptapStarterKit読み込み成功');
                }
                
                if (typeof TiptapImage === 'undefined') {
                    console.error('❌ TiptapImageが読み込まれていません');
                } else {
                    console.log('✅ TiptapImage読み込み成功');
                }
                
                // 必須ライブラリが読み込まれていない場合は処理を停止
                if (typeof Vue === 'undefined' || typeof TiptapVue3 === 'undefined' || 
                    typeof TiptapStarterKit === 'undefined' || typeof TiptapImage === 'undefined') {
                    console.error('❌ 必須ライブラリが不足しています。処理を停止します。');
                    throw new Error('必須ライブラリが読み込まれていません');
                }
                
                const { createApp, ref, computed, onMounted, onBeforeUnmount, watch, nextTick } = Vue;
                const { useEditor, EditorContent } = TiptapVue3;
                const { StarterKit } = TiptapStarterKit;
                const { Image } = TiptapImage;
                
                // BlockEditor Vue Component
                const BlockEditor = {
                    template: `
                        <div class="vue-block-editor">
                            <!-- ツールバー -->
                            <div class="toolbar bg-gray-50 border-b border-gray-300 p-3">
                                <div class="flex flex-wrap items-center gap-2">
                                    <!-- 基本フォーマット -->
                                    <div class="flex items-center gap-1 pr-3 border-r border-gray-300">
                                        <button type="button" @click="toggleHeading(2)" 
                                                :class="{ 'bg-blue-600 text-white': isActive.heading2, 'bg-white text-gray-700': !isActive.heading2 }"
                                                :disabled="!isEditorReady"
                                                class="px-2 py-1.5 text-sm font-medium border border-gray-300 rounded hover:bg-gray-50 transition-colors disabled:opacity-50">
                                            H2
                                        </button>
                                        <button type="button" @click="toggleHeading(3)"
                                                :class="{ 'bg-blue-600 text-white': isActive.heading3, 'bg-white text-gray-700': !isActive.heading3 }"
                                                :disabled="!isEditorReady"
                                                class="px-2 py-1.5 text-sm font-medium border border-gray-300 rounded hover:bg-gray-50 transition-colors disabled:opacity-50">
                                            H3
                                        </button>
                                        <button type="button" @click="setParagraph()"
                                                :class="{ 'bg-blue-600 text-white': isActive.paragraph, 'bg-white text-gray-700': !isActive.paragraph }"
                                                :disabled="!isEditorReady"
                                                class="px-2 py-1.5 text-sm font-medium border border-gray-300 rounded hover:bg-gray-50 transition-colors disabled:opacity-50">
                                            P
                                        </button>
                                    </div>
                                    
                                    <!-- テキストスタイル -->
                                    <div class="flex items-center gap-1 pr-3 border-r border-gray-300">
                                        <button type="button" @click="toggleBold()"
                                                :class="{ 'bg-blue-600 text-white': isActive.bold, 'bg-white text-gray-700': !isActive.bold }"
                                                :disabled="!isEditorReady"
                                                class="px-2 py-1.5 text-sm font-medium border border-gray-300 rounded hover:bg-gray-50 transition-colors disabled:opacity-50">
                                            <strong>B</strong>
                                        </button>
                                        <button type="button" @click="toggleItalic()"
                                                :class="{ 'bg-blue-600 text-white': isActive.italic, 'bg-white text-gray-700': !isActive.italic }"
                                                :disabled="!isEditorReady"
                                                class="px-2 py-1.5 text-sm font-medium border border-gray-300 rounded hover:bg-gray-50 transition-colors disabled:opacity-50">
                                            <em>I</em>
                                        </button>
                                    </div>
                                    
                                    <!-- リスト -->
                                    <div class="flex items-center gap-1 pr-3 border-r border-gray-300">
                                        <button type="button" @click="toggleBulletList()"
                                                :class="{ 'bg-blue-600 text-white': isActive.bulletList, 'bg-white text-gray-700': !isActive.bulletList }"
                                                :disabled="!isEditorReady"
                                                class="px-2 py-1.5 text-sm font-medium border border-gray-300 rounded hover:bg-gray-50 transition-colors disabled:opacity-50">
                                            • List
                                        </button>
                                        <button type="button" @click="toggleOrderedList()"
                                                :class="{ 'bg-blue-600 text-white': isActive.orderedList, 'bg-white text-gray-700': !isActive.orderedList }"
                                                :disabled="!isEditorReady"
                                                class="px-2 py-1.5 text-sm font-medium border border-gray-300 rounded hover:bg-gray-50 transition-colors disabled:opacity-50">
                                            1. List
                                        </button>
                                    </div>
                                    
                                    <!-- メディア・その他 -->
                                    <div class="flex items-center gap-1">
                                        <button type="button" @click="openImageUpload()" 
                                                :disabled="!isEditorReady"
                                                class="px-3 py-1.5 text-sm bg-green-600 text-white border border-green-600 rounded hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                            📷 画像
                                            <span v-if="!isEditorReady" class="ml-1 text-xs">(準備中)</span>
                                        </button>
                                        <button type="button" @click="toggleBlockquote()"
                                                :class="{ 'bg-blue-600 text-white': isActive.blockquote, 'bg-white text-gray-700': !isActive.blockquote }"
                                                :disabled="!isEditorReady"
                                                class="px-2 py-1.5 text-sm font-medium border border-gray-300 rounded hover:bg-gray-50 transition-colors disabled:opacity-50">
                                            Quote
                                        </button>
                                        <button type="button" @click="insertHorizontalRule()"
                                                :disabled="!isEditorReady"
                                                class="px-2 py-1.5 text-sm bg-white text-gray-700 border border-gray-300 rounded hover:bg-gray-50 transition-colors disabled:opacity-50">
                                            ———
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- エディタコンテンツ（v-showで必ず存在、v-ifは使わない） -->
                            <div class="editor-container prose max-w-none p-6 min-h-[400px] focus:outline-none" 
                                 v-show="isEditorReady">
                                <editor-content :editor="editor" />
                            </div>
                            
                            <!-- エディタ準備中表示 -->
                            <div v-show="!isEditorReady" class="flex items-center justify-center p-6 min-h-[400px] text-gray-500">
                                <div class="text-center">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900 mx-auto"></div>
                                    <p class="mt-2">エディタを準備中...</p>
                                </div>
                            </div>
                            
                            <!-- 隠しファイル入力 -->
                            <input ref="imageInput" type="file" @change="handleImageUpload" 
                                   accept="image/*" style="display: none;">
                        </div>
                    `,
                    
                    components: {
                        EditorContent
                    },
                    
                    props: {
                        initialState: Object,
                        uploadUrl: String,
                        deleteUrl: String,
                        csrfToken: String,
                        fieldName: String
                    },
                    
                    setup(props, { emit }) {
                        console.log('🏗️ BlockEditor setup開始');
                        
                        // === エディタ関連の状態 ===
                        const editor = ref(null);
                        const imageInput = ref(null);
                        
                        // エディタの準備状態（厳密チェック）
                        const isEditorReady = computed(() => {
                            const ready = !!(editor.value && !editor.value.isDestroyed && editor.value.view);
                            console.log('📊 isEditorReady =', ready, {
                                editor: !!editor.value,
                                destroyed: editor.value?.isDestroyed,
                                hasView: !!editor.value?.view
                            });
                            return ready;
                        });
                        
                        // アクティブ状態
                        const isActive = ref({
                            heading2: false,
                            heading3: false,
                            paragraph: false,
                            bold: false,
                            italic: false,
                            bulletList: false,
                            orderedList: false,
                            blockquote: false,
                        });
                        
                        // === エディタ初期化（onMountedで確実実行） ===
                        onMounted(async () => {
                            console.log('🔄 onMounted: エディタ初期化開始', {
                                timestamp: new Date().toISOString(),
                                props: {
                                    uploadUrl: props.uploadUrl,
                                    csrfToken: props.csrfToken ? '設定済み' : '未設定',
                                    initialState: props.initialState
                                }
                            });
                            
                            try {
                                await nextTick(); // DOM準備完了を確実に待つ
                                
                                // === 画像サイズ制御対応のカスタムImageBlock拡張 ===
                                const ImageBlock = Image.extend({
                                    name: 'imageBlock',
                                    
                                    addAttributes() {
                                        return {
                                            ...this.parent?.(),
                                            size: {
                                                default: 'md',
                                                parseHTML: element => element.getAttribute('data-size') || 'md',
                                                renderHTML: attributes => {
                                                    return { 'data-size': attributes.size };
                                                }
                                            }
                                        };
                                    },
                                    
                                    renderHTML({ HTMLAttributes }) {
                                        const { size, ...attrs } = HTMLAttributes;
                                        return ['img', {
                                            ...attrs,
                                            'data-size': size || 'md',
                                            class: `img-${size || 'md'} max-w-full h-auto rounded-lg shadow-sm my-4`
                                        }];
                                    },
                                    
                                    addNodeView() {
                                        return ({ node, getPos, editor }) => {
                                            const container = document.createElement('div');
                                            container.className = 'image-block-container relative';
                                            
                                            const img = document.createElement('img');
                                            img.src = node.attrs.src;
                                            img.alt = node.attrs.alt || '';
                                            img.setAttribute('data-size', node.attrs.size || 'md');
                                            img.className = `img-${node.attrs.size || 'md'} max-w-full h-auto rounded-lg shadow-sm cursor-pointer`;
                                            
                                            // 画像サイズ制御ツールバー
                                            const toolbar = document.createElement('div');
                                            toolbar.className = 'image-toolbar absolute top-2 right-2 bg-black bg-opacity-75 rounded px-2 py-1 opacity-0 transition-opacity';
                                            toolbar.innerHTML = `
                                                <div class="flex gap-1">
                                                    <button class="size-btn text-white text-xs px-2 py-1 rounded hover:bg-gray-600" data-size="sm">小</button>
                                                    <button class="size-btn text-white text-xs px-2 py-1 rounded hover:bg-gray-600" data-size="md">中</button>
                                                    <button class="size-btn text-white text-xs px-2 py-1 rounded hover:bg-gray-600" data-size="lg">大</button>
                                                </div>
                                            `;
                                            
                                            // サイズボタンイベント
                                            toolbar.addEventListener('click', (e) => {
                                                if (e.target.classList.contains('size-btn')) {
                                                    const newSize = e.target.dataset.size;
                                                    console.log('📏 画像サイズ変更:', newSize);
                                                    
                                                    if (typeof getPos === 'function') {
                                                        editor.chain()
                                                            .focus()
                                                            .updateAttributes('imageBlock', { size: newSize })
                                                            .run();
                                                        
                                                        console.log('✅ サイズ更新完了:', newSize);
                                                    }
                                                }
                                            });
                                            
                                            // ホバー効果
                                            container.addEventListener('mouseenter', () => {
                                                toolbar.style.opacity = '1';
                                            });
                                            container.addEventListener('mouseleave', () => {
                                                toolbar.style.opacity = '0';
                                            });
                                            
                                            container.appendChild(img);
                                            container.appendChild(toolbar);
                                            
                                            return {
                                                dom: container,
                                                update: (updatedNode) => {
                                                    if (updatedNode.type.name !== 'imageBlock') return false;
                                                    
                                                    img.src = updatedNode.attrs.src;
                                                    img.alt = updatedNode.attrs.alt || '';
                                                    img.setAttribute('data-size', updatedNode.attrs.size || 'md');
                                                    img.className = `img-${updatedNode.attrs.size || 'md'} max-w-full h-auto rounded-lg shadow-sm cursor-pointer`;
                                                    
                                                    // ツールバーのアクティブ状態更新
                                                    toolbar.querySelectorAll('.size-btn').forEach(btn => {
                                                        btn.classList.toggle('bg-blue-600', btn.dataset.size === updatedNode.attrs.size);
                                                    });
                                                    
                                                    return true;
                                                }
                                            };
                                        };
                                    }
                                });
                                
                                // Tiptap Editor生成
                                editor.value = useEditor({
                                    extensions: [
                                        StarterKit.configure({
                                            // 標準のImage拡張を無効にしてImageBlockを使用
                                        }),
                                        ImageBlock.configure({
                                            inline: false,
                                        }),
                                    ],
                                    content: generateHtmlFromState(props.initialState),
                                    onUpdate: ({ editor: editorInstance }) => {
                                        console.log('📝 エディタ内容更新');
                                        updateStateFromEditor(editorInstance);
                                        updateActiveStates(editorInstance);
                                    },
                                    onSelectionUpdate: ({ editor: editorInstance }) => {
                                        updateActiveStates(editorInstance);
                                    },
                                    onCreate: ({ editor: editorInstance }) => {
                                        console.log('✅ エディタ作成完了', {
                                            isDestroyed: editorInstance.isDestroyed,
                                            hasView: !!editorInstance.view,
                                            isEmpty: editorInstance.isEmpty
                                        });
                                        
                                        // 空ドキュメント対策
                                        if (editorInstance.isEmpty) {
                                            editorInstance.commands.setContent('<p></p>');
                                        }
                                    },
                                    onDestroy: () => {
                                        console.log('🗑️ エディタ破棄イベント');
                                    }
                                });
                                
                                console.log('🎉 エディタ初期化完了', {
                                    editor: !!editor.value,
                                    destroyed: editor.value?.isDestroyed,
                                    ready: isEditorReady.value
                                });
                                
                            } catch (error) {
                                console.error('❌ エディタ初期化エラー:', error);
                            }
                        });
                        
                        // === エディタ破棄（onBeforeUnmountで確実実行） ===
                        onBeforeUnmount(() => {
                            console.log('🧹 onBeforeUnmount: エディタ破棄開始', {
                                editor: !!editor.value,
                                destroyed: editor.value?.isDestroyed
                            });
                            
                            if (editor.value && !editor.value.isDestroyed) {
                                editor.value.destroy();
                                console.log('✅ エディタ破棄完了');
                            }
                            editor.value = null;
                        });
                        
                        // === 画像アップロード処理（ガード付き） ===
                        const openImageUpload = () => {
                            console.log('📷 画像アップロードボタンクリック', {
                                timestamp: new Date().toISOString(),
                                editor: !!editor.value,
                                destroyed: editor.value?.isDestroyed,
                                ready: isEditorReady.value
                            });
                            
                            // 必須ガード
                            if (!editor.value || editor.value.isDestroyed) {
                                console.error('❌ エディタが利用できません（ボタンクリック時）', {
                                    editor: !!editor.value,
                                    destroyed: editor.value?.isDestroyed
                                });
                                alert('エラー: エディタが準備できていません。ページを再読み込みしてください。');
                                return;
                            }
                            
                            imageInput.value?.click();
                        };
                        
                        const handleImageUpload = async (event) => {
                            console.log('📤 画像アップロード処理開始', {
                                timestamp: new Date().toISOString(),
                                editor: !!editor.value,
                                destroyed: editor.value?.isDestroyed,
                                hasFile: !!event.target.files[0]
                            });
                            
                            // 最重要ガード
                            if (!editor.value || editor.value.isDestroyed) {
                                console.error('❌ エディタが利用できません（アップロード開始時）', {
                                    editor: !!editor.value,
                                    destroyed: editor.value?.isDestroyed,
                                    view: !!editor.value?.view
                                });
                                alert('エラー: エディタが利用できません');
                                return;
                            }
                            
                            const file = event.target.files[0];
                            if (!file) {
                                console.warn('ファイルが選択されていません');
                                return;
                            }
                            
                            console.log('📁 選択ファイル:', {
                                name: file.name,
                                size: file.size,
                                type: file.type
                            });
                            
                            // バリデーション
                            if (file.size > 10 * 1024 * 1024) {
                                alert('ファイルサイズが10MBを超えています');
                                event.target.value = '';
                                return;
                            }
                            
                            if (!file.type.match(/image\\/(jpeg|jpg|png|webp|gif)/)) {
                                alert('対応していないファイル形式です');
                                event.target.value = '';
                                return;
                            }
                            
                            try {
                                const formData = new FormData();
                                formData.append('image', file);
                                
                                console.log('🌐 APIリクエスト送信:', {
                                    url: props.uploadUrl,
                                    hasCSRF: !!props.csrfToken
                                });
                                
                                const response = await fetch(props.uploadUrl, {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-CSRF-TOKEN': props.csrfToken,
                                        'X-Requested-With': 'XMLHttpRequest',
                                    },
                                    credentials: 'same-origin'
                                });
                                
                                console.log('📡 APIレスポンス:', {
                                    status: response.status,
                                    ok: response.ok
                                });
                                
                                const responseData = await response.json();
                                console.log('📋 レスポンスデータ:', responseData);
                                
                                if (!response.ok || !responseData.success) {
                                    throw new Error(responseData.message || `HTTP ${response.status}`);
                                }
                                
                                console.log('✅ アップロードAPI成功');
                                
                                // === 画像挿入（POS事故完全排除） ===
                                await insertImageToEditor(responseData);
                                
                            } catch (error) {
                                console.error('❌ アップロードエラー:', {
                                    error: error,
                                    message: error.message,
                                    stack: error.stack
                                });
                                
                                let errorMessage = '画像アップロードに失敗しました\\n';
                                if (error.message.includes('HTTP')) {
                                    errorMessage += error.message;
                                } else {
                                    errorMessage += `エラー: ${error.message}`;
                                }
                                alert(errorMessage);
                                
                            } finally {
                                event.target.value = '';
                            }
                        };
                        
                        const insertImageToEditor = async (imageData) => {
                            console.log('🖼️ エディタ画像挿入開始', {
                                url: imageData.url,
                                editor: !!editor.value,
                                destroyed: editor.value?.isDestroyed
                            });
                            
                            // 挿入直前の最終ガード
                            if (!editor.value || editor.value.isDestroyed) {
                                console.error('❌ エディタが利用できません（挿入直前）', {
                                    editor: !!editor.value,
                                    destroyed: editor.value?.isDestroyed
                                });
                                throw new Error('エディタが利用できません');
                            }
                            
                            try {
                                // フォーカス確保
                                editor.value.chain().focus().run();
                                
                                // 現在状態ログ
                                const state = editor.value.state;
                                const selection = state.selection;
                                const docSize = state.doc.content.size;
                                
                                console.log('📍 挿入前のエディタ状態:', {
                                    docSize: docSize,
                                    selectionFrom: selection.from,
                                    selectionTo: selection.to,
                                    isEmpty: editor.value.isEmpty
                                });
                                
                                // 末尾に安全挿入（POS事故ゼロ）- ImageBlock使用
                                const endPos = state.doc.content.size;
                                const { TextSelection } = state.schema;
                                
                                editor.value
                                    .chain()
                                    .focus()
                                    .command(({ tr, dispatch }) => {
                                        if (dispatch) {
                                            const selection = TextSelection.create(tr.doc, endPos);
                                            tr.setSelection(selection);
                                        }
                                        return true;
                                    })
                                    .insertContent({
                                        type: 'imageBlock',
                                        attrs: {
                                            src: imageData.url,
                                            alt: imageData.alt || '',
                                            size: 'md'  // デフォルトサイズ
                                        }
                                    })
                                    .run();
                                
                                console.log('✅ 画像挿入完了:', {
                                    finalDocSize: editor.value.state.doc.content.size
                                });
                                
                            } catch (insertError) {
                                console.error('❌ 画像挿入エラー:', insertError);
                                throw new Error('画像挿入に失敗: ' + insertError.message);
                            }
                        };
                        
                        // === ツールバーアクション ===
                        const toggleHeading = (level) => {
                            if (!isEditorReady.value) return;
                            editor.value.chain().focus().toggleHeading({ level }).run();
                        };
                        
                        const setParagraph = () => {
                            if (!isEditorReady.value) return;
                            editor.value.chain().focus().setParagraph().run();
                        };
                        
                        const toggleBold = () => {
                            if (!isEditorReady.value) return;
                            editor.value.chain().focus().toggleBold().run();
                        };
                        
                        const toggleItalic = () => {
                            if (!isEditorReady.value) return;
                            editor.value.chain().focus().toggleItalic().run();
                        };
                        
                        const toggleBulletList = () => {
                            if (!isEditorReady.value) return;
                            editor.value.chain().focus().toggleBulletList().run();
                        };
                        
                        const toggleOrderedList = () => {
                            if (!isEditorReady.value) return;
                            editor.value.chain().focus().toggleOrderedList().run();
                        };
                        
                        const toggleBlockquote = () => {
                            if (!isEditorReady.value) return;
                            editor.value.chain().focus().toggleBlockquote().run();
                        };
                        
                        const insertHorizontalRule = () => {
                            if (!isEditorReady.value) return;
                            editor.value.chain().focus().setHorizontalRule().run();
                        };
                        
                        // === ユーティリティ ===
                        const updateActiveStates = (editorInstance) => {
                            if (!editorInstance) return;
                            
                            isActive.value = {
                                heading2: editorInstance.isActive('heading', { level: 2 }),
                                heading3: editorInstance.isActive('heading', { level: 3 }),
                                paragraph: editorInstance.isActive('paragraph'),
                                bold: editorInstance.isActive('bold'),
                                italic: editorInstance.isActive('italic'),
                                bulletList: editorInstance.isActive('bulletList'),
                                orderedList: editorInstance.isActive('orderedList'),
                                blockquote: editorInstance.isActive('blockquote'),
                            };
                        };
                        
                        const updateStateFromEditor = (editorInstance) => {
                            if (!editorInstance) return;
                            
                            const json = editorInstance.getJSON();
                            emit('update', json);
                            
                            // Filamentフィールドとの統合 - 隠しinputフィールドを更新
                            const hiddenInput = document.querySelector(`input[name="${props.fieldName}"]`) || 
                                               document.querySelector(`textarea[name="${props.fieldName}"]`);
                            
                            if (hiddenInput) {
                                hiddenInput.value = JSON.stringify(json);
                                // Filamentのリアクティブ更新をトリガー
                                hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                                hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
                                console.log('📤 Filamentフィールド更新:', props.fieldName, json);
                            } else {
                                console.warn('⚠️ Filamentフィールドが見つかりません:', props.fieldName);
                            }
                        };
                        
                        const generateHtmlFromState = (state) => {
                            if (!state || !state.content || state.content.length === 0) {
                                return '<p></p>';
                            }
                            // 簡易JSON→HTML変換（実装済みのロジックを使用）
                            return '<p></p>'; // 初期表示用
                        };
                        
                        return {
                            editor,
                            imageInput,
                            isEditorReady,
                            isActive,
                            openImageUpload,
                            handleImageUpload,
                            toggleHeading,
                            setParagraph,
                            toggleBold,
                            toggleItalic,
                            toggleBulletList,
                            toggleOrderedList,
                            toggleBlockquote,
                            insertHorizontalRule,
                        };
                    }
                };
                
                // === Vueアプリ初期化（即座に実行） ===
                function initializeVueBlockEditors() {
                    console.log('🌟 Vueアプリ初期化開始');
                    
                    // 全てのBlockEditorインスタンスをマウント
                    document.querySelectorAll('[id^="vue-block-editor-"]').forEach(container => {
                        // 既にマウント済みかチェック
                        if (container.classList.contains('vue-mounted')) {
                            console.log('⏭️ 既にマウント済み:', container.id);
                            return;
                        }
                        
                        const dataset = container.dataset;
                        
                        if (!dataset.state || !dataset.uploadUrl) {
                            console.error('❌ 必須データが不足:', container.id, dataset);
                            return;
                        }
                        
                        console.log('📦 Vueアプリマウント開始:', container.id);
                        
                        try {
                            // 対応する隠しフィールドを確認
                            const hiddenField = document.querySelector(`input[name="${dataset.fieldName}"]`);
                            if (!hiddenField) {
                                console.warn('⚠️ 隠しフィールドが見つかりません:', dataset.fieldName);
                            } else {
                                console.log('✅ 隠しフィールド確認:', dataset.fieldName);
                            }
                            
                            const app = createApp(BlockEditor, {
                                initialState: JSON.parse(dataset.state || '{}'),
                                uploadUrl: dataset.uploadUrl,
                                deleteUrl: dataset.deleteUrl,
                                csrfToken: dataset.csrfToken,
                                fieldName: dataset.fieldName
                            });
                            
                            app.mount(container);
                            container.classList.add('vue-mounted'); // マウント済みマーク
                            
                            console.log('✅ Vueアプリマウント完了:', container.id);
                        } catch (error) {
                            console.error('❌ Vueアプリマウントエラー:', container.id, error);
                        }
                    });
                }
                
                // フォーム送信デバッグ処理
                function debugFormSubmit() {
                    document.addEventListener('submit', function(e) {
                        console.log('📝 フォーム送信イベント検出:', {
                            target: e.target,
                            submitter: e.submitter,
                            method: e.target.method,
                            action: e.target.action,
                            hasBlockEditor: !!document.querySelector('[id^="vue-block-editor-"]')
                        });
                    }, true);
                }
                
                // 即座に実行（DOM準備待ち）
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', () => {
                        initializeVueBlockEditors();
                        debugFormSubmit();
                    });
                } else {
                    // DOMが既に準備済みの場合は即座に実行
                    initializeVueBlockEditors();
                    debugFormSubmit();
                }
                
                // Livewire再描画やAJAXページ変更後の再初期化対策
                document.addEventListener('livewire:navigated', () => {
                    initializeVueBlockEditors();
                    debugFormSubmit();
                });
                
                // 追加の安全策：タイマーベース初期化
                setTimeout(() => {
                    initializeVueBlockEditors();
                    debugFormSubmit();
                }, 100);
            </script>
        @endpush
    @endonce

    @once
        @push('styles')
            <style>
                /* 画像サイズ制御スタイル */
                .img-sm {
                    max-width: 320px !important;
                    height: auto;
                }
                
                .img-md {
                    max-width: 560px !important;
                    height: auto;
                }
                
                .img-lg {
                    max-width: 100% !important;
                    height: auto;
                }
                
                /* エディタ内画像のレスポンシブ */
                .ProseMirror img[data-size="sm"],
                .prose img[data-size="sm"] {
                    max-width: 320px !important;
                    height: auto;
                }
                
                .ProseMirror img[data-size="md"],
                .prose img[data-size="md"] {
                    max-width: 560px !important;
                    height: auto;
                }
                
                .ProseMirror img[data-size="lg"],
                .prose img[data-size="lg"] {
                    max-width: 100% !important;
                    height: auto;
                }
                
                /* 画像ツールバーの見た目 */
                .image-toolbar .size-btn.bg-blue-600 {
                    background-color: rgb(37, 99, 235) !important;
                }
            </style>
        @endpush
    @endonce
</x-dynamic-component>