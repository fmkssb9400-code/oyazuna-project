<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StaticPageResource\Pages;
use App\Filament\Resources\StaticPageResource\RelationManagers;
use App\Models\StaticPage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Forms\Components\ContentEditor;

class StaticPageResource extends Resource
{
    protected static ?string $model = StaticPage::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?string $navigationLabel = '固定記事';
    
    protected static ?string $modelLabel = '固定記事';
    
    protected static ?string $pluralModelLabel = '固定記事';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('基本情報')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('タイトル')
                            ->required()
                            ->afterStateUpdated(fn (string $context, $state, callable $set) => $context === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null),
                        
                        Forms\Components\TextInput::make('slug')
                            ->label('スラッグ（URL用）')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->alphaDash()
                            ->helperText('URLに使用されます（英数字とハイフンのみ）'),
                        
                        Forms\Components\Select::make('page_type')
                            ->label('ページタイプ')
                            ->options(StaticPage::getPageTypes())
                            ->required()
                            ->helperText('ガイドページのタイプを選択してください'),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('コンテンツ')
                    ->schema([
                        ContentEditor::make('content')
                            ->label('ページ内容')
                            ->hintAction(ContentEditor::getButtonInsertAction()),
                        
                        Forms\Components\FileUpload::make('featured_image')
                            ->label('アイキャッチ画像')
                            ->image()
                            ->disk('public')
                            ->directory('static-pages')
                            ->visibility('public')
                            ->imageEditor()
                            ->preserveFilenames(false),
                    ]),
                
                Forms\Components\Section::make('公開設定')
                    ->schema([
                        Forms\Components\Toggle::make('is_published')
                            ->label('公開する')
                            ->default(false),
                        
                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('公開日時')
                            ->default(now()),
                    ])
                    ->columns(2),
                
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
                            ->helperText('監修者の写真またはアイコン（PNG、JPEG、JPG、GIF、WebP形式、最大5MB）'),
                    ])
                    ->columns(2)
                    ->collapsible(),
                
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
                    ->description('このページ専用のCSSスタイルを記述してください')
                    ->schema([
                        Forms\Components\Textarea::make('custom_css')
                            ->label('CSS')
                            ->rows(12)
                            ->columnSpanFull()
                            ->helperText('ページ内でのみ有効なCSSを記述できます。記述したCSSは自動的にページ専用のスコープが適用されます。')
                            ->placeholder('.info-box {
  background: #e8f4fd;
  border: 2px solid #3b82f6;
  border-radius: 8px;
  padding: 20px;
  margin: 20px 0;
}

.info-icon {
  font-size: 24px;
  margin-bottom: 10px;
}

.info-content h3 {
  color: #1e40af;
  margin-bottom: 8px;
}'),
                    ])
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->striped()
            ->deferLoading()
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID'),
                    
                Tables\Columns\TextColumn::make('title')
                    ->label('タイトル')
                    ->limit(50),
                
                Tables\Columns\IconColumn::make('is_published')
                    ->label('公開状態')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('page_type')
                    ->label('ページタイプ')
                    ->options(StaticPage::getPageTypes()),
                
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('公開状態'),
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
            'index' => Pages\ListStaticPages::route('/'),
            'create' => Pages\CreateStaticPage::route('/create'),
            'edit' => Pages\EditStaticPage::route('/{record}/edit'),
        ];
    }
}
