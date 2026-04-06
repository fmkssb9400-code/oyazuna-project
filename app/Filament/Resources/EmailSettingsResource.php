<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmailSettingsResource\Pages;
use App\Filament\Resources\EmailSettingsResource\RelationManagers;
use App\Models\EmailSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmailSettingsResource extends Resource
{
    protected static ?string $model = EmailSettings::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationLabel = 'メール設定';
    protected static ?string $modelLabel = 'メール設定';
    protected static ?string $pluralModelLabel = 'メール設定';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('key')
                    ->label('設定キー')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('label')
                    ->label('表示名')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->label('タイプ')
                    ->options([
                        'text' => 'テキスト',
                        'email' => 'メールアドレス',
                        'textarea' => 'テキストエリア',
                        'number' => '数値',
                    ])
                    ->default('text')
                    ->required()
                    ->reactive(),
                Forms\Components\TextInput::make('value')
                    ->label('設定値')
                    ->visible(fn (Forms\Get $get) => in_array($get('type'), ['text', 'email', 'number']))
                    ->email(fn (Forms\Get $get) => $get('type') === 'email')
                    ->numeric(fn (Forms\Get $get) => $get('type') === 'number'),
                Forms\Components\Textarea::make('value')
                    ->label('設定値')
                    ->visible(fn (Forms\Get $get) => $get('type') === 'textarea')
                    ->rows(3),
                Forms\Components\Textarea::make('description')
                    ->label('説明')
                    ->rows(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label('設定名')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('key')
                    ->label('キー')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('タイプ')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'email' => 'success',
                        'textarea' => 'info',
                        'number' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('value')
                    ->label('設定値')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    }),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('更新日時')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmailSettings::route('/'),
            'create' => Pages\CreateEmailSettings::route('/create'),
            'edit' => Pages\EditEmailSettings::route('/{record}/edit'),
        ];
    }
}
