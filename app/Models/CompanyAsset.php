<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyAsset extends Model
{
    protected $fillable = [
        'company_id',
        'kind',
        'path',
        'caption',
        'sort_order',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
