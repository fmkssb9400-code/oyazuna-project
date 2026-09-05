<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerInquiry extends Model
{
    protected $fillable = [
        'company_name',
        'contact_name',
        'email',
        'phone',
        'message',
        'ip_address',
        'user_agent',
    ];
}
