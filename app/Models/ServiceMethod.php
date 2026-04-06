<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceMethod extends Model
{
    protected $fillable = [
        'key',
        'label',
    ];

    public function companies()
    {
        return $this->belongsToMany(Company::class);
    }

    public function quoteRequestsAsPreferred()
    {
        return $this->hasMany(QuoteRequest::class, 'preferred_service_method_id');
    }
}
