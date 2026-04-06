<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\CompanyResource\RelationManagers;
use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Forms\Components\ContentEditor;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    
    protected static ?string $navigationLabel = '業者管理';
    
    protected static ?string $modelLabel = '業者';
    
    protected static ?string $pluralModelLabel = '業者';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('基本情報')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('会社名')
                            ->required(),
                        Forms\Components\TextInput::make('slug')
                            ->label('スラッグ（URL用）')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\Textarea::make('description')
                            ->label('会社紹介')
                            ->rows(4)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('article_content')
                            ->label('記事・詳細内容')
                            ->rows(6)
                            ->columnSpanFull()
                            ->helperText('会社の詳細な説明や記事内容'),
                        Forms\Components\TextInput::make('website_url')
                            ->label('公式サイトURL')
                            ->url()
                            ->placeholder('https://example.com'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('サービス情報')
                    ->schema([
                        Forms\Components\CheckboxList::make('area_regions')
                            ->label('対応地域（検索用）')
                            ->options([
                                'nationwide' => '全国',
                                'hokkaido_tohoku' => '北海道・東北',
                                'hokuriku_koshinetsu' => '北陸・甲信越',
                                'kanto' => '関東',
                                'tokai' => '東海',
                                'kansai' => '関西',
                                'chugoku' => '中国',
                                'shikoku' => '四国',
                                'kyushu_okinawa' => '九州・沖縄',
                            ])
                            ->columns(3)
                            ->columnSpanFull()
                            ->helperText('検索で表示される地域を選択してください。例：全国を選択すると、大阪や京都で検索してもこの会社が表示されます。この設定は検索のみに使用され、カード表示には影響しません。'),
                        
                        Forms\Components\TagsInput::make('areas')
                            ->label('対応エリア（カード表示用）')
                            ->placeholder('東京都, 埼玉県, 神奈川県...')
                            ->separator(',')
                            ->columnSpanFull()
                            ->helperText('カードに表示される都道府県を入力してください。47都道府県すべて入力すると「全国」と表示されます。'),
                        Forms\Components\CheckboxList::make('service_categories')
                            ->label('提供サービス')
                            ->options([
                                'window' => '窓ガラス清掃',
                                'exterior' => '外壁清掃',
                                'inspection' => '外壁調査',
                                'repair' => '外壁補修',
                                'painting' => '外壁塗装',
                                'bird_control' => '鳥害対策',
                                'sign' => '看板作業',
                                'leak_inspection' => '雨漏り調査'
                            ])
                            ->columns(3)
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('rope_support')
                            ->label('ロープアクセス対応')
                            ->default(false)
                            ->live(),
                        Forms\Components\Toggle::make('branco_supported')
                            ->label('ブランコ対応')
                            ->default(false)
                            ->live(),
                        Forms\Components\Toggle::make('aerial_platform_supported')
                            ->label('高所作業車')
                            ->default(false)
                            ->live(),
                        Forms\Components\Toggle::make('gondola_supported')
                            ->label('ゴンドラ対応')
                            ->default(false)
                            ->live(),
                        Forms\Components\Toggle::make('emergency_supported')
                            ->label('即日・緊急対応')
                            ->default(false)
                            ->live(),
                        Forms\Components\Toggle::make('free_estimate')
                            ->label('見積もり無料')
                            ->default(false)
                            ->live(),
                        Forms\Components\Toggle::make('regular_work')
                            ->label('定期作業')
                            ->default(false)
                            ->live(),
                        Forms\Components\Toggle::make('reviews_reputation')
                            ->label('口コミ評判')
                            ->default(false)
                            ->live(),
                        Forms\Components\Toggle::make('case_studies')
                            ->label('事例')
                            ->default(false)
                            ->live(),
                        Forms\Components\Toggle::make('liability_insurance')
                            ->label('損害賠償保険加入')
                            ->default(false)
                            ->live(),
                        Forms\Components\Toggle::make('workers_insurance')
                            ->label('労災保険加入')
                            ->default(false)
                            ->live(),
                        Forms\Components\Toggle::make('certified_staff')
                            ->label('有資格者在籍')
                            ->default(false)
                            ->live(),
                        Forms\Components\Toggle::make('corporate_support')
                            ->label('法人対応')
                            ->default(false)
                            ->live(),
                        Forms\Components\Toggle::make('weekend_support')
                            ->label('土日対応')
                            ->default(false)
                            ->live(),
                        Forms\Components\Toggle::make('night_support')
                            ->label('夜間対応')
                            ->default(false)
                            ->live(),
                        Forms\Components\Toggle::make('online_consultation')
                            ->label('オンライン相談')
                            ->default(false)
                            ->live(),
                        Forms\Components\Toggle::make('email_support')
                            ->label('メール対応')
                            ->default(false)
                            ->live(),
                        Forms\Components\Toggle::make('line_support')
                            ->label('LINE対応')
                            ->default(false)
                            ->live(),
                        Forms\Components\TextInput::make('official_url')
                            ->label('公式サイトURL')
                            ->url()
                            ->placeholder('https://example.com')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),


                Forms\Components\Section::make('会社ロゴ・画像')
                    ->schema([
                        Forms\Components\FileUpload::make('logo_path')
                            ->label('会社ロゴ（カード用）')
                            ->image()
                            ->disk('public')
                            ->directory('company-logos'),
                        Forms\Components\FileUpload::make('ranking_logo_path')
                            ->label('ランキング用ロゴ（人気ランキングセクション用）')
                            ->image()
                            ->disk('public')
                            ->directory('company-ranking-logos'),
                    ]),

                Forms\Components\Section::make('安全・実績情報')
                    ->schema([
                        Forms\Components\TagsInput::make('safety_items')
                            ->label('安全情報')
                            ->placeholder('賠償責任保険加入')
                            ->separator(',')
                            ->columnSpanFull()
                            ->helperText('Enterキーまたはカンマで区切って追加してください'),
                        Forms\Components\TextInput::make('achievements_summary')
                            ->label('実績要約')
                            ->placeholder('大型ビル施工実績多数')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('おすすめ設定')
                    ->schema([
                        Forms\Components\Toggle::make('is_featured')
                            ->label('ホームページのおすすめ欄に表示')
                            ->default(false)
                            ->live(),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('表示順')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->maxValue(10)
                            ->step(1)
                            ->helperText('数値が小さいほど上に表示されます（0-10）'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Forms\Components\Section::make('評価・スコア設定')
                    ->description('タブ切り替え用の評価を設定してください（5つ星評価）')
                    ->schema([
                        Forms\Components\Select::make('recommend_score')
                            ->label('おすすめスコア（★1-5）')
                            ->options([
                                0 => '0 - 未設定',
                                1 => '★ 1',
                                2 => '★★ 2',
                                3 => '★★★ 3',
                                4 => '★★★★ 4',
                                5 => '★★★★★ 5',
                            ])
                            ->default(0)
                            ->helperText('おすすめタブでの表示順に使用されます'),
                        Forms\Components\Select::make('safety_score')
                            ->label('安全スコア（★1-5）')
                            ->options([
                                0 => '0 - 未設定',
                                1 => '★ 1',
                                2 => '★★ 2',
                                3 => '★★★ 3',
                                4 => '★★★★ 4',
                                5 => '★★★★★ 5',
                            ])
                            ->default(0)
                            ->helperText('安全タブでの表示順に使用されます'),
                        Forms\Components\Select::make('performance_score')
                            ->label('実績スコア（★1-5）')
                            ->options([
                                0 => '0 - 未設定',
                                1 => '★ 1',
                                2 => '★★ 2',
                                3 => '★★★ 3',
                                4 => '★★★★ 4',
                                5 => '★★★★★ 5',
                            ])
                            ->default(0)
                            ->helperText('実績タブでの表示順に使用されます'),
                    ])
                    ->columns(3)
                    ->collapsible(),

                    
                // Temporarily commented out to fix save issue - Article management section
                /*
                Forms\Components\Section::make('記事管理')
                    ->schema([
                        Forms\Components\Repeater::make('articles')
                            ->relationship('articles')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('記事タイトル')
                                    ->required()
                                    ->afterStateUpdated(fn (string $context, $state, callable $set) => $context === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null),
                                
                                Forms\Components\TextInput::make('slug')
                                    ->label('記事スラッグ')
                                    ->required()
                                    ->unique(\App\Models\Article::class, 'slug', ignoreRecord: true)
                                    ->alphaDash()
                                    ->helperText('URLに使用されます（英数字とハイフンのみ）'),
                                
                                Forms\Components\TagsInput::make('tags')
                                    ->label('タグ')
                                    ->helperText('記事のタグを入力してください（Enter区切りで複数追加可能）')
                                    ->separator(','),
                                
                                Forms\Components\Select::make('guide_type')
                                    ->label('ガイドページタイプ')
                                    ->options([
                                        'window-cleaning-price-guide' => '窓ガラス清掃の料金相場・費用目安を解説',
                                        'wall-inspection-price-guide' => '外壁調査の料金相場・費用目安を解説', 
                                        'wall-painting-price-guide' => '外壁塗装の料金相場・費用目安を解説',
                                        'company-selection-guide' => '高所ロープ作業会社の選び方',
                                    ])
                                    ->placeholder('通常の記事（ガイドページではない）')
                                    ->helperText('ガイドページとして使用する場合は選択してください'),
                                
                                ContentEditor::make('content')
                                    ->label('記事内容')
                                    ->hintActions([
                                        ContentEditor::getButtonInsertAction(),
                                        ContentEditor::getCheckPointInsertAction()
                                    ]),
                                
                                Forms\Components\FileUpload::make('featured_image')
                                    ->label('アイキャッチ画像')
                                    ->image()
                                    ->disk('public')
                                    ->directory('articles')
                                    ->visibility('public')
                                    ->imageEditor()
                                    ->preserveFilenames(false),
                                
                                Forms\Components\Toggle::make('is_featured')
                                    ->label('おすすめ記事に表示')
                                    ->helperText('ホームページのおすすめ記事欄に表示されます'),
                                
                                Forms\Components\Toggle::make('is_published')
                                    ->label('公開する')
                                    ->default(false),
                                
                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label('公開日時')
                                    ->default(now()),
                                
                                Forms\Components\Section::make('監修者情報')
                                    ->schema([
                                        Forms\Components\TextInput::make('supervisor_name')
                                            ->label('監修者名')
                                            ->helperText('記事を監修した専門家の名前'),
                                        
                                        Forms\Components\TextInput::make('supervisor_title')
                                            ->label('監修者肩書き')
                                            ->helperText('専門家の肩書きや役職'),
                                        
                                        Forms\Components\Textarea::make('supervisor_description')
                                            ->label('監修者紹介')
                                            ->rows(3)
                                            ->columnSpanFull()
                                            ->helperText('監修者の経歴や専門分野について'),
                                        
                                        Forms\Components\FileUpload::make('supervisor_avatar')
                                            ->label('監修者アイコン')
                                            ->image()
                                            ->directory('supervisors')
                                            ->disk('public')
                                            ->columnSpanFull()
                                            ->helperText('監修者の写真やアイコン画像'),
                                    ])
                                    ->columns(2)
                                    ->collapsed()
                                    ->columnSpanFull(),
                            ])
                            ->defaultItems(0)
                            ->addActionLabel('新しい記事を追加')
                            ->collapsible()
                            ->collapsed()
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),
                */
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('会社名')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('areas')
                    ->label('対応エリア')
                    ->limit(50)
                    ->formatStateUsing(function ($state, $record) {
                        $displayItems = [];
                        
                        // area_regionsが設定されている場合は地域名を追加
                        if ($record->area_regions && is_array($record->area_regions) && !empty($record->area_regions)) {
                            $regionLabels = [
                                'nationwide' => '全国',
                                'hokkaido_tohoku' => '北海道・東北',
                                'hokuriku_koshinetsu' => '北陸・甲信越',
                                'kanto' => '関東',
                                'tokai' => '東海',
                                'kansai' => '関西',
                                'chugoku' => '中国',
                                'shikoku' => '四国',
                                'kyushu_okinawa' => '九州・沖縄',
                            ];
                            
                            foreach ($record->area_regions as $region) {
                                if (isset($regionLabels[$region])) {
                                    $displayItems[] = $regionLabels[$region];
                                }
                            }
                        }
                        
                        // 全国が含まれている場合は「全国」のみ表示
                        if (in_array('全国', $displayItems)) {
                            return '全国';
                        }
                        
                        // areasフィールドの都道府県も追加（地域名と重複しないもの）
                        if ($state) {
                            $areas = is_string($state) ? json_decode($state, true) : $state;
                            if (is_array($areas)) {
                                // 全47都道府県の場合は「全国」表示
                                if (count($areas) >= 47) {
                                    return '全国';
                                }
                                
                                // 地域に含まれない個別の都道府県を追加
                                $regionPrefectures = [
                                    'hokkaido_tohoku' => ['北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県'],
                                    'kanto' => ['茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県'],
                                    'hokuriku_koshinetsu' => ['新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県'],
                                    'tokai' => ['岐阜県', '静岡県', '愛知県', '三重県'],
                                    'kansai' => ['大阪府', '兵庫県', '京都府', '滋賀県', '奈良県', '和歌山県'],
                                    'chugoku' => ['鳥取県', '島根県', '岡山県', '広島県', '山口県'],
                                    'shikoku' => ['徳島県', '香川県', '愛媛県', '高知県'],
                                    'kyushu_okinawa' => ['福岡県', '佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県'],
                                ];
                                
                                $selectedRegionPrefectures = [];
                                if ($record->area_regions && is_array($record->area_regions)) {
                                    foreach ($record->area_regions as $region) {
                                        if (isset($regionPrefectures[$region])) {
                                            $selectedRegionPrefectures = array_merge($selectedRegionPrefectures, $regionPrefectures[$region]);
                                        }
                                    }
                                }
                                
                                foreach ($areas as $area) {
                                    if (!in_array($area, $selectedRegionPrefectures)) {
                                        $displayItems[] = $area;
                                    }
                                }
                            }
                        }
                        
                        if (empty($displayItems)) {
                            return '-';
                        }
                        
                        // 表示項目数を制限
                        if (count($displayItems) > 5) {
                            return implode('・', array_slice($displayItems, 0, 5)) . '…';
                        }
                        
                        return implode('・', $displayItems);
                    })
                    ->tooltip(function ($record) {
                        $displayItems = [];
                        
                        // 地域名を追加
                        if ($record->area_regions && is_array($record->area_regions) && !empty($record->area_regions)) {
                            $regionLabels = [
                                'nationwide' => '全国',
                                'hokkaido_tohoku' => '北海道・東北',
                                'hokuriku_koshinetsu' => '北陸・甲信越',
                                'kanto' => '関東',
                                'tokai' => '東海',
                                'kansai' => '関西',
                                'chugoku' => '中国',
                                'shikoku' => '四国',
                                'kyushu_okinawa' => '九州・沖縄',
                            ];
                            
                            foreach ($record->area_regions as $region) {
                                if (isset($regionLabels[$region])) {
                                    $displayItems[] = $regionLabels[$region];
                                }
                            }
                        }
                        
                        // 都道府県を追加
                        if ($record->areas) {
                            $areas = is_string($record->areas) ? json_decode($record->areas, true) : $record->areas;
                            if (is_array($areas)) {
                                $displayItems = array_merge($displayItems, $areas);
                            }
                        }
                        
                        return empty($displayItems) ? '設定なし' : implode(', ', array_unique($displayItems));
                    }),
                Tables\Columns\IconColumn::make('rope_support')
                    ->label('ロープアクセス')
                    ->boolean(),
                Tables\Columns\IconColumn::make('branco_supported')
                    ->label('ブランコ')
                    ->boolean(),
                Tables\Columns\IconColumn::make('aerial_platform_supported')
                    ->label('高所作業車')
                    ->boolean(),
                Tables\Columns\IconColumn::make('gondola_supported')
                    ->label('ゴンドラ')
                    ->boolean(),
                Tables\Columns\IconColumn::make('emergency_supported')
                    ->label('緊急対応')
                    ->boolean(),
                Tables\Columns\TextColumn::make('service_categories')
                    ->label('サービス')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return '-';
                        
                        if (is_string($state)) {
                            $decoded = json_decode($state, true);
                            $state = is_array($decoded) ? $decoded : [$state];
                        }
                        
                        if (!is_array($state)) return '-';
                        
                        $labels = [
                            'window' => '窓ガラス',
                            'exterior' => '外壁清掃',
                            'inspection' => '外壁調査',
                            'repair' => '外壁補修',
                            'painting' => '外壁塗装',
                            'bird_control' => '鳥害対策',
                            'sign' => '看板作業',
                            'leak_inspection' => '雨漏り調査'
                        ];
                        return collect($state)->map(fn($key) => $labels[$key] ?? $key)->implode('・');
                    })
                    ->limit(20),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('おすすめ')
                    ->boolean(),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('公開日時')
                    ->dateTime()
                    ->sortable(),
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
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
