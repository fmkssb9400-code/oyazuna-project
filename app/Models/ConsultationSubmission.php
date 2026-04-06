<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ConsultationSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'ip_address',
        'user_agent',
        'form_data',
        'submitted_at',
    ];

    protected $casts = [
        'form_data' => 'array',
        'submitted_at' => 'datetime',
    ];

    public function scopeThisMonth($query)
    {
        return $query->whereBetween('submitted_at', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ]);
    }

    public function scopeLastMonth($query)
    {
        return $query->whereBetween('submitted_at', [
            Carbon::now()->subMonth()->startOfMonth(),
            Carbon::now()->subMonth()->endOfMonth()
        ]);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('submitted_at', Carbon::today());
    }

    public function scopeYesterday($query)
    {
        return $query->whereDate('submitted_at', Carbon::yesterday());
    }
}
