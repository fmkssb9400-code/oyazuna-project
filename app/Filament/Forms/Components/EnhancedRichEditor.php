<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Actions\Action;
use Filament\Support\Concerns\HasExtraAttributes;

class EnhancedRichEditor extends RichEditor
{
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->toolbarButtons([
            'attachFiles',
            'blockquote',
            'bold',
            'bulletList',
            'codeBlock',
            'h2',
            'h3',
            'italic',
            'link',
            'orderedList',
            'redo',
            'strike',
            'underline',
            'undo',
        ]);

        $this->extraInputAttributes([
            'style' => 'min-height: 300px;'
        ]);

        $this->helperText('記事の本文を入力してください。');
    }
}