<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaticPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'page_type',
        'content',
        'featured_image',
        'is_published',
        'published_at',
        'supervisor_name',
        'supervisor_title',
        'supervisor_description',
        'supervisor_avatar',
        'custom_html_blocks',
        'custom_css',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'custom_html_blocks' => 'array',
        'supervisor_name' => 'string',
        'supervisor_title' => 'string',
        'supervisor_description' => 'string',
        'supervisor_avatar' => 'string',
        'title' => 'string',
        'slug' => 'string',
        'page_type' => 'string',
        'content' => 'string',
        'featured_image' => 'string',
        'custom_css' => 'string',
    ];

    // 公開設定時に自動的にpublished_atを設定
    protected static function booted()
    {
        static::saving(function ($page) {
            if ($page->is_published && !$page->published_at) {
                $page->published_at = now();
            } elseif (!$page->is_published) {
                $page->published_at = null;
            }
        });
        
        // Clear any potential caching when content is updated
        static::saved(function ($page) {
            // Force clear any view caching or OPcache
            if (function_exists('opcache_invalidate') && $page->wasChanged('content')) {
                // This ensures the rendered_content accessor reflects changes immediately
                $page->refresh();
            }
        });
    }

    // No special handling needed for supervisor fields - allow normal data storage

    // Scope for published pages
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    // Get page types
    public static function getPageTypes()
    {
        return [
            'window-cleaning-price-guide' => '窓ガラス清掃の相場・費用目安を解説',
            'window-cleaning-contractor-guide' => '窓ガラス清掃業者の選び方を解説',
            'exterior-wall-painting-price-guide' => '外壁塗装の料金相場・費用目安を解説',
            'exterior-wall-painting-contractor-guide' => '外壁塗装業者の選び方を解説',
        ];
    }

    // Get rendered content for display with custom HTML blocks
    public function getRenderedContentAttribute()
    {
        $content = $this->content ?? '';
        
        // Replace HTML shortcodes with custom HTML blocks
        $content = $this->replaceHtmlShortcodes($content);
        
        return $content;
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
                    // Wrap in page-specific container
                    return '<div class="custom-html-block page-content-' . $this->id . '">' . $html . '</div>';
                }
                // Return shortcode as-is if no matching block found
                return $matches[0];
            },
            $content
        );

        // Fallback: Use ContentShortcode for any remaining HTML shortcodes
        $content = \App\Support\ContentShortcode::render($content);

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

    // Get scoped custom CSS for this page
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
                    // Add page-specific scope to each selector with higher priority
                    $scopedSelectors[] = '.page-content.page-content-' . $this->id . ' ' . $sel;
                }
            }

            if (!empty($scopedSelectors)) {
                $scopedCss .= implode(', ', $scopedSelectors) . ' { ' . $properties . " }\n";
            }
        }

        return $scopedCss;
    }
}
