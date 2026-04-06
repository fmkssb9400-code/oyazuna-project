<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    protected $fillable = [
        'key',
        'label',
    ];

    public function companies()
    {
        return $this->belongsToMany(Company::class);
    }

    public function quoteRequests()
    {
        return $this->hasMany(QuoteRequest::class);
    }
}
