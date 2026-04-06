<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'company_id',
        'reviewer_name',
        'company_name',
        'service_category',
        'building_type',
        'project_scale',
        'usage_period',
        'continue_request',
        'good_points',
        'improvement_points',
        'service_quality',
        'staff_response',
        'value_for_money',
        'would_use_again',
        'total_score',
        'status',
    ];

    protected $casts = [
        'service_quality' => 'integer',
        'staff_response' => 'integer',
        'value_for_money' => 'integer', 
        'would_use_again' => 'integer',
        'total_score' => 'decimal:2',
    ];

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // Scopes
    public function scopePublished(Builder $query)
    {
        return $query->where('status', 'published');
    }
    
    // Accessors & Mutators
    public function getTotalScoreAttribute($value)
    {
        // If total_score is already calculated, return it
        if ($value !== null) {
            return (float) $value;
        }
        
        // Calculate total_score from 4 ratings
        return $this->calculateTotalScore();
    }
    
    // Calculate total score from 4 rating components
    public function calculateTotalScore()
    {
        $ratings = [
            $this->service_quality,
            $this->staff_response,
            $this->value_for_money,
            $this->would_use_again
        ];
        
        $validRatings = array_filter($ratings, function($rating) {
            return $rating !== null && $rating >= 1 && $rating <= 5;
        });
        
        if (empty($validRatings)) {
            return null;
        }
        
        return round(array_sum($validRatings) / count($validRatings), 2);
    }
    
    // Boot method to automatically calculate total_score
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($review) {
            $review->total_score = $review->calculateTotalScore();
        });
    }
    
    // Accessors for Japanese labels
    public function getServiceCategoryJaAttribute()
    {
        $mapping = [
            'window_cleaning' => '窓ガラス清掃',
            'exterior_cleaning' => '外壁清掃',
            'roof_cleaning' => '屋根清掃',
            'gutter_cleaning' => '雨樋清掃',
            'solar_panel_cleaning' => 'ソーラーパネル清掃',
            'general_cleaning' => '一般清掃',
        ];
        
        return $mapping[$this->service_category] ?? $this->service_category;
    }
    
    public function getBuildingTypeJaAttribute()
    {
        $mapping = [
            'office' => 'オフィスビル',
            'apartment' => 'マンション・アパート',
            'hospital' => '病院・医療施設',
            'school' => '学校・教育施設',
            'hotel' => 'ホテル・宿泊施設',
            'retail' => '商業施設',
            'factory' => '工場・倉庫',
            'other' => 'その他',
        ];
        
        return $mapping[$this->building_type] ?? $this->building_type;
    }
    
    public function getProjectScaleJaAttribute()
    {
        $mapping = [
            'small' => '小規模',
            'medium' => '中規模',
            'large' => '大規模',
        ];
        
        return $mapping[$this->project_scale] ?? $this->project_scale;
    }
    
    public function getUsagePeriodJaAttribute()
    {
        $mapping = [
            'within_1month' => '1ヶ月以内',
            'within_3months' => '3ヶ月以内',
            'within_6months' => '6ヶ月以内',
            'over_6months' => '6ヶ月以上',
        ];
        
        return $mapping[$this->usage_period] ?? $this->usage_period;
    }
    
    public function getContinueRequestJaAttribute()
    {
        $mapping = [
            'definitely_yes' => 'ぜひお願いしたい',
            'probably_yes' => 'たぶんお願いする',
            'maybe' => 'わからない',
            'probably_no' => 'たぶんお願いしない',
            'definitely_no' => 'お願いしない',
        ];
        
        return $mapping[$this->continue_request] ?? $this->continue_request;
    }
}
