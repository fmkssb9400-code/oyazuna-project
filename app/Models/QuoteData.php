<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuoteData extends Model
{
    use HasFactory;

    protected $table = 'quote_data';

    protected $fillable = [
        // 共通項目
        'work_type',
        'building_type',
        'floors',
        'prefecture',
        'quote_amount',
        'quote_date',
        'company_name',
        'order_status',
        'memo',
        
        // 窓ガラス清掃用項目
        'window_count',
        'work_surface',
        'cleaning_range',
        'regular_cleaning',
        'rope_work',
        'gondola_lift',
        
        // 外壁塗装用項目
        'painting_area',
        'painting_surface',
        'paint_type',
        'foundation_repair',
        'scaffolding',
        'construction_days',
        
        // 外壁清掃用項目
        'cleaning_area',
        'dirt_type',
        'cleaning_method',
        'work_surfaces',
        'wall_rope_work',
        
        // 外壁点検用項目
        'inspection_range',
        'inspection_method',
        'report_included',
        'photo_submission',
        
        // 外壁補修用項目
        'repair_content',
        'repair_locations',
        'repair_area',
        'materials_included',
        'survey_included',
        
        // 雨漏り調査用項目
        'leak_location',
        'survey_method',
        'leak_report_included',
        'repair_quote_included',
    ];

    protected $casts = [
        'quote_date' => 'date',
        'regular_cleaning' => 'boolean',
        'rope_work' => 'boolean',
        'gondola_lift' => 'boolean',
        'foundation_repair' => 'boolean',
        'scaffolding' => 'boolean',
        'wall_rope_work' => 'boolean',
        'report_included' => 'boolean',
        'photo_submission' => 'boolean',
        'materials_included' => 'boolean',
        'survey_included' => 'boolean',
        'leak_report_included' => 'boolean',
        'repair_quote_included' => 'boolean',
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

    public const BUILDING_TYPES = [
        'apartment' => 'マンション',
        'building' => 'ビル',
        'shop' => '店舗',
        'house' => '戸建て',
        'factory' => '工場・倉庫',
        'other' => 'その他',
    ];

    public const ORDER_STATUSES = [
        'ordered' => '依頼した',
        'not_ordered' => '依頼していない',
        'considering' => '検討中',
    ];
}
