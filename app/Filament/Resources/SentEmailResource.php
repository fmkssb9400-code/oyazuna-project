<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SentEmailResource\Pages;
use App\Models\SentEmail;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SentEmailResource extends Resource
{
    protected static ?string $model = SentEmail::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';
    // サイドバーの「メール」項目はEmailSettingsResource側で出している
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationLabel = 'メール履歴';
    protected static ?string $modelLabel = 'メール';
    protected static ?string $pluralModelLabel = 'メール履歴';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make()
                ->columns(2)
                ->schema([
                    Infolists\Components\TextEntry::make('type')
                        ->label('種別')
                        ->badge()
                        ->formatStateUsing(fn (string $state) => SentEmail::TYPE_LABELS[$state] ?? $state)
                        ->color(fn (string $state) => match ($state) {
                            SentEmail::TYPE_CONTACT => 'info',
                            SentEmail::TYPE_QUOTE => 'success',
                            default => 'gray',
                        }),
                    Infolists\Components\TextEntry::make('created_at')
                        ->label('送信日時')
                        ->dateTime('Y/m/d H:i:s'),
                    Infolists\Components\TextEntry::make('from_address')
                        ->label('送信元')
                        ->formatStateUsing(fn ($state, SentEmail $record) => $record->from_name
                            ? "{$record->from_name} <{$state}>"
                            : $state),
                    Infolists\Components\TextEntry::make('to_address')
                        ->label('宛先'),
                    Infolists\Components\TextEntry::make('subject')
                        ->label('件名')
                        ->columnSpanFull(),
                    Infolists\Components\TextEntry::make('body')
                        ->label('本文')
                        ->columnSpanFull()
                        ->extraAttributes(['style' => 'white-space: pre-wrap;']),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('送信日時')
                    ->dateTime('Y/m/d H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('種別')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => SentEmail::TYPE_LABELS[$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        SentEmail::TYPE_CONTACT => 'info',
                        SentEmail::TYPE_QUOTE => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('subject')
                    ->label('件名')
                    ->searchable()
                    ->limit(60),
                Tables\Columns\TextColumn::make('to_address')
                    ->label('宛先')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('種別')
                    ->options(SentEmail::TYPE_LABELS),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('開く'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSentEmails::route('/'),
            'view' => Pages\ViewSentEmail::route('/{record}'),
        ];
    }
}
