<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnerInquiryResource\Pages;
use App\Models\PartnerInquiry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PartnerInquiryResource extends Resource
{
    protected static ?string $model = PartnerInquiry::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationLabel = '提携相談';

    protected static ?string $modelLabel = '提携相談';

    protected static ?string $pluralModelLabel = '提携相談';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('company_name')
                    ->label('会社名')
                    ->disabled(),

                Forms\Components\TextInput::make('contact_name')
                    ->label('ご担当者名')
                    ->disabled(),

                Forms\Components\TextInput::make('email')
                    ->label('メールアドレス')
                    ->disabled(),

                Forms\Components\TextInput::make('phone')
                    ->label('電話番号')
                    ->disabled(),

                Forms\Components\Textarea::make('message')
                    ->label('ご相談内容')
                    ->rows(6)
                    ->columnSpanFull()
                    ->disabled(),

                Forms\Components\TextInput::make('ip_address')
                    ->label('IPアドレス')
                    ->disabled(),

                Forms\Components\TextInput::make('created_at')
                    ->label('送信日時')
                    ->disabled(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')
                    ->label('会社名')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('contact_name')
                    ->label('ご担当者名')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('メールアドレス')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('電話番号'),

                Tables\Columns\TextColumn::make('message')
                    ->label('ご相談内容')
                    ->limit(50)
                    ->tooltip(fn (PartnerInquiry $record) => $record->message),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('送信日時')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePartnerInquiries::route('/'),
        ];
    }
}
