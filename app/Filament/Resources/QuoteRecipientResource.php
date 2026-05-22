<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuoteRecipientResource\Pages;
use App\Filament\Resources\QuoteRecipientResource\RelationManagers;
use App\Models\QuoteRecipient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuoteRecipientResource extends Resource
{
    protected static ?string $model = QuoteRecipient::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = '見積受信者';
    protected static ?string $modelLabel = '見積受信者';
    protected static ?string $pluralModelLabel = '見積受信者';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('案件詳細')
                    ->schema([
                        Forms\Components\Select::make('region')
                            ->label('地域')
                            ->options([
                                '東京都' => '東京都',
                                '神奈川県' => '神奈川県',
                                '埼玉県' => '埼玉県',
                                '千葉県' => '千葉県',
                                '大阪府' => '大阪府',
                                '愛知県' => '愛知県',
                                '兵庫県' => '兵庫県',
                                '福岡県' => '福岡県',
                            ])
                            ->required(),
                        Forms\Components\Select::make('building_type')
                            ->label('建物種別')
                            ->options([
                                'オフィス' => 'オフィス',
                                'マンション' => 'マンション',
                                '商業施設' => '商業施設',
                                '工場' => '工場',
                                '病院' => '病院',
                                '学校' => '学校',
                                'ホテル' => 'ホテル',
                                'その他' => 'その他',
                            ])
                            ->required(),
                        Forms\Components\Select::make('floor_range')
                            ->label('階数帯')
                            ->options([
                                '1〜5階' => '1〜5階',
                                '6〜10階' => '6〜10階',
                                '11〜20階' => '11〜20階',
                                '21階以上' => '21階以上',
                            ])
                            ->required(),
                        Forms\Components\Select::make('order_type')
                            ->label('発注形態')
                            ->options([
                                '管理会社経由' => '管理会社経由',
                                'オーナー直接' => 'オーナー直接',
                                '代理店経由' => '代理店経由',
                                '元請業者経由' => '元請業者経由',
                            ])
                            ->required(),
                        Forms\Components\Select::make('contract_type')
                            ->label('契約')
                            ->options([
                                '毎月定期' => '毎月定期',
                                '年1回定期' => '年1回定期',
                                '年2回定期' => '年2回定期',
                                '年3回定期' => '年3回定期',
                                '年4回定期' => '年4回定期',
                                '年5回定期' => '年5回定期',
                            ])
                            ->required(),
                    ])->columns(2),
                
                Forms\Components\Section::make('見積もりテーブル')
                    ->schema([
                        Forms\Components\Textarea::make('quote_items')
                            ->label('')
                            ->extraInputAttributes(['style' => 'display: none;'])
                            ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state) : $state)
                            ->dehydrateStateUsing(fn ($state) => is_string($state) ? json_decode($state, true) : $state)
                            ->columnSpanFull(),
                        Forms\Components\ViewField::make('quote_table_display')
                            ->view('filament.forms.components.quote-table')
                            ->label('')
                            ->columnSpanFull(),
                        Forms\Components\Hidden::make('total_amount')
                            ->dehydrateStateUsing(function ($get) {
                                $items = $get('quote_items');
                                if (is_string($items)) {
                                    $items = json_decode($items, true);
                                }
                                $items = $items ?: [];
                                $total = 0;
                                foreach ($items as $item) {
                                    $total += ($item['total_price'] ?? 0);
                                }
                                return $total;
                            }),
                    ]),
                
                Forms\Components\Section::make('備考欄')
                    ->schema([
                        Forms\Components\Textarea::make('additional_info')
                            ->label('備考欄')
                            ->placeholder('その他の情報やメモがあれば入力してください')
                            ->nullable()
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('region')
                    ->label('地域')
                    ->searchable(),
                Tables\Columns\TextColumn::make('building_type')
                    ->label('建物種別')
                    ->searchable(),
                Tables\Columns\TextColumn::make('delivery_status')
                    ->label('配信ステータス')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => '配信待ち',
                        'sent' => '配信済み',
                        'failed' => '配信失敗',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'sent' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('sent_at')
                    ->label('配信日時')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('作成日時')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('delivery_status')
                    ->label('配信ステータス')
                    ->options([
                        'pending' => '配信待ち',
                        'sent' => '配信済み',
                        'failed' => '配信失敗',
                    ]),
            ])
            ->actions([
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
            'index' => Pages\ListQuoteRecipients::route('/'),
            'create' => Pages\CreateQuoteRecipient::route('/create'),
            'edit' => Pages\EditQuoteRecipient::route('/{record}/edit'),
        ];
    }
}
