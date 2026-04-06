<?php

namespace App\Filament\Components;

use Filament\Forms\Components\Field;

class BlockEditor extends Field
{
    protected string $view = 'filament.components.vue-block-editor';

    public function getUploadUrl(): string
    {
        return route('admin.editor.upload');
    }

    public function getDeleteUrl(): string
    {
        return route('admin.editor.delete');
    }

    public function getCsrfToken(): string
    {
        return csrf_token();
    }
}