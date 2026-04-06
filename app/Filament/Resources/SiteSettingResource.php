<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteSettingResource\Pages;
use App\Filament\Resources\SiteSettingResource\RelationManagers;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static ?string $navigationIcon = null;
    
    protected static ?string $navigationLabel = null;
    
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('key')
                    ->label('設定キー')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->disabled(fn ($record) => $record && $record->exists),
                    
                Forms\Components\TextInput::make('label')
                    ->label('設定名')
                    ->required(),
                    
                Forms\Components\Textarea::make('description')
                    ->label('説明'),
                    
                Forms\Components\Select::make('type')
                    ->label('タイプ')
                    ->options([
                        'text' => 'テキスト',
                        'image' => '画像',
                        'textarea' => 'テキストエリア',
                        'html' => 'HTML（広告コード）',
                    ])
                    ->required()
                    ->live(),
                    
                Forms\Components\TextInput::make('value')
                    ->label('値')
                    ->visible(fn (Forms\Get $get) => $get('type') === 'text'),
                    
                Forms\Components\Textarea::make('value')
                    ->label('値')
                    ->visible(fn (Forms\Get $get) => $get('type') === 'textarea'),
                    
                Forms\Components\Textarea::make('value')
                    ->label('HTMLコード')
                    ->rows(8)
                    ->helperText('Google AdSense、Amazon Associates等の広告コードを貼り付けてください')
                    ->visible(fn (Forms\Get $get) => $get('type') === 'html'),
                    
                Forms\Components\FileUpload::make('value')
                    ->label('画像')
                    ->image()
                    ->disk('public')
                    ->directory('site-settings')
                    ->visibility('public')
                    ->storeFileNamesIn('original_filename')
                    ->maxSize(2048) // Reduce max size to 2MB
                    ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png', 'image/webp'])
                    ->imagePreviewHeight('250')
                    ->loadingIndicatorPosition('left')
                    ->panelAspectRatio('2:1')
                    ->panelLayout('integrated')
                    ->removeUploadedFileButtonPosition('right')
                    ->uploadButtonPosition('left')
                    ->uploadProgressIndicatorPosition('left')
                    ->moveFiles()
                    ->saveUploadedFileUsing(function ($file, $record) {
                        // Store the file and return the path
                        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                        $path = $file->storeAs('site-settings', $filename, 'public');
                        return $path;
                    })
                    ->visible(fn (Forms\Get $get) => $get('type') === 'image'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label('設定名')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('key')
                    ->label('キー')
                    ->searchable(),
                    
                Tables\Columns\BadgeColumn::make('type')
                    ->label('タイプ')
                    ->colors([
                        'primary' => 'text',
                        'success' => 'image',
                        'warning' => 'textarea',
                        'danger' => 'html',
                    ]),
                    
                Tables\Columns\ImageColumn::make('value')
                    ->label('画像プレビュー')
                    ->disk('public')
                    ->height(60)
                    ->width(80)
                    ->visible(fn ($record) => $record && $record->type === 'image'),
                    
                Tables\Columns\TextColumn::make('value')
                    ->label('値')
                    ->limit(50)
                    ->formatStateUsing(function ($state, $record) {
                        if ($record && $record->type === 'image' && $state) {
                            return basename($state);
                        }
                        return $state;
                    })
                    ->visible(fn ($record) => $record && $record->type !== 'image'),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('更新日')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('タイプ')
                    ->options([
                        'text' => 'テキスト',
                        'image' => '画像',
                        'textarea' => 'テキストエリア',
                        'html' => 'HTML（広告コード）',
                    ]),
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
            'index' => Pages\ListSiteSettings::route('/'),
            'create' => Pages\CreateSiteSetting::route('/create'),
            'edit' => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }
}
