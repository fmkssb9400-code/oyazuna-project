<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use Filament\Resources\Pages\CreateRecord;
use App\Services\ContentRenderer;
use Illuminate\Contracts\View\View;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // HTML正規化で画像サイズを確実に反映
        if (!empty($data['content'])) {
            $data['content'] = $this->normalizeImageSizesInHtml($data['content']);
        }

        return $data;
    }

    /**
     * HTML内の全画像にサイズ情報を確実に反映（正規表現ベース）
     */
    private function normalizeImageSizesInHtml(string $html): string
    {
        \Log::info('🔧 正規表現HTML正規化開始（新規）', ['html_length' => strlen($html)]);
        
        if (empty($html)) {
            return $html;
        }

        $modifiedCount = 0;
        $processedHtml = $html;

        // <img>タグを正規表現で検索・置換
        $processedHtml = preg_replace_callback(
            '/<img([^>]*)>/i',
            function ($matches) use (&$modifiedCount) {
                $imgTag = $matches[0];
                $attributes = $matches[1];
                
                // 現在のサイズを抽出
                $size = $this->extractSizeFromImgAttributes($attributes, $imgTag);
                
                // サイズ情報をimgタグに適用
                $newImgTag = $this->applySizeToImgTag($imgTag, $attributes, $size);
                
                $modifiedCount++;
                \Log::info('📏 画像サイズ適用（正規表現・新規）', [
                    'size' => $size,
                    'src_match' => preg_match('/src="([^"]*)"/', $attributes, $srcMatches) ? substr($srcMatches[1], -30) : 'unknown'
                ]);
                
                return $newImgTag;
            },
            $processedHtml
        );

        \Log::info('✅ 正規表現HTML正規化完了（新規）', [
            'modified_images' => $modifiedCount,
            'output_length' => strlen($processedHtml)
        ]);

        return $processedHtml;
    }

    /**
     * imgタグの属性とコンテキストからサイズを抽出
     */
    private function extractSizeFromImgAttributes(string $attributes, string $imgTag): string
    {
        // 1. data-size属性から抽出
        if (preg_match('/data-size="(sm|md|lg)"/', $attributes, $matches)) {
            return $matches[1];
        }
        
        // 2. class属性から抽出
        if (preg_match('/class="([^"]*)"/', $attributes, $matches)) {
            $classes = explode(' ', $matches[1]);
            foreach ($classes as $class) {
                if (preg_match('/^img-(sm|md|lg)$/', trim($class), $sizeMatches)) {
                    return $sizeMatches[1];
                }
            }
        }
        
        // 3. デフォルト：中サイズ
        return 'md';
    }

    /**
     * imgタグにサイズ情報を適用（正規表現ベース）
     */
    private function applySizeToImgTag(string $imgTag, string $attributes, string $size): string
    {
        $sizeStyles = [
            'sm' => 'max-width:320px;width:100%;height:auto;',
            'md' => 'max-width:600px;width:100%;height:auto;',
            'lg' => 'max-width:100%;width:100%;height:auto;'
        ];

        $newAttributes = $attributes;

        // 1. data-size属性を設定/更新
        if (preg_match('/data-size="[^"]*"/', $newAttributes)) {
            $newAttributes = preg_replace('/data-size="[^"]*"/', 'data-size="' . $size . '"', $newAttributes);
        } else {
            $newAttributes .= ' data-size="' . $size . '"';
        }

        // 2. class属性を設定/更新
        if (preg_match('/class="([^"]*)"/', $newAttributes, $matches)) {
            $classes = explode(' ', $matches[1]);
            // 既存のimg-*クラスを削除
            $classes = array_filter($classes, function($class) {
                return !preg_match('/^img-(sm|md|lg)$/', trim($class));
            });
            // 新しいクラスを追加
            $classes[] = "img-{$size}";
            $newClass = implode(' ', array_filter($classes));
            $newAttributes = preg_replace('/class="[^"]*"/', 'class="' . $newClass . '"', $newAttributes);
        } else {
            $newAttributes .= ' class="img-' . $size . '"';
        }

        // 3. style属性を設定/更新
        $newStyle = $sizeStyles[$size];
        if (preg_match('/style="([^"]*)"/', $newAttributes, $matches)) {
            $existingStyle = $matches[1];
            // 既存のサイズ関連スタイルを削除
            $existingStyle = preg_replace('/\s*(max-)?width\s*:[^;]+;?/i', '', $existingStyle);
            $existingStyle = preg_replace('/\s*height\s*:[^;]+;?/i', '', $existingStyle);
            $existingStyle = trim($existingStyle, '; ');
            
            $finalStyle = $existingStyle ? $existingStyle . ';' . $newStyle : $newStyle;
            $newAttributes = preg_replace('/style="[^"]*"/', 'style="' . $finalStyle . '"', $newAttributes);
        } else {
            $newAttributes .= ' style="' . $newStyle . '"';
        }

        return '<img' . $newAttributes . '>';
    }
}