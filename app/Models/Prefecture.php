<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prefecture extends Model
{
    protected $fillable = [
        'name',
        'slug',
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
