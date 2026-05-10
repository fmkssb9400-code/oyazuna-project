<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteRecipient extends Model
{
    protected $fillable = [
        'region',
        'building_type',
        'floor_range',
        'order_type',
        'contract_type',
        'quote_items',
        'delivery_status',
        'sent_at',
        'error_message',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'quote_items' => 'array',
    ];

    public function quoteRequest()
    {
        return $this->belongsTo(QuoteRequest::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
