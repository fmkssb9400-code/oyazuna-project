<?php

namespace App\Services;

class ContentRenderer
{
    public function renderToHtml(array $content): string
    {
        if (!isset($content['content']) || !is_array($content['content'])) {
            return '';
        }

        $html = '';
        foreach ($content['content'] as $block) {
            $html .= $this->renderBlock($block);
        }

        return $html;
    }

    private function renderBlock(array $block): string
    {
        // 必須：最初に全ての変数を安全に定義（PHPエラー完全回避）
        $type = $block['type'] ?? '';
        $content = $block['content'] ?? [];
        $attrs = $block['attrs'] ?? [];

        switch ($type) {
            case 'paragraph':
                return $this->renderParagraph($content, $attrs);
            
            case 'heading':
                return $this->renderHeading($content, $attrs);
            
            case 'bulletList':
                return $this->renderBulletList($content);
            
            case 'orderedList':
                return $this->renderOrderedList($content);
            
            case 'listItem':
                return $this->renderListItem($content);
            
            case 'blockquote':
                return $this->renderBlockquote($content);
            
            case 'imageBlock':
                return $this->renderImageBlock($attrs);
            
            case 'image':
                // TiptapのImageノード対応
                return $this->renderImageBlock($attrs);
            
            case 'horizontalRule':
                return '<hr class="my-8 border-0 h-px bg-gray-300">';
            
            case 'text':
                // textは単独ブロックとして来ない想定が多いが、来ても落ちないように
                return $this->renderInlineText($block);
            
            default:
                // 未知typeのときは空文字を返して落ちないようにする
                return '';
        }
    }

    private function renderParagraph(array $content, array $attrs = []): string
    {
        // 安全性確保：空/欠損データでも落ちない
        if (empty($content) || !is_array($content)) {
            $content = [];
        }

        $textAlign = $attrs['textAlign'] ?? '';
        $classes = ['mb-4', 'last:mb-0', 'leading-relaxed'];
        
        if ($textAlign) {
            $classes[] = "text-{$textAlign}";
        }

        $html = '<p class="' . implode(' ', $classes) . '">';
        foreach ($content as $item) {
            if (is_array($item)) {
                $html .= $this->renderInline($item);
            }
        }
        $html .= '</p>';

        return $html;
    }

    private function renderHeading(array $content, array $attrs = []): string
    {
        // 安全性確保：空/欠損データでも落ちない
        if (empty($content) || !is_array($content)) {
            $content = [];
        }

        $level = $attrs['level'] ?? 2;
        $textAlign = $attrs['textAlign'] ?? '';
        
        // levelの範囲チェック
        if (!is_int($level) || $level < 1 || $level > 6) {
            $level = 2;
        }
        
        $classes = match($level) {
            2 => ['text-2xl', 'font-bold', 'mb-4', 'mt-8', 'first:mt-0', 'text-gray-900'],
            3 => ['text-xl', 'font-bold', 'mb-3', 'mt-6', 'first:mt-0', 'text-gray-900'],
            default => ['text-lg', 'font-bold', 'mb-2', 'mt-4', 'first:mt-0', 'text-gray-900'],
        };

        if ($textAlign) {
            $classes[] = "text-{$textAlign}";
        }

        $html = "<h{$level} class=\"" . implode(' ', $classes) . "\">";
        foreach ($content as $item) {
            if (is_array($item)) {
                $html .= $this->renderInline($item);
            }
        }
        $html .= "</h{$level}>";

        return $html;
    }

    private function renderBulletList(array $content): string
    {
        // 安全性確保：空/欠損データでも落ちない
        if (empty($content) || !is_array($content)) {
            return '<ul class="list-disc pl-6 mb-4 space-y-2"></ul>';
        }

        $html = '<ul class="list-disc pl-6 mb-4 space-y-2">';
        foreach ($content as $item) {
            if (is_array($item)) {
                $html .= $this->renderBlock($item);
            }
        }
        $html .= '</ul>';

        return $html;
    }

    private function renderOrderedList(array $content): string
    {
        // 安全性確保：空/欠損データでも落ちない
        if (empty($content) || !is_array($content)) {
            return '<ol class="list-decimal pl-6 mb-4 space-y-2"></ol>';
        }

        $html = '<ol class="list-decimal pl-6 mb-4 space-y-2">';
        foreach ($content as $item) {
            if (is_array($item)) {
                $html .= $this->renderBlock($item);
            }
        }
        $html .= '</ol>';

        return $html;
    }

    private function renderListItem(array $content): string
    {
        // 安全性確保：空/欠損データでも落ちない
        if (empty($content) || !is_array($content)) {
            return '<li class="leading-relaxed"></li>';
        }

        $html = '<li class="leading-relaxed">';
        foreach ($content as $item) {
            if (is_array($item)) {
                $html .= $this->renderBlock($item);
            }
        }
        $html .= '</li>';

        return $html;
    }

    private function renderBlockquote(array $content): string
    {
        // 安全性確保：空/欠損データでも落ちない
        if (empty($content) || !is_array($content)) {
            return '<blockquote class="border-l-4 border-gray-300 pl-4 py-2 mb-4 italic text-gray-700 bg-gray-50"></blockquote>';
        }

        $html = '<blockquote class="border-l-4 border-gray-300 pl-4 py-2 mb-4 italic text-gray-700 bg-gray-50">';
        foreach ($content as $item) {
            if (is_array($item)) {
                $html .= $this->renderBlock($item);
            }
        }
        $html .= '</blockquote>';

        return $html;
    }

    private function renderImageBlock(array $attrs): string
    {
        $src = $attrs['src'] ?? '';
        $alt = $attrs['alt'] ?? '';
        $caption = $attrs['caption'] ?? '';
        $size = $attrs['size'] ?? 'md';  // 新しいsizeアトリビュート対応
        
        // 下位互換性のための旧アトリビュート対応
        $alignment = $attrs['alignment'] ?? 'center';
        $width = $attrs['width'] ?? 'normal';
        $customWidth = $attrs['customWidth'] ?? null;

        if (!$src) {
            return '';
        }

        $figureClasses = ['block-image', "align-{$alignment}", "width-{$width}", "img-{$size}", 'mb-6'];
        
        $html = '<figure class="' . implode(' ', $figureClasses) . '">';
        
        $imgClasses = ['block', 'mx-auto', 'max-w-full', 'h-auto', 'rounded-lg', 'shadow-sm', "img-{$size}"];
        
        // Apply alignment classes to image
        if ($alignment === 'left') {
            $imgClasses = array_diff($imgClasses, ['mx-auto']);
            $imgClasses[] = 'ml-0';
        } elseif ($alignment === 'right') {
            $imgClasses = array_diff($imgClasses, ['mx-auto']);
            $imgClasses[] = 'mr-0';
        }

        $imgStyle = '';
        if ($customWidth) {
            $imgStyle = "style=\"width: {$customWidth}px\"";
        }

        $imgDataAttrs = "data-size=\"{$size}\"";

        $html .= "<img src=\"{$src}\" alt=\"{$alt}\" class=\"" . implode(' ', $imgClasses) . "\" {$imgDataAttrs} {$imgStyle}>";
        
        if ($caption) {
            $html .= '<figcaption class="mt-2 text-sm text-gray-600 text-center italic">' . 
                     htmlspecialchars($caption) . '</figcaption>';
        }
        
        $html .= '</figure>';

        return $html;
    }

    private function renderInline(array $item): string
    {
        // 安全性確保：空/欠損データでも落ちない
        if (!is_array($item)) {
            return '';
        }

        $type = $item['type'] ?? '';
        $marks = $item['marks'] ?? [];
        $text = $item['text'] ?? '';

        if ($type === 'text') {
            $html = htmlspecialchars($text);

            // marksが配列でない場合の安全性確保
            if (!is_array($marks)) {
                $marks = [];
            }

            foreach ($marks as $mark) {
                if (!is_array($mark)) {
                    continue;
                }
                
                $markType = $mark['type'] ?? '';
                switch ($markType) {
                    case 'bold':
                        $html = "<strong>{$html}</strong>";
                        break;
                    case 'italic':
                        $html = "<em>{$html}</em>";
                        break;
                    case 'link':
                        $attrs = $mark['attrs'] ?? [];
                        $href = $attrs['href'] ?? '#';
                        $target = $attrs['target'] ?? '';
                        $targetAttr = $target ? " target=\"{$target}\"" : '';
                        $html = "<a href=\"{$href}\" class=\"text-blue-600 hover:text-blue-800 underline\"{$targetAttr}>{$html}</a>";
                        break;
                }
            }

            return $html;
        }

        return '';
    }

    public function renderToPlainText(array $content): string
    {
        if (!isset($content['content']) || !is_array($content['content'])) {
            return '';
        }

        $text = '';
        foreach ($content['content'] as $block) {
            $text .= $this->extractTextFromBlock($block) . ' ';
        }

        return trim($text);
    }

    private function extractTextFromBlock(array $block): string
    {
        $content = $block['content'] ?? [];
        $text = '';

        foreach ($content as $item) {
            if (isset($item['type']) && $item['type'] === 'text') {
                $text .= $item['text'] ?? '';
            } elseif (isset($item['content'])) {
                $text .= $this->extractTextFromBlock($item) . ' ';
            }
        }

        return $text;
    }

    /**
     * textブロックが単独で来た場合の処理（安全版）
     * $block['text'] をHTMLエスケープして返す、marks対応
     */
    private function renderInlineText(array $block): string
    {
        $type = $block['type'] ?? '';
        $marks = $block['marks'] ?? [];
        $text = $block['text'] ?? '';

        if ($type !== 'text') {
            return '';
        }

        // HTMLエスケープ
        $html = htmlspecialchars($text);

        // marks（bold/italic/link）を適用
        foreach ($marks as $mark) {
            $markType = $mark['type'] ?? '';
            switch ($markType) {
                case 'bold':
                    $html = "<strong>{$html}</strong>";
                    break;
                case 'italic':
                    $html = "<em>{$html}</em>";
                    break;
                case 'link':
                    $href = $mark['attrs']['href'] ?? '#';
                    $target = $mark['attrs']['target'] ?? '';
                    $targetAttr = $target ? " target=\"{$target}\"" : '';
                    $html = "<a href=\"{$href}\" class=\"text-blue-600 hover:text-blue-800 underline\"{$targetAttr}>{$html}</a>";
                    break;
            }
        }

        return $html;
    }
}