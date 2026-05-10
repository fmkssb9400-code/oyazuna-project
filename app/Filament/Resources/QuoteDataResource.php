<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuoteDataResource\Pages;
use App\Filament\Resources\QuoteDataResource\RelationManagers;
use App\Models\QuoteSubmission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuoteDataResource extends Resource
{
    protected static ?string $model = QuoteSubmission::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = '見積書投稿';
    protected static ?string $modelLabel = '見積書投稿';
    protected static ?string $pluralModelLabel = '見積書投稿';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('work_type')
                    ->label('作業内容')
                    ->options(QuoteSubmission::WORK_TYPES)
                    ->required(),
                Forms\Components\TextInput::make('prefecture')
                    ->label('都道府県')
                    ->required(),
                Forms\Components\Textarea::make('comment')
                    ->label('コメント')
                    ->required()
                    ->rows(3),
                Forms\Components\TextInput::make('building_floors')
                    ->label('建物階数')
                    ->numeric(),
                Forms\Components\Select::make('order_status')
                    ->label('依頼状況')
                    ->options(QuoteSubmission::ORDER_STATUSES),
                Forms\Components\DatePicker::make('quote_date')
                    ->label('見積もり時期'),
                Forms\Components\Select::make('status')
                    ->label('処理ステータス')
                    ->options(QuoteSubmission::STATUSES)
                    ->required(),
                Forms\Components\Textarea::make('admin_notes')
                    ->label('管理者メモ')
                    ->rows(3),
                Forms\Components\Section::make('見積書画像')
                    ->schema([
                        Forms\Components\Placeholder::make('images_display')
                            ->label('')
                            ->content(function (?QuoteSubmission $record): \Illuminate\View\View {
                                return view('filament.components.quote-images', ['images' => $record?->images ?? []]);
                            }),
                    ])
                    ->collapsible()
                    ->hiddenOn('create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('work_type')
                    ->label('作業内容')
                    ->formatStateUsing(fn (string $state): string => QuoteSubmission::WORK_TYPES[$state] ?? $state)
                    ->searchable(),
                Tables\Columns\TextColumn::make('prefecture')
                    ->label('都道府県')
                    ->searchable(),
                Tables\Columns\TextColumn::make('comment')
                    ->label('コメント')
                    ->limit(50)
                    ->tooltip(function (QuoteSubmission $record): string {
                        return $record->comment;
                    }),
                Tables\Columns\TextColumn::make('images')
                    ->label('画像数')
                    ->formatStateUsing(function ($state): string {
                        return is_array($state) ? count($state) . '枚' : '0枚';
                    }),
                Tables\Columns\TextColumn::make('building_floors')
                    ->label('建物階数'),
                Tables\Columns\TextColumn::make('order_status')
                    ->label('依頼状況')
                    ->formatStateUsing(fn (?string $state): string => $state ? (QuoteSubmission::ORDER_STATUSES[$state] ?? $state) : '未回答')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'yes' => 'success',
                        'no' => 'danger', 
                        'considering' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('処理ステータス')
                    ->formatStateUsing(fn (string $state): string => QuoteSubmission::STATUSES[$state] ?? $state)
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'processing' => 'info',
                        'completed' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('投稿日時')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('work_type')
                    ->label('作業内容')
                    ->options(QuoteSubmission::WORK_TYPES),
                Tables\Filters\SelectFilter::make('order_status')
                    ->label('依頼状況')
                    ->options(QuoteSubmission::ORDER_STATUSES),
                Tables\Filters\SelectFilter::make('status')
                    ->label('処理ステータス')
                    ->options(QuoteSubmission::STATUSES),
                Tables\Filters\Filter::make('has_images')
                    ->label('画像あり')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('images')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->label('管理')
                    ->icon('heroicon-o-cog-6-tooth'),
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
            'index' => Pages\ListQuoteData::route('/'),
            'view' => Pages\ViewQuoteData::route('/{record}'),
            'edit' => Pages\EditQuoteData::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
