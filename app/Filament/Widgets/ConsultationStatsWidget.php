<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\ConsultationSubmission;
use Illuminate\Database\Eloquent\Builder;

class ConsultationStatsWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    protected function getTableHeading(): string
    {
        return '相談フォーム送信履歴（今月）';
    }

    protected function getTableQuery(): Builder
    {
        return ConsultationSubmission::query()
            ->thisMonth()
            ->orderByDesc('submitted_at')
            ->limit(20);
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
                Tables\Columns\TextColumn::make('submitted_at')
                    ->label('送信日時')
                    ->dateTime('Y/m/d H:i')
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('form_data')
                    ->label('依頼者')
                    ->formatStateUsing(function ($state) {
                        $data = is_string($state) ? json_decode($state, true) : $state;
                        return $data['name'] ?? '不明';
                    })
                    ->limit(30),

                Tables\Columns\TextColumn::make('form_data')
                    ->label('種別')
                    ->formatStateUsing(function ($state) {
                        $data = is_string($state) ? json_decode($state, true) : $state;
                        return match($data['client_kind'] ?? '') {
                            'corp' => '法人',
                            'personal' => '個人',
                            default => '不明'
                        };
                    })
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('form_data')
                    ->label('依頼内容')
                    ->formatStateUsing(function ($state) {
                        $data = is_string($state) ? json_decode($state, true) : $state;
                        return $data['service_category_id'] ?? '不明';
                    })
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('form_data')
                    ->label('希望時期')
                    ->formatStateUsing(function ($state) {
                        $data = is_string($state) ? json_decode($state, true) : $state;
                        return match($data['preferred_timing'] ?? '') {
                            'urgent' => '至急',
                            'this_week' => '今週中',
                            'this_month' => '今月中',
                            'undecided' => '未定',
                            default => '不明'
                        };
                    })
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('form_data')
                    ->label('送信先業者数')
                    ->formatStateUsing(function ($state) {
                        $data = is_string($state) ? json_decode($state, true) : $state;
                        return ($data['target_companies_count'] ?? 0) . '社';
                    })
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP')
                    ->limit(15)
                    ->alignCenter(),
            ])
            ->defaultSort('submitted_at', 'desc')
            ->paginated(false);
    }
}