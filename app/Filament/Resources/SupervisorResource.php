<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupervisorResource\Pages;
use App\Models\Supervisor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SupervisorResource extends Resource
{
    protected static ?string $model = Supervisor::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationLabel = '監修者';

    protected static ?string $modelLabel = '監修者';

    protected static ?string $pluralModelLabel = '監修者';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('監修者名')
                    ->required()
                    ->helperText('記事を監修した専門家の名前'),

                Forms\Components\TextInput::make('title')
                    ->label('肩書き')
                    ->helperText('専門家の肩書きや役職'),

                Forms\Components\Textarea::make('description')
                    ->label('紹介文')
                    ->rows(4)
                    ->columnSpanFull()
                    ->helperText('監修者の経歴や専門分野について'),

                Forms\Components\FileUpload::make('avatar')
                    ->label('アイコン画像')
                    ->image()
                    ->directory('supervisors')
                    ->disk('public')
                    ->maxSize(5120)
                    ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'image/webp'])
                    ->reorderable(false)
                    ->deletable(true)
                    ->previewable(true),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label('')
                    ->disk('public')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('監修者名')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('肩書き'),

                Tables\Columns\TextColumn::make('articles_count')
                    ->label('紐づく記事数')
                    ->counts('articles'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('更新日時')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListSupervisors::route('/'),
            'create' => Pages\CreateSupervisor::route('/create'),
            'edit' => Pages\EditSupervisor::route('/{record}/edit'),
        ];
    }
}
