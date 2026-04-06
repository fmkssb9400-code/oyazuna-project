<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Services\ContentRenderer;
use Illuminate\Contracts\View\View;

class EditArticle extends EditRecord
{
    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFooterWidgets(): array
    {
        return [];
    }


    protected function afterSave(): void
    {
        \Log::info('CONTENT_AFTER_SAVE', [
            'db' => $this->record->content
        ]);
    }
    
    /**
     * IME安全な保存処理：wire:ignoreされたエディタからの同期
     */
    // protected function beforeSave(): void
    // {
    //     // JavaScriptから送信された最新のコンテンツを取得
    //     $latestContent = session()->pull('ime_safe_content');
    //     if ($latestContent !== null) {
    //         $this->data['content'] = $latestContent;
    //         \Log::info('IME_SAFE_CONTENT_SYNCED', ['content_length' => strlen($latestContent)]);
    //     }
    // }

    /**
     * 保存前にcontentのHTMLを正規化し、画像サイズを確実に反映
     */
    // protected function mutateFormDataBeforeSave(array $data): array
    // {
    //     \Log::info('SIZE_NORMALIZER_EXECUTED');
        
    //     \Log::info('CONTENT_BEFORE_SAVE', ['content' => $data['content']]);
        
    //     // TiptapEditorは配列またはHTMLとして来る可能性があるため型チェック
    //     if (!empty($data['content']) && is_string($data['content'])) {
    //         $data['content'] = $this->normalizeImageSizesInHtml($data['content']);
    //         \Log::info('CONTENT_AFTER_NORMALIZE', ['content' => $data['content']]);
    //     } elseif (is_array($data['content'])) {
    //         \Log::info('CONTENT_IS_ARRAY_SKIPPING_NORMALIZATION', ['content_type' => gettype($data['content'])]);
    //     }
        
    //     return $data;
    // }

    /**
     * HTML内の全画像にサイズ情報を確実に反映（正規表現ベース）
     */
    private function normalizeImageSizesInHtml(string $html): string
    {
        \Log::info('🔧 正規表現HTML正規化開始', ['html_length' => strlen($html)]);
        
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
                \Log::info('📏 画像サイズ適用（正規表現）', [
                    'size' => $size,
                    'src_match' => preg_match('/src="([^"]*)"/', $attributes, $srcMatches) ? substr($srcMatches[1], -30) : 'unknown'
                ]);
                
                return $newImgTag;
            },
            $processedHtml
        );

        \Log::info('✅ 正規表現HTML正規化完了', [
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
        
        // 3. 親のfigureのdata-trix-attributesから抽出（前後のコンテキスト必要）
        // この方法では難しいため、admin-scripts.blade.phpからの情報に依存
        
        // 4. デフォルト：中サイズ
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