<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class QuoteRequest extends Model
{
    protected $fillable = [
        'public_id',
        'type',
        'client_kind',
        'company_name',
        'name',
        'email',
        'phone',
        'prefecture_id',
        'city_text',
        'building_name',
        'building_type_id',
        'floors',
        'glass_area_type',
        'service_category_id',
        'preferred_service_method_id',
        'preferred_timing',
        'priorities',
        'note',
        'attachments',
        'status',
        'utm_source',
        'utm_medium',
        'utm_campaign',
    ];

    protected $casts = [
        'priorities' => 'array',
        'attachments' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->public_id) {
                $model->public_id = self::generatePublicId();
            }
        });
    }

    public static function generatePublicId()
    {
        $date = Carbon::now()->format('Ymd');
        $random = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 4));
        return "QR-{$date}-{$random}";
    }

    // Relationships
    public function prefecture()
    {
        return $this->belongsTo(Prefecture::class);
    }

    public function buildingType()
    {
        return $this->belongsTo(BuildingType::class);
    }

    public function serviceCategory()
    {
        return $this->belongsTo(ServiceCategory::class);
    }

    public function preferredServiceMethod()
    {
        return $this->belongsTo(ServiceMethod::class, 'preferred_service_method_id');
    }

    public function recipients()
    {
        return $this->hasMany(QuoteRecipient::class);
    }
}
