<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'tags',
        'company_id',
        'guide_type',
        'content',
        'custom_css',
        'custom_html_blocks',
        'is_published',
        'published_at',
        'is_featured',
        'featured_image',
        'supervisor_name',
        'supervisor_title',
        'supervisor_description',
        'supervisor_avatar',
    ];

    protected $casts = [
        'tags' => 'array',
        'custom_html_blocks' => 'array',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // 公開設定時に自動的にpublished_atを設定
    protected static function booted()
    {
        static::saving(function ($article) {
            if ($article->is_published && !$article->published_at) {
                $article->published_at = now();
            } elseif (!$article->is_published) {
                $article->published_at = null;
            }
        });
    }

    // Scope for published articles
    public function scopePublished(Builder $query)
    {
        return $query->where('is_published', true)
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    // Scope for featured articles
    public function scopeFeatured(Builder $query)
    {
        return $query->where('is_featured', true);
    }

    // Get featured image URL
    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            return \Storage::disk('public')->url($this->featured_image);
        }
        return null;
    }

    // Get rendered content for display with custom HTML blocks - simplified for TinyMCE
    public function getRenderedContentAttribute()
    {
        // Since we're using TinyMCE now, content field contains clean HTML
        $content = $this->content ?? '';
        
        // Replace HTML shortcodes with custom HTML blocks
        $content = $this->replaceHtmlShortcodes($content);
        
        // Apply security cleaning and image link removal
        return $this->removeImageLinks($content);
    }

    // Replace [html:key] shortcodes with custom HTML blocks
    public function replaceHtmlShortcodes($content)
    {
        if (empty($content) || empty($this->custom_html_blocks)) {
            return $content;
        }

        // Create a lookup array for faster access
        $htmlBlocks = [];
        foreach ($this->custom_html_blocks as $block) {
            if (!empty($block['key']) && !empty($block['html'])) {
                $htmlBlocks[$block['key']] = $block['html'];
            }
        }

        // Replace shortcodes with HTML
        $content = preg_replace_callback(
            '/\[html:([a-zA-Z0-9_-]+)\]/',
            function ($matches) use ($htmlBlocks) {
                $key = $matches[1];
                if (isset($htmlBlocks[$key])) {
                    // Sanitize the HTML content
                    $html = $this->sanitizeCustomHtml($htmlBlocks[$key]);
                    // Wrap in article-specific container
                    return '<div class="custom-html-block article-content-' . $this->id . '">' . $html . '</div>';
                }
                // Return shortcode as-is if no matching block found
                return $matches[0];
            },
            $content
        );

        return $content;
    }

    // Sanitize custom HTML blocks
    private function sanitizeCustomHtml($html)
    {
        if (empty($html)) {
            return $html;
        }

        // Remove script tags and dangerous attributes
        $html = preg_replace('/<script[^>]*>.*?<\/script>/si', '', $html);
        $html = preg_replace('/on\w+\s*=\s*["\'][^"\']*["\']/', '', $html);
        $html = preg_replace('/javascript\s*:/', '', $html);

        return $html;
    }

    // Get scoped custom CSS for this article
    public function getScopedCustomCssAttribute()
    {
        if (empty($this->custom_css)) {
            return '';
        }

        $css = $this->custom_css;
        $scopedCss = '';

        // Split CSS by rules and add scope prefix to each selector
        preg_match_all('/([^{]+)\{([^}]*)\}/s', $css, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $selector = trim($match[1]);
            $properties = trim($match[2]);

            if (empty($selector) || empty($properties)) {
                continue;
            }

            // Handle multiple selectors separated by comma
            $selectors = explode(',', $selector);
            $scopedSelectors = [];
            
            foreach ($selectors as $sel) {
                $sel = trim($sel);
                if (!empty($sel)) {
                    // Add article-specific scope to each selector with higher priority
                    $scopedSelectors[] = '.article-content.article-content-' . $this->id . ' ' . $sel;
                }
            }

            if (!empty($scopedSelectors)) {
                $scopedCss .= implode(', ', $scopedSelectors) . ' { ' . $properties . " }\n";
            }
        }

        return $scopedCss;
    }

    // Remove <a> tags around images in content and sanitize JS/attributes
    private function removeImageLinks($content)
    {
        // First, protect CTA buttons from being processed
        $ctaPlaceholders = [];
        $ctaPattern = '/<div[^>]*class="[^"]*my-8[^"]*"[^>]*>.*?<\/div>/s';
        preg_match_all($ctaPattern, $content, $ctaMatches);
        
        foreach ($ctaMatches[0] as $index => $ctaHtml) {
            $placeholder = "___CTA_PLACEHOLDER_{$index}___";
            $ctaPlaceholders[$placeholder] = $ctaHtml;
            $content = str_replace($ctaHtml, $placeholder, $content);
        }
        
        // Sanitize content to remove malicious JavaScript and unwanted attributes
        $content = $this->sanitizeContent($content);
        
        // Pattern to match <a> tags that contain only an <img> tag and possibly a <figcaption>
        $pattern = '/<a[^>]*href="[^"]*"[^>]*>\s*(<img[^>]*>)(?:\s*<figcaption[^>]*>.*?<\/figcaption>)?\s*<\/a>/s';
        
        // Replace with just the img tag and figcaption (if exists)
        $content = preg_replace($pattern, '$1', $content);
        
        // Also handle figure > a > img structure
        $figurePattern = '/(<figure[^>]*>)\s*<a[^>]*href="[^"]*"[^>]*>\s*(<img[^>]*>)\s*<\/a>(\s*<figcaption[^>]*>.*?<\/figcaption>)?\s*(<\/figure>)/s';
        $content = preg_replace($figurePattern, '$1$2$3$4', $content);
        
        // Restore CTA buttons
        foreach ($ctaPlaceholders as $placeholder => $ctaHtml) {
            $content = str_replace($placeholder, $ctaHtml, $content);
        }
        
        return $content;
    }
    
    // Sanitize content to remove JavaScript and unwanted attributes while preserving images
    private function sanitizeContent($content)
    {
        if (empty($content)) {
            return $content;
        }
        
        // Load the content into DOMDocument
        $dom = new \DOMDocument('1.0', 'UTF-8');
        
        // Suppress errors for malformed HTML
        libxml_use_internal_errors(true);
        
        // Add proper HTML structure and UTF-8 meta tag for proper encoding
        $htmlContent = '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body>' . $content . '</body></html>';
        $dom->loadHTML($htmlContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        
        // Get body element
        $body = $dom->getElementsByTagName('body')->item(0);
        if (!$body) {
            return $content;
        }
        
        // Remove unwanted elements and attributes
        $this->sanitizeNode($body);
        
        // Get the cleaned content
        $cleanedContent = '';
        foreach ($body->childNodes as $node) {
            $cleanedContent .= $dom->saveHTML($node);
        }
        
        // Clear libxml errors
        libxml_clear_errors();
        
        return $cleanedContent;
    }
    
    // Recursively sanitize DOM nodes
    private function sanitizeNode($node)
    {
        if (!$node->hasChildNodes()) {
            return;
        }
        
        $nodesToRemove = [];
        
        foreach ($node->childNodes as $child) {
            if ($child->nodeType === XML_ELEMENT_NODE) {
                $tagName = strtolower($child->tagName);
                
                // Remove script and style tags completely
                if (in_array($tagName, ['script', 'style'])) {
                    $nodesToRemove[] = $child;
                    continue;
                }
                
                // Clean attributes for all elements
                $this->cleanAttributes($child);
                
                // Recursively clean child nodes
                $this->sanitizeNode($child);
                
            } elseif ($child->nodeType === XML_TEXT_NODE) {
                // Remove text nodes that contain JavaScript-like patterns
                $text = $child->textContent;
                if ($this->containsJavaScriptPatterns($text)) {
                    $nodesToRemove[] = $child;
                }
            }
        }
        
        // Remove flagged nodes
        foreach ($nodesToRemove as $nodeToRemove) {
            $node->removeChild($nodeToRemove);
        }
    }
    
    // Clean element attributes
    private function cleanAttributes($element)
    {
        $allowedAttributes = [
            // Global safe attributes
            'id', 'class', 'title', 'alt', 'href', 'src', 'width', 'height',
            // Image specific
            'srcset', 'sizes',
            // Link specific  
            'target', 'rel',
            // Figure/caption specific
            'data-trix-attachment', 'data-trix-content-type',
            // Table specific
            'colspan', 'rowspan', 'border', 'cellpadding', 'cellspacing', 'contenteditable',
            // Text formatting
            'style' // We'll sanitize this separately
        ];
        
        $attributesToRemove = [];
        
        if ($element->hasAttributes()) {
            foreach ($element->attributes as $attr) {
                $attrName = strtolower($attr->name);
                $attrValue = $attr->value;
                
                // Remove event handlers (onclick, onload, etc.)
                if (strpos($attrName, 'on') === 0) {
                    $attributesToRemove[] = $attr->name;
                    continue;
                }
                
                // Remove data-click-handler and similar dangerous data attributes
                if (in_array($attrName, ['data-click-handler', 'data-resize-enabled', 'data-size-modified'])) {
                    $attributesToRemove[] = $attr->name;
                    continue;
                }
                
                // Remove javascript: protocols
                if (in_array($attrName, ['href', 'src']) && 
                    (strpos(strtolower($attrValue), 'javascript:') === 0 || 
                     strpos(strtolower($attrValue), 'data:text/html') === 0)) {
                    $attributesToRemove[] = $attr->name;
                    continue;
                }
                
                // Sanitize style attribute
                if ($attrName === 'style') {
                    $cleanStyle = $this->sanitizeStyleAttribute($attrValue);
                    if (empty($cleanStyle)) {
                        $attributesToRemove[] = $attr->name;
                    } else {
                        $element->setAttribute($attr->name, $cleanStyle);
                    }
                    continue;
                }
                
                // Allow custom button classes
                if ($attrName === 'class') {
                    $classValue = $attrValue;
                    // Allow our custom button classes
                    $allowedButtonClasses = ['inline-block', 'px-4', 'py-2', 'rounded-lg', 'font-bold', 'text-white', 'bg-orange-500', 'bg-orange-600', 'bg-blue-600', 'bg-blue-700', 'hover:bg-orange-600', 'hover:bg-blue-700'];
                    $classes = explode(' ', $classValue);
                    $filteredClasses = [];
                    foreach ($classes as $class) {
                        if (in_array($class, $allowedButtonClasses) || strpos($class, 'hover:') === 0 || strpos($class, 'bg-') === 0 || in_array($class, ['inline-block', 'px-4', 'py-2', 'rounded-lg', 'font-bold', 'text-white'])) {
                            $filteredClasses[] = $class;
                        }
                    }
                    if (!empty($filteredClasses)) {
                        $element->setAttribute($attr->name, implode(' ', $filteredClasses));
                    } else {
                        $attributesToRemove[] = $attr->name;
                    }
                    continue;
                }

                // Remove attributes not in allowlist
                if (!in_array($attrName, $allowedAttributes) && 
                    !preg_match('/^data-trix-/', $attrName)) {
                    $attributesToRemove[] = $attr->name;
                }
            }
        }
        
        // Remove flagged attributes
        foreach ($attributesToRemove as $attrName) {
            $element->removeAttribute($attrName);
        }
    }
    
    // Sanitize CSS in style attributes
    private function sanitizeStyleAttribute($style)
    {
        // Remove dangerous CSS properties and functions
        $dangerousPatterns = [
            '/expression\s*\(/i',
            '/javascript\s*:/i',
            '/behavior\s*:/i',
            '/binding\s*:/i',
            '/-moz-binding/i',
            '/cursor\s*:\s*pointer/i', // Remove cursor pointer that was added by JS
        ];
        
        foreach ($dangerousPatterns as $pattern) {
            $style = preg_replace($pattern, '', $style);
        }
        
        // Keep only safe CSS properties for images and text formatting
        $allowedProperties = [
            'width', 'height', 'max-width', 'max-height', 'min-width', 'min-height',
            'margin', 'margin-top', 'margin-right', 'margin-bottom', 'margin-left',
            'padding', 'padding-top', 'padding-right', 'padding-bottom', 'padding-left',
            'border', 'border-radius', 'box-shadow', 'display', 'float', 'clear',
            'color', 'background-color', 'background', 'font-size', 'font-weight',
            'font-family', 'text-align', 'text-decoration', 'line-height'
        ];
        
        // Parse and filter style properties
        $cleanProperties = [];
        $properties = explode(';', $style);
        
        foreach ($properties as $property) {
            $property = trim($property);
            if (empty($property)) continue;
            
            $parts = explode(':', $property, 2);
            if (count($parts) !== 2) continue;
            
            $propName = trim(strtolower($parts[0]));
            $propValue = trim($parts[1]);
            
            if (in_array($propName, $allowedProperties) && !empty($propValue)) {
                $cleanProperties[] = $propName . ':' . $propValue;
            }
        }
        
        return implode(';', $cleanProperties);
    }
    
    // Check if text contains JavaScript-like patterns
    private function containsJavaScriptPatterns($text)
    {
        $jsPatterns = [
            '/function\s*\(/i',
            '/\}\s*\)\s*;/i',
            '/document\./i',
            '/console\./i',
            '/alert\s*\(/i',
            '/getElementById/i',
            '/addEventListener/i',
            '/data-click-handler/i',
            '/data-resize-enabled/i',
            '/createSizeToolbar/i',
            '/style\.border/i',
            '/style\.boxShadow/i',
        ];
        
        foreach ($jsPatterns as $pattern) {
            if (preg_match($pattern, $text)) {
                return true;
            }
        }
        
        return false;
    }

    // Public method for sanitizing content before save (called from ArticleResource)
    public function sanitizeContentForSave($content)
    {
        return $this->sanitizeContent($content);
    }

    // Safe method to check if article has any content at all
    public function hasAnyContent(): bool
    {
        return !empty($this->content_html) || 
               !empty($this->content_json) || 
               !empty($this->content);
    }

    // Check if article uses new block format
    public function usesBlockEditor(): bool
    {
        return !empty($this->content_json) && is_array($this->content_json);
    }

    // Check if article uses legacy format only
    public function usesLegacyEditor(): bool
    {
        return !empty($this->content) && empty($this->content_json);
    }

    // Get plain text for excerpts (safe fallback)
    public function getPlainTextContent(): string
    {
        // Try block content first
        if (!empty($this->content_json)) {
            try {
                $renderer = new \App\Services\ContentRenderer();
                return $renderer->renderToPlainText($this->content_json);
            } catch (\Exception $e) {
                \Log::warning('Failed to extract plain text from content_json for article ' . $this->id);
            }
        }

        // Fallback: strip HTML from legacy content
        return strip_tags($this->content ?? '');
    }
    
    /**
     * 文字列がTiptapのJSON形式かどうかをチェック
     */
    private function isTiptapJson(string $content): bool
    {
        $decoded = json_decode($content, true);
        return $decoded && isset($decoded['type']) && $decoded['type'] === 'doc';
    }
    
    /**
     * TiptapのJSONをHTMLに変換
     */
    private function convertTiptapJsonToHtml(string $json): string
    {
        try {
            $data = json_decode($json, true);
            if (!$data || !isset($data['type']) || $data['type'] !== 'doc') {
                return $json;
            }
            
            // TiptapのJSONをHTMLに変換
            return $this->renderTiptapContent($data['content'] ?? []);
        } catch (\Exception $e) {
            \Log::error('Failed to convert Tiptap JSON to HTML: ' . $e->getMessage());
            return $json;
        }
    }
    
    /**
     * Tiptapコンテンツを再帰的にHTMLにレンダリング
     */
    private function renderTiptapContent(array $content): string
    {
        $html = '';
        
        foreach ($content as $node) {
            $html .= $this->renderTiptapNode($node);
        }
        
        return $html;
    }
    
    /**
     * 単一のTiptapノードをHTMLに変換
     */
    private function renderTiptapNode(array $node): string
    {
        $type = $node['type'] ?? '';
        $content = $node['content'] ?? [];
        $attrs = $node['attrs'] ?? [];
        $marks = $node['marks'] ?? [];
        
        switch ($type) {
            case 'paragraph':
                $text = $this->renderTiptapContent($content);
                return "<p>{$text}</p>";
                
            case 'heading':
                $level = $attrs['level'] ?? 2;
                $text = $this->renderTiptapContent($content);
                return "<h{$level}>{$text}</h{$level}>";
                
            case 'text':
                $text = htmlspecialchars($node['text'] ?? '');
                return $this->applyTiptapMarks($text, $marks);
                
            case 'hardBreak':
                return '<br>';
                
            case 'blockquote':
                $text = $this->renderTiptapContent($content);
                return "<blockquote>{$text}</blockquote>";
                
            case 'bulletList':
                $items = $this->renderTiptapContent($content);
                return "<ul>{$items}</ul>";
                
            case 'orderedList':
                $items = $this->renderTiptapContent($content);
                return "<ol>{$items}</ol>";
                
            case 'listItem':
                $text = $this->renderTiptapContent($content);
                return "<li>{$text}</li>";
                
            case 'table':
                $rows = $this->renderTiptapContent($content);
                return "<table class=\"article-content table custom-compare-table\">{$rows}</table>";
                
            case 'tableRow':
                $cells = $this->renderTiptapContent($content);
                return "<tr>{$cells}</tr>";
                
            case 'tableHeader':
                $text = $this->renderTiptapContent($content);
                return "<th>{$text}</th>";
                
            case 'tableCell':
                $text = $this->renderTiptapContent($content);
                return "<td>{$text}</td>";
                
            case 'image':
                $src = $attrs['src'] ?? '';
                $alt = $attrs['alt'] ?? '';
                $class = $attrs['class'] ?? '';
                $style = $attrs['style'] ?? '';
                return "<img src=\"{$src}\" alt=\"{$alt}\" class=\"{$class}\" style=\"{$style}\">";
                
            case 'horizontalRule':
                return '<hr>';
                
            default:
                // 不明なノードは内容をレンダリング
                return $this->renderTiptapContent($content);
        }
    }
    
    /**
     * テキストにTiptapマークを適用
     */
    private function applyTiptapMarks(string $text, array $marks): string
    {
        foreach ($marks as $mark) {
            $type = $mark['type'] ?? '';
            $attrs = $mark['attrs'] ?? [];
            
            switch ($type) {
                case 'bold':
                    $text = "<strong>{$text}</strong>";
                    break;
                case 'italic':
                    $text = "<em>{$text}</em>";
                    break;
                case 'strike':
                    $text = "<s>{$text}</s>";
                    break;
                case 'link':
                    $href = htmlspecialchars($attrs['href'] ?? '#');
                    $text = "<a href=\"{$href}\">{$text}</a>";
                    break;
            }
        }
        
        // CTAボタンの変換も処理
        if (preg_match('/\[\[CTA\|([^|]+)\|([^|]+)\|([^\]]+)\]\]/', $text, $matches)) {
            $color = $matches[1];
            $url = htmlspecialchars($matches[2]);
            $buttonText = htmlspecialchars($matches[3]);
            $colorClass = $color === 'orange' ? 'bg-orange-600 hover:bg-orange-700' : 'bg-blue-600 hover:bg-blue-700';
            
            $text = preg_replace(
                '/\[\[CTA\|([^|]+)\|([^|]+)\|([^\]]+)\]\]/',
                '<div class="my-8">
                    <a href="' . $url . '" class="inline-flex items-center gap-2 max-w-lg px-6 py-4 rounded-xl font-bold text-lg text-white ' . $colorClass . ' transition-all duration-200 transform hover:scale-105 hover:shadow-lg">
                        ' . $buttonText . '
                    </a>
                </div>',
                $text
            );
        }
        
        return $text;
    }
}
