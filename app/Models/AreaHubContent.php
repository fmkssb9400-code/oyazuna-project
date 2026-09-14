<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaHubContent extends Model
{
    protected $fillable = [
        'area_slug',
        'hub_slug',
        'title',
        'meta_description',
        'content',
    ];
}
