import { Editor } from '@tiptap/core'
import StarterKit from '@tiptap/starter-kit'
import Image from '@tiptap/extension-image'
import Link from '@tiptap/extension-link'
import TextAlign from '@tiptap/extension-text-align'
import Placeholder from '@tiptap/extension-placeholder'

let editorInstance = null;

// Custom Image Block Extension
const ImageBlock = Image.extend({
    addAttributes() {
        return {
            ...this.parent?.(),
            caption: {
                default: '',
            },
            alignment: {
                default: 'center',
            },
            width: {
                default: 'normal',
            },
            customWidth: {
                default: null,
            }
        }
    },

    renderHTML({ HTMLAttributes }) {
        const { caption, alignment, width, customWidth, ...imgAttrs } = HTMLAttributes;
        
        let classes = ['block-image'];
        classes.push(`align-${alignment}`);
        classes.push(`width-${width}`);
        
        const figure = ['figure', { class: classes.join(' ') }];
        const img = ['img', { ...imgAttrs, draggable: false }];
        
        if (customWidth) {
            img[1].style = `width: ${customWidth}px`;
        }
        
        const content = [img];
        
        if (caption) {
            content.push(['figcaption', caption]);
        }
        
        figure.push(...content);
        return figure;
    },

    addNodeView() {
        return ({ node, getPos, editor }) => {
            const container = document.createElement('div');
            container.className = 'image-block-wrapper';
            
            const figure = document.createElement('figure');
            figure.className = `block-image align-${node.attrs.alignment} width-${node.attrs.width}`;
            
            const img = document.createElement('img');
            img.src = node.attrs.src;
            img.alt = node.attrs.alt || '';
            img.draggable = false;
            
            if (node.attrs.customWidth) {
                img.style.width = `${node.attrs.customWidth}px`;
            }
            
            const caption = document.createElement('div');
            caption.className = 'image-caption';
            caption.contentEditable = true;
            caption.innerText = node.attrs.caption || '';
            caption.placeholder = 'キャプションを入力...';
            
            // Controls
            const controls = document.createElement('div');
            controls.className = 'image-controls';
            controls.innerHTML = `
                <div class="image-control-buttons">
                    <button type="button" class="btn-align" data-align="left" title="左寄せ">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="17" y1="10" x2="3" y2="10"></line>
                            <line x1="21" y1="6" x2="3" y2="6"></line>
                            <line x1="21" y1="14" x2="3" y2="14"></line>
                            <line x1="17" y1="18" x2="3" y2="18"></line>
                        </svg>
                    </button>
                    <button type="button" class="btn-align" data-align="center" title="中央寄せ">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="10" x2="6" y2="10"></line>
                            <line x1="21" y1="6" x2="3" y2="6"></line>
                            <line x1="21" y1="14" x2="3" y2="14"></line>
                            <line x1="18" y1="18" x2="6" y2="18"></line>
                        </svg>
                    </button>
                    <button type="button" class="btn-align" data-align="right" title="右寄せ">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="21" y1="10" x2="7" y2="10"></line>
                            <line x1="21" y1="6" x2="3" y2="6"></line>
                            <line x1="21" y1="14" x2="3" y2="14"></line>
                            <line x1="21" y1="18" x2="7" y2="18"></line>
                        </svg>
                    </button>
                    <div class="width-controls">
                        <select class="width-select">
                            <option value="normal" ${node.attrs.width === 'normal' ? 'selected' : ''}>通常</option>
                            <option value="wide" ${node.attrs.width === 'wide' ? 'selected' : ''}>ワイド</option>
                            <option value="full" ${node.attrs.width === 'full' ? 'selected' : ''}>全幅</option>
                        </select>
                    </div>
                    <button type="button" class="btn-replace" title="画像を差し替え">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21,15 16,10 5,21"></polyline>
                        </svg>
                    </button>
                    <button type="button" class="btn-delete" title="削除">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3,6 5,6 21,6"></polyline>
                            <path d="m19,6v14a2,2,0,0,1-2,2H7a2,2,0,0,1-2-2V6m3,0V4a2,2,0,0,1,2-2h4a2,2,0,0,1,2,2v2"></path>
                        </svg>
                    </button>
                </div>
            `;
            
            figure.appendChild(img);
            figure.appendChild(caption);
            container.appendChild(figure);
            container.appendChild(controls);
            
            // Event listeners
            caption.addEventListener('blur', () => {
                if (typeof getPos === 'function') {
                    editor.commands.updateAttributes('imageBlock', { caption: caption.innerText });
                }
            });
            
            controls.addEventListener('click', (e) => {
                const btn = e.target.closest('button');
                if (!btn) return;
                
                if (btn.classList.contains('btn-align')) {
                    const align = btn.dataset.align;
                    editor.commands.updateAttributes('imageBlock', { alignment: align });
                } else if (btn.classList.contains('btn-replace')) {
                    // Trigger file input
                    const fileInput = document.createElement('input');
                    fileInput.type = 'file';
                    fileInput.accept = 'image/*';
                    fileInput.onchange = (e) => {
                        const file = e.target.files[0];
                        if (file) {
                            uploadImage(file, editor.options.uploadUrl, editor.options.csrfToken)
                                .then(result => {
                                    editor.commands.updateAttributes('imageBlock', { 
                                        src: result.url,
                                        'data-path': result.path 
                                    });
                                })
                                .catch(console.error);
                        }
                    };
                    fileInput.click();
                } else if (btn.classList.contains('btn-delete')) {
                    if (typeof getPos === 'function') {
                        editor.commands.deleteRange({ from: getPos(), to: getPos() + node.nodeSize });
                    }
                }
            });
            
            const widthSelect = controls.querySelector('.width-select');
            widthSelect.addEventListener('change', () => {
                editor.commands.updateAttributes('imageBlock', { width: widthSelect.value });
            });
            
            return {
                dom: container,
                update: (updatedNode) => {
                    if (updatedNode.type.name !== 'imageBlock') return false;
                    
                    // Update figure class
                    figure.className = `block-image align-${updatedNode.attrs.alignment} width-${updatedNode.attrs.width}`;
                    
                    // Update image
                    img.src = updatedNode.attrs.src;
                    img.alt = updatedNode.attrs.alt || '';
                    
                    if (updatedNode.attrs.customWidth) {
                        img.style.width = `${updatedNode.attrs.customWidth}px`;
                    } else {
                        img.style.width = '';
                    }
                    
                    // Update caption
                    caption.innerText = updatedNode.attrs.caption || '';
                    
                    // Update controls
                    widthSelect.value = updatedNode.attrs.width;
                    
                    return true;
                }
            }
        }
    }
});

// Upload helper function
async function uploadImage(file, uploadUrl, csrfToken) {
    const formData = new FormData();
    formData.append('image', file);
    
    const response = await fetch(uploadUrl, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        }
    });
    
    const result = await response.json();
    
    if (!response.ok) {
        throw new Error(result.message || 'Upload failed');
    }
    
    return result;
}

// Block Editor initialization function
window.initBlockEditor = function(element, options = {}) {
    if (editorInstance) {
        editorInstance.destroy();
    }
    
    const editor = new Editor({
        element,
        extensions: [
            StarterKit.configure({
                heading: {
                    levels: [2, 3]
                }
            }),
            ImageBlock,
            Link.configure({
                openOnClick: false
            }),
            TextAlign.configure({
                types: ['heading', 'paragraph']
            }),
            Placeholder.configure({
                placeholder: 'ブロックを追加するには + をクリック...'
            })
        ],
        content: options.content || [],
        editorProps: {
            attributes: {
                class: 'prose prose-lg max-w-none focus:outline-none min-h-[300px] p-4'
            }
        },
        onUpdate: ({ editor }) => {
            const json = editor.getJSON();
            if (options.onChange) {
                options.onChange(json);
            }
        }
    });
    
    // Store upload options in editor
    editor.options.uploadUrl = options.uploadUrl;
    editor.options.deleteUrl = options.deleteUrl;
    editor.options.csrfToken = options.csrfToken;
    
    // Add toolbar
    const toolbar = createToolbar(editor, options);
    element.parentNode.insertBefore(toolbar, element);
    
    editorInstance = editor;
    return editor;
};

function createToolbar(editor, options) {
    const toolbar = document.createElement('div');
    toolbar.className = 'block-editor-toolbar';
    toolbar.innerHTML = `
        <div class="toolbar-group">
            <button type="button" data-action="heading2" class="toolbar-btn" title="見出し2">
                <strong>H2</strong>
            </button>
            <button type="button" data-action="heading3" class="toolbar-btn" title="見出し3">
                <strong>H3</strong>
            </button>
            <button type="button" data-action="paragraph" class="toolbar-btn" title="段落">
                <span>P</span>
            </button>
        </div>
        <div class="toolbar-group">
            <button type="button" data-action="bold" class="toolbar-btn" title="太字">
                <strong>B</strong>
            </button>
            <button type="button" data-action="italic" class="toolbar-btn" title="斜体">
                <em>I</em>
            </button>
            <button type="button" data-action="link" class="toolbar-btn" title="リンク">
                🔗
            </button>
        </div>
        <div class="toolbar-group">
            <button type="button" data-action="bulletList" class="toolbar-btn" title="箇条書き">
                • List
            </button>
            <button type="button" data-action="orderedList" class="toolbar-btn" title="番号付きリスト">
                1. List
            </button>
            <button type="button" data-action="blockquote" class="toolbar-btn" title="引用">
                " Quote
            </button>
        </div>
        <div class="toolbar-group">
            <button type="button" data-action="image" class="toolbar-btn" title="画像">
                🖼️ 画像
            </button>
            <button type="button" data-action="horizontalRule" class="toolbar-btn" title="区切り線">
                ―
            </button>
        </div>
    `;
    
    toolbar.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-action]');
        if (!btn) return;
        
        e.preventDefault();
        
        const action = btn.dataset.action;
        
        switch (action) {
            case 'heading2':
                editor.chain().focus().toggleHeading({ level: 2 }).run();
                break;
            case 'heading3':
                editor.chain().focus().toggleHeading({ level: 3 }).run();
                break;
            case 'paragraph':
                editor.chain().focus().setParagraph().run();
                break;
            case 'bold':
                editor.chain().focus().toggleBold().run();
                break;
            case 'italic':
                editor.chain().focus().toggleItalic().run();
                break;
            case 'link':
                const url = prompt('リンクURLを入力してください:');
                if (url) {
                    editor.chain().focus().setLink({ href: url }).run();
                }
                break;
            case 'bulletList':
                editor.chain().focus().toggleBulletList().run();
                break;
            case 'orderedList':
                editor.chain().focus().toggleOrderedList().run();
                break;
            case 'blockquote':
                editor.chain().focus().toggleBlockquote().run();
                break;
            case 'image':
                const fileInput = document.createElement('input');
                fileInput.type = 'file';
                fileInput.accept = 'image/*';
                fileInput.onchange = (e) => {
                    const file = e.target.files[0];
                    if (file) {
                        uploadImage(file, options.uploadUrl, options.csrfToken)
                            .then(result => {
                                editor.chain().focus().setImage({
                                    src: result.url,
                                    alt: result.filename,
                                    'data-path': result.path,
                                    caption: '',
                                    alignment: 'center',
                                    width: 'normal'
                                }).run();
                            })
                            .catch(console.error);
                    }
                };
                fileInput.click();
                break;
            case 'horizontalRule':
                editor.chain().focus().setHorizontalRule().run();
                break;
        }
        
        // Update active states
        updateToolbarStates(toolbar, editor);
    });
    
    // Update toolbar state on selection change
    editor.on('selectionUpdate', () => {
        updateToolbarStates(toolbar, editor);
    });
    
    return toolbar;
}

function updateToolbarStates(toolbar, editor) {
    const buttons = toolbar.querySelectorAll('[data-action]');
    
    buttons.forEach(btn => {
        const action = btn.dataset.action;
        let isActive = false;
        
        switch (action) {
            case 'heading2':
                isActive = editor.isActive('heading', { level: 2 });
                break;
            case 'heading3':
                isActive = editor.isActive('heading', { level: 3 });
                break;
            case 'paragraph':
                isActive = editor.isActive('paragraph');
                break;
            case 'bold':
                isActive = editor.isActive('bold');
                break;
            case 'italic':
                isActive = editor.isActive('italic');
                break;
            case 'link':
                isActive = editor.isActive('link');
                break;
            case 'bulletList':
                isActive = editor.isActive('bulletList');
                break;
            case 'orderedList':
                isActive = editor.isActive('orderedList');
                break;
            case 'blockquote':
                isActive = editor.isActive('blockquote');
                break;
        }
        
        btn.classList.toggle('active', isActive);
    });
}

export { initBlockEditor };