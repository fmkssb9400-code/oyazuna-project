<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Filament\Resources\ReviewResource\RelationManagers;
use App\Models\Review;
use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';
    
    protected static ?string $navigationLabel = '口コミ管理';
    
    protected static ?string $modelLabel = '口コミ';
    
    protected static ?string $pluralModelLabel = '口コミ';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('基本情報')
                    ->schema([
                        Forms\Components\Select::make('company_id')
                            ->label('対象業者')
                            ->relationship('company', 'name')
                            ->required()
                            ->searchable(),
                        Forms\Components\TextInput::make('reviewer_name')
                            ->label('レビュワー名')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('company_name')
                            ->label('レビュワー会社名')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('評価')
                    ->schema([
                        Forms\Components\Select::make('service_quality')
                            ->label('サービス品質')
                            ->options([
                                1 => '★☆☆☆☆ (1点)',
                                2 => '★★☆☆☆ (2点)',
                                3 => '★★★☆☆ (3点)',
                                4 => '★★★★☆ (4点)',
                                5 => '★★★★★ (5点)',
                            ])
                            ->required(),
                        Forms\Components\Select::make('staff_response')
                            ->label('スタッフ対応')
                            ->options([
                                1 => '★☆☆☆☆ (1点)',
                                2 => '★★☆☆☆ (2点)',
                                3 => '★★★☆☆ (3点)',
                                4 => '★★★★☆ (4点)',
                                5 => '★★★★★ (5点)',
                            ])
                            ->required(),
                        Forms\Components\Select::make('value_for_money')
                            ->label('価格満足度')
                            ->options([
                                1 => '★☆☆☆☆ (1点)',
                                2 => '★★☆☆☆ (2点)',
                                3 => '★★★☆☆ (3点)',
                                4 => '★★★★☆ (4点)',
                                5 => '★★★★★ (5点)',
                            ])
                            ->required(),
                        Forms\Components\Select::make('would_use_again')
                            ->label('リピート意向')
                            ->options([
                                1 => '★☆☆☆☆ (1点)',
                                2 => '★★☆☆☆ (2点)',
                                3 => '★★★☆☆ (3点)',
                                4 => '★★★★☆ (4点)',
                                5 => '★★★★★ (5点)',
                            ])
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('詳細内容')
                    ->schema([
                        Forms\Components\Textarea::make('good_points')
                            ->label('良かった点')
                            ->rows(3),
                        Forms\Components\Textarea::make('improvement_points')
                            ->label('改善点・要望')
                            ->rows(3),
                    ]),

                Forms\Components\Section::make('公開設定')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('ステータス')
                            ->options([
                                'pending' => '承認待ち',
                                'published' => '公開中',
                                'rejected' => '非公開（悪質な投稿）',
                            ])
                            ->required()
                            ->default('pending'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company.name')
                    ->label('対象業者')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reviewer_name')
                    ->label('レビュワー')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_score')
                    ->label('総合評価')
                    ->formatStateUsing(fn ($state) => $state ? '★' . number_format($state, 1) : '-')
                    ->sortable(),
                Tables\Columns\TextColumn::make('good_points')
                    ->label('良かった点')
                    ->limit(50)
                    ->tooltip(function ($record) {
                        return $record->good_points;
                    }),
                Tables\Columns\TextColumn::make('improvement_points')
                    ->label('改善点')
                    ->limit(50)
                    ->tooltip(function ($record) {
                        return $record->improvement_points;
                    }),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('ステータス')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'published',
                        'danger' => 'rejected',
                    ])
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'pending' => '承認待ち',
                            'published' => '公開中',
                            'rejected' => '非公開',
                            default => $state,
                        };
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('投稿日時')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('ステータス')
                    ->options([
                        'pending' => '承認待ち',
                        'published' => '公開中',
                        'rejected' => '非公開',
                    ]),
                Tables\Filters\SelectFilter::make('company')
                    ->relationship('company', 'name')
                    ->label('業者'),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('承認')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(function ($record) {
                        $record->update(['status' => 'published']);
                    }),
                Tables\Actions\Action::make('reject')
                    ->label('非公開にする')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status !== 'rejected')
                    ->requiresConfirmation()
                    ->modalHeading('口コミを非公開にしますか？')
                    ->modalDescription('悪質な投稿として非公開にします。この操作は取り消せます。')
                    ->action(function ($record) {
                        $record->update(['status' => 'rejected']);
                    }),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('approve_selected')
                        ->label('選択した口コミを承認')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each(fn ($record) => $record->update(['status' => 'published']));
                        }),
                    Tables\Actions\BulkAction::make('reject_selected')
                        ->label('選択した口コミを非公開')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each(fn ($record) => $record->update(['status' => 'rejected']));
                        }),
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
            'index' => Pages\ListReviews::route('/'),
            // 作成・編集ページは無効化
        ];
    }

    public static function canCreate(): bool
    {
        return false; // 新規作成を無効化
    }

    public static function canEdit($record): bool
    {
        return false; // 編集を無効化
    }
}
