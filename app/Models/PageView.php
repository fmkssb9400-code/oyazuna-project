<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class PageView extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'page_type',
        'article_id',
        'user_agent',
        'ip_address',
        'session_id',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    // アナリティクス用のスコープ
    public function scopeThisMonth($query)
    {
        return $query->whereBetween('viewed_at', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ]);
    }

    public function scopeLastMonth($query)
    {
        return $query->whereBetween('viewed_at', [
            Carbon::now()->subMonth()->startOfMonth(),
            Carbon::now()->subMonth()->endOfMonth()
        ]);
    }

    public function scopeArticles($query)
    {
        return $query->where('page_type', 'article')->whereNotNull('article_id');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('viewed_at', Carbon::today());
    }

    public function scopeYesterday($query)
    {
        return $query->whereDate('viewed_at', Carbon::yesterday());
    }
}
