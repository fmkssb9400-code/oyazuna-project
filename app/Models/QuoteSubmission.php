<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuoteSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_type',
        'prefecture',
        'comment',
        'images',
        'building_floors',
        'order_status',
        'quote_date',
        'ocr_data',
        'ai_analysis',
        'status',
        'admin_notes',
        'total_amount',
        'quote_items',
        'floor_count',
        'work_area',
        'area_unit',
        'work_description',
    ];

    protected $casts = [
        'images' => 'array',
        'ocr_data' => 'array',
        'ai_analysis' => 'array',
        'quote_items' => 'array',
        'quote_date' => 'date',
    ];

    public const WORK_TYPES = [
        'window' => '窓ガラス清掃',
        'inspection' => '外壁調査',
        'repair' => '外壁補修',
        'painting' => '外壁塗装',
        'bird_control' => '鳥害対策',
        'sign' => '看板作業',
        'leak' => '雨漏り調査',
        'other' => 'その他',
    ];

    public const ORDER_STATUSES = [
        'yes' => '依頼した',
        'no' => '依頼していない',
        'considering' => '検討中',
    ];

    public const STATUSES = [
        'pending' => '未処理',
        'processing' => '処理中',
        'completed' => '完了',
        'rejected' => '却下',
    ];
}
