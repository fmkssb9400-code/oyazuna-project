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
    ];

    protected $casts = [
        'images' => 'array',
        'ocr_data' => 'array',
        'ai_analysis' => 'array',
        'quote_date' => 'date',
    ];

    public const WORK_TYPES = [
        'window_cleaning' => '窓ガラス清掃',
        'exterior_painting' => '外壁塗装',
        'exterior_cleaning' => '外壁清掃',
        'exterior_inspection' => '外壁点検',
        'exterior_repair' => '外壁補修',
        'leak_survey' => '雨漏り調査',
        'bird_control' => '鳥害対策',
        'signboard_work' => '看板作業',
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
