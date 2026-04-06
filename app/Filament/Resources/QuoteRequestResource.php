<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuoteRequestResource\Pages;
use App\Filament\Resources\QuoteRequestResource\RelationManagers;
use App\Models\QuoteRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuoteRequestResource extends Resource
{
    protected static ?string $model = QuoteRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = '見積依頼';
    protected static ?string $modelLabel = '見積依頼';
    protected static ?string $pluralModelLabel = '見積依頼';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('public_id')
                    ->label('案件番号')
                    ->disabled(),
                Forms\Components\Select::make('status')
                    ->label('ステータス')
                    ->options([
                        'new' => '新規',
                        'sent' => '送信済み',
                        'done' => '完了',
                        'invalid' => '無効',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->label('氏名')
                    ->disabled(),
                Forms\Components\TextInput::make('email')
                    ->label('メールアドレス')
                    ->disabled(),
                Forms\Components\TextInput::make('phone')
                    ->label('電話番号')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('public_id')
                    ->label('案件番号')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('氏名')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('メールアドレス')
                    ->searchable(),
                Tables\Columns\TextColumn::make('prefecture.name')
                    ->label('都道府県'),
                Tables\Columns\TextColumn::make('floors')
                    ->label('階数'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('ステータス')
                    ->colors([
                        'primary' => 'new',
                        'success' => 'sent',
                        'warning' => 'done',
                        'danger' => 'invalid',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('作成日')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('ステータス')
                    ->options([
                        'new' => '新規',
                        'sent' => '送信済み',
                        'done' => '完了',
                        'invalid' => '無効',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListQuoteRequests::route('/'),
            'create' => Pages\CreateQuoteRequest::route('/create'),
            'edit' => Pages\EditQuoteRequest::route('/{record}/edit'),
        ];
    }
}
