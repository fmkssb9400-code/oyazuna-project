<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\PageView;
use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;

class TopArticlesWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getTableHeading(): string
    {
        return '人気記事ランキング（今月）';
    }

    protected function getTableQuery(): Builder
    {
        return PageView::query()
            ->selectRaw('article_id, COUNT(*) as total_views, MAX(id) as id')
            ->thisMonth()
            ->articles()
            ->groupBy('article_id')
            ->orderByDesc('total_views')
            ->with(['article' => function ($query) {
                $query->published(); // Only show published articles
            }])
            ->whereHas('article', function ($query) {
                $query->published(); // Only include page views for published articles
            })
            ->limit(10);
    }

    public function getTableRecordKey($record): string
    {
        return (string) $record->id;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                Tables\Columns\TextColumn::make('rank')
                    ->label('#')
                    ->state(fn ($rowLoop) => $rowLoop->index + 1)
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('article.title')
                    ->label('記事タイトル')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->article?->title)
                    ->url(fn ($record) => route('news.show', $record->article?->slug))
                    ->openUrlInNewTab(),

                Tables\Columns\TextColumn::make('total_views')
                    ->label('PV数')
                    ->formatStateUsing(fn ($state) => number_format($state))
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('article.published_at')
                    ->label('公開日')
                    ->date('Y/m/d')
                    ->alignCenter(),
            ])
            ->defaultSort('total_views', 'desc')
            ->paginated(false);
    }
}