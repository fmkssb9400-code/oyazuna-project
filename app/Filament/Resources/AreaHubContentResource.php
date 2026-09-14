<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AreaHubContentResource\Pages;
use App\Filament\Forms\Components\ContentEditor;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\HubController;
use App\Models\AreaHubContent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class AreaHubContentResource extends Resource
{
    protected static ?string $model = AreaHubContent::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationLabel = 'ハブページコンテンツ';

    protected static ?string $modelLabel = 'ハブページコンテンツ';

    protected static ?string $pluralModelLabel = 'ハブページコンテンツ';

    /**
     * 都道府県slug => 表示名（例: 'osaka' => '大阪府'）
     */
    private static function areaOptions(): array
    {
        return collect((new AreaController())->pages())
            ->mapWithKeys(fn ($config, $slug) => [$slug => $config['prefecture'] ?? $config['label'] ?? $slug])
            ->toArray();
    }

    /**
     * カテゴリ型ハブのslug => 表示名（'area/{都道府県}/{ハブ}'ページが存在するもののみ）
     */
    private static function hubOptions(): array
    {
        return collect((new HubController())->pages())
            ->filter(fn ($config) => ($config['type'] ?? null) === 'category')
            ->mapWithKeys(fn ($config, $slug) => [$slug => $config['label'] ?? $slug])
            ->toArray();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('対象ページ')
                    ->description('どの都道府県×ジャンルのハブページ（/area/{都道府県}/{ジャンル}）に表示するコンテンツかを選択します。')
                    ->schema([
                        Forms\Components\Select::make('area_slug')
                            ->label('都道府県')
                            ->options(self::areaOptions())
                            ->required()
                            ->searchable(),

                        Forms\Components\Select::make('hub_slug')
                            ->label('ジャンル')
                            ->options(self::hubOptions())
                            ->required()
                            ->searchable(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('SEO設定')
                    ->description('未入力の場合は自動生成された汎用の文言が使われます。')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('ページタイトル')
                            ->maxLength(255)
                            ->helperText('例: 大阪府の高所窓ガラス清掃業者一覧｜梅田・中之島エリア対応で見積り無料'),

                        Forms\Components\Textarea::make('meta_description')
                            ->label('meta description')
                            ->rows(2)
                            ->maxLength(255)
                            ->helperText('120〜150字程度が目安です'),
                    ]),

                Forms\Components\Section::make('本文コンテンツ')
                    ->description('業者一覧テーブルの下に表示される本文です。見出し・太字・表・色付きボックスなどは、見たままの状態で直接クリックして編集できます。')
                    ->schema([
                        ContentEditor::make('content'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('area_slug')
                    ->label('都道府県')
                    ->formatStateUsing(fn (string $state): string => self::areaOptions()[$state] ?? $state)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('hub_slug')
                    ->label('ジャンル')
                    ->formatStateUsing(fn (string $state): string => self::hubOptions()[$state] ?? $state)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('ページタイトル')
                    ->limit(40)
                    ->toggleable(),

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
            ])
            ->defaultSort('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAreaHubContents::route('/'),
            'create' => Pages\CreateAreaHubContent::route('/create'),
            'edit' => Pages\EditAreaHubContent::route('/{record}/edit'),
        ];
    }
}
