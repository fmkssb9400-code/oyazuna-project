<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteRecipient extends Model
{
    protected $fillable = [
        'quote_request_id',
        'company_id',
        'delivery_status',
        'sent_at',
        'error_message',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
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
