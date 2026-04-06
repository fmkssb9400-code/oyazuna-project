<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use App\Filament\Components\BlockEditor;
use App\Services\ContentRenderer;
use App\Filament\Forms\Components\EnhancedRichEditor;
use App\Filament\Forms\Components\ContentEditor;
use Filament\Support\RawJs;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = '記事';

    protected static ?string $modelLabel = '記事';

    protected static ?string $pluralModelLabel = '記事';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('記事情報')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('タイトル')
                            ->required()
                            ->afterStateUpdated(fn (string $context, $state, callable $set) => $context === 'create' ? $set('slug', Str::slug($state)) : null),

                        Forms\Components\TextInput::make('slug')
                            ->label('スラッグ')
                            ->required()
                            ->unique(Article::class, 'slug', ignoreRecord: true)
                            ->alphaDash()
                            ->helperText('URLに使用されます（英数字とハイフンのみ）'),

                        Forms\Components\TagsInput::make('tags')
                            ->label('タグ')
                            ->helperText('SEO対策のためのタグを入力してください（Enter区切りで複数追加可能）')
                            ->separator(','),

                        ContentEditor::make('content')
                            ->profile('default')
                            ->hintAction(ContentEditor::getButtonInsertAction()),

                        Forms\Components\FileUpload::make('featured_image')
                            ->label('アイキャッチ画像')
                            ->image()
                            ->disk('public')
                            ->directory('test-uploads')
                            ->maxSize(10240)
                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'image/webp'])
                            ->uploadingMessage('画像をアップロード中...')
                            ->reorderable(false)
                            ->deletable(true)
                            ->previewable(true),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('HTMLブロック管理')
                    ->description('本文中に[html:キー名]と記述することで、ここで設定したHTMLブロックを差し込むことができます。カスタムCSSと組み合わせて独自デザインを作成できます。')
                    ->schema([
                        Forms\Components\Repeater::make('custom_html_blocks')
                            ->label('HTMLブロック')
                            ->schema([
                                Forms\Components\TextInput::make('key')
                                    ->label('キー')
                                    ->required()
                                    ->regex('/^[a-zA-Z0-9_-]+$/')
                                    ->helperText('英数字、ハイフン、アンダースコアのみ使用可能。本文で[html:このキー]として呼び出します')
                                    ->placeholder('例: info_box')
                                    ->rules(['required', 'regex:/^[a-zA-Z0-9_-]+$/'])
                                    ->validationMessages([
                                        'regex' => 'キーには英数字、ハイフン、アンダースコアのみ使用できます。',
                                        'required' => 'キーは必須です。'
                                    ]),
                                
                                Forms\Components\TextInput::make('label')
                                    ->label('ブロック名')
                                    ->required()
                                    ->helperText('管理画面での表示用の名前')
                                    ->placeholder('例: 情報ボックス'),
                                
                                Forms\Components\Textarea::make('html')
                                    ->label('HTMLコード')
                                    ->required()
                                    ->rows(8)
                                    ->columnSpanFull()
                                    ->helperText('scriptタグや危険な属性は自動的に除去されます。カスタムCSSでスタイルを指定してください。')
                                    ->placeholder('<div class="info-box">
  <div class="info-icon">📋</div>
  <div class="info-content">
    <h3>重要なお知らせ</h3>
    <p>ここに重要な情報を記載します。</p>
  </div>
</div>'),
                            ])
                            ->defaultItems(0)
                            ->addActionLabel('新しいHTMLブロックを追加')
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? '新しいブロック')
                            ->columnSpanFull()
                            ->helperText('本文中に [html:info_box] のように書くとHTMLブロックを差し込めます'),
                    ])
                    ->collapsed(),

                Forms\Components\Section::make('カスタムCSS')
                    ->description('この記事専用のCSSスタイルを記述してください')
                    ->schema([
                        Forms\Components\Textarea::make('custom_css')
                            ->label('CSS')
                            ->rows(15)
                            ->columnSpanFull()
                            ->helperText('記事ページ表示時にのみ反映されます。既存デザインに影響しないよう、.article-content-{記事ID} 内でスコープされます')
                            ->placeholder('/* 情報ボックスのスタイル例 */
.info-box {
    display: flex;
    align-items: flex-start;
    background: #f0f8ff;
    border: 2px solid #4a90e2;
    border-radius: 12px;
    padding: 20px;
    margin: 20px 0;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.info-icon {
    font-size: 24px;
    margin-right: 15px;
    background: #4a90e2;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.info-content h3 {
    color: #2c5aa0;
    margin: 0 0 10px 0;
    font-size: 18px;
}

.info-content p {
    margin: 0;
    color: #333;
    line-height: 1.6;
}')
                            ->extraAttributes([
                                'style' => 'font-family: "Monaco", "Consolas", "Courier New", monospace; font-size: 14px;'
                            ]),
                    ])
                    ->collapsed(),

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
                            ->maxSize(5120)
                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'image/webp'])
                            ->uploadingMessage('アバターをアップロード中...')
                            ->reorderable(false)
                            ->deletable(true)
                            ->previewable(true),
                    ])
                    ->columns(2)
                    ->collapsed(),

                Forms\Components\Section::make('公開設定')
                    ->schema([
                        Forms\Components\Toggle::make('is_published')
                            ->label('公開する')
                            ->default(false),

                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('公開日時')
                            ->default(now()),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('おすすめ記事に表示')
                            ->helperText('ホームページと業者一覧ページのおすすめ記事欄に表示されます')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('タイトル')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('スラッグ')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('公開状況')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('おすすめ')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('公開日時')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('作成日時')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TagsColumn::make('tags')
                    ->label('タグ')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('更新日時')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('公開状況')
                    ->placeholder('すべて')
                    ->trueLabel('公開中')
                    ->falseLabel('下書き'),
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
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereNull('company_id');
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
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }

    private static function generateTableInputsHtml($cols, $rows)
    {
        $html = '<div style="background: #f9fafb; padding: 20px; border-radius: 8px; margin: 10px 0;">';
        $html .= '<h4 style="margin-bottom: 15px; color: #374151;">表の内容を入力してください</h4>';
        
        // ヘッダー行入力
        $html .= '<div style="margin-bottom: 20px;">';
        $html .= '<h5 style="margin-bottom: 10px; color: #6b7280;">ヘッダー行:</h5>';
        $html .= '<div style="display: grid; grid-template-columns: repeat(' . $cols . ', 1fr); gap: 10px;">';
        
        for ($col = 0; $col < $cols; $col++) {
            $placeholder = $col === 0 ? '項目' : '比較' . $col;
            $html .= '<input type="text" name="header_' . $col . '" placeholder="' . $placeholder . '" style="padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;">';
        }
        
        $html .= '</div></div>';
        
        // データ行入力
        $html .= '<div>';
        $html .= '<h5 style="margin-bottom: 10px; color: #6b7280;">データ行:</h5>';
        
        for ($row = 0; $row < ($rows - 1); $row++) {
            $html .= '<div style="display: grid; grid-template-columns: repeat(' . $cols . ', 1fr); gap: 10px; margin-bottom: 10px;">';
            
            for ($col = 0; $col < $cols; $col++) {
                $placeholder = $col === 0 ? '項目' . ($row + 1) : '内容を入力';
                $html .= '<input type="text" name="data_' . $row . '_' . $col . '" placeholder="' . $placeholder . '" style="padding: 8px; border: 1px solid #d1d5db; border-radius: 4px;">';
            }
            
            $html .= '</div>';
        }
        
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
    
    private static function generateTableData($cols, $rows, $formData)
    {
        // ヘッダー行を構築
        $headers = [];
        for ($col = 0; $col < $cols; $col++) {
            $headers[] = $formData['header_' . $col] ?? ($col === 0 ? '項目' : '比較' . $col);
        }
        
        // データ行を構築
        $dataRows = [];
        for ($row = 0; $row < ($rows - 1); $row++) {
            $rowData = [];
            for ($col = 0; $col < $cols; $col++) {
                $rowData[] = $formData['data_' . $row . '_' . $col] ?? ($col === 0 ? '項目' . ($row + 1) : '');
            }
            $dataRows[] = $rowData;
        }
        
        return [
            'headers' => $headers,
            'rows' => $dataRows
        ];
    }
    
    private static function generateEditableTableHtml($headers, $rows)
    {
        // ダッシュボードとフロント両方で使えるテーブルHTMLを生成
        $html = '<table class="comparison-table" style="width: 100%; border-collapse: collapse; margin: 20px 0; border: 1px solid #e5e7eb;">
<thead>
<tr style="background-color: #f3f4f6;">';
        
        // ヘッダー行
        foreach ($headers as $header) {
            $html .= '<th style="border: 1px solid #e5e7eb; padding: 12px; font-weight: bold; text-align: left;">' . htmlspecialchars($header ?: '') . '</th>';
        }
        
        $html .= '</tr>
</thead>
<tbody>';
        
        // データ行
        foreach ($rows as $rowIndex => $rowData) {
            $html .= '<tr>';
            
            foreach ($rowData as $colIndex => $cellData) {
                if ($colIndex === 0) {
                    // 1列目は項目列
                    $html .= '<th style="border: 1px solid #e5e7eb; padding: 12px; font-weight: bold; text-align: left; background-color: #f9fafb;">' . htmlspecialchars($cellData ?: '') . '</th>';
                } else {
                    // その他は通常のセル
                    $html .= '<td style="border: 1px solid #e5e7eb; padding: 12px;">' . htmlspecialchars($cellData ?: '') . '</td>';
                }
            }
            
            $html .= '</tr>';
        }
        
        $html .= '</tbody>
</table>';
        
        return $html;
    }
}