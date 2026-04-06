<?php

namespace App\Filament\Forms\Components;

use App\Support\EditorFactory;
use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use Filament\Forms;

class ContentEditor
{
    public static function make(string $name = 'content'): TinyEditor
    {
        return EditorFactory::createContentEditor($name);
    }

    public static function getButtonInsertAction(): Forms\Components\Actions\Action
    {
        return EditorFactory::getButtonInsertAction();
    }

    public static function getCheckPointInsertAction(): Forms\Components\Actions\Action
    {
        return EditorFactory::getCheckPointInsertAction();
    }

}