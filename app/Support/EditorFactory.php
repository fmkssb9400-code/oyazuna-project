<?php

namespace App\Support;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use Filament\Forms;
use Filament\Support\RawJs;

class EditorFactory
{
    public static function createContentEditor(string $name = 'content'): TinyEditor
    {
        return TinyEditor::make($name)
            ->profile('default')
            ->label('本文')
            ->required()
            ->columnSpanFull()
            ->minHeight(500)
            ->fileAttachmentsDirectory('articles');
    }

    public static function getCheckPointInsertAction(): Forms\Components\Actions\Action
    {
        return Forms\Components\Actions\Action::make('insertCheckPoint')
            ->label('チェックポイント挿入')
            ->icon('heroicon-o-check-circle')
            ->color('success')
            ->form([
                Forms\Components\TagsInput::make('check_items')
                    ->label('チェック項目')
                    ->required()
                    ->placeholder('項目を入力してEnterキーで追加')
                    ->helperText('各項目を入力してEnterキーで追加してください'),
            ])
            ->action(function (array $data, $livewire) {
                $items = $data['check_items'] ?? [];
                
                if (empty($items)) {
                    return;
                }
                
                $listItems = '';
                foreach ($items as $item) {
                    $listItems .= '<li>' . htmlspecialchars($item) . '</li>' . "\n";
                }
                
                $checkPointHtml = '<ul class="cp_point" title="チェックポイント！">' . "\n" . $listItems . '</ul>';
                
                // Get current content and append check point
                $currentContent = $livewire->data['content'] ?? '';
                $livewire->data['content'] = $currentContent . "\n" . $checkPointHtml;
                
                // Show notification
                \Filament\Notifications\Notification::make()
                    ->title('チェックポイントを挿入しました')
                    ->success()
                    ->send();
            });
    }

    public static function getButtonInsertAction(): Forms\Components\Actions\Action
    {
        return Forms\Components\Actions\Action::make('insertButton')
            ->label('ボタン挿入')
            ->icon('heroicon-o-cursor-arrow-rays')
            ->color('primary')
            ->form([
                Forms\Components\TextInput::make('button_text')
                    ->label('ボタン文言')
                    ->required()
                    ->placeholder('例: こちらをクリック'),
                    
                Forms\Components\TextInput::make('button_url')
                    ->label('URL')
                    ->required()
                    ->url()
                    ->placeholder('例: https://example.com'),
                    
                Forms\Components\Select::make('button_color')
                    ->label('ボタン色')
                    ->options([
                        'orange' => 'オレンジ',
                        'blue' => '青'
                    ])
                    ->required()
                    ->default('blue'),
            ])
            ->action(function (array $data, $livewire) {
                $text = $data['button_text'];
                $url = $data['button_url'];
                $color = $data['button_color'];
                
                $buttonClass = $color === 'orange' 
                    ? 'inline-block px-4 py-2 rounded-lg font-bold text-white bg-orange-500 hover:bg-orange-600'
                    : 'inline-block px-4 py-2 rounded-lg font-bold text-white bg-blue-600 hover:bg-blue-700';
                
                $buttonHtml = '<p><a href="' . $url . '" class="' . $buttonClass . '" target="_blank" rel="noopener">' . $text . '</a></p>';
                
                // Get current content and append button
                $currentContent = $livewire->data['content'] ?? '';
                $livewire->data['content'] = $currentContent . "\n" . $buttonHtml;
                
                // Show notification
                \Filament\Notifications\Notification::make()
                    ->title('ボタンを挿入しました')
                    ->success()
                    ->send();
            });
    }


}