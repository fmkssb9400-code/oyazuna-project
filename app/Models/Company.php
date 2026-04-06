<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Company extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'slug',
        'description',
        'article_content',
        'website_url',
        'service_areas',
        'rope_support',
        'gondola_supported',
        'branco_supported',
        'aerial_platform_supported',
        'official_url',
        'areas',
        'achievements_summary',
        'safety_items',
        'is_featured',
        'sort_order',
        'security_points',
        'performance_summary',
        'strength_tags',
        'recommend_score',
        'safety_score',
        'performance_score',
        'review_score',
        'review_count',
        'email_quote',
        'phone',
        'address_text',
        'max_floor',
        'emergency_supported',
        'insurance',
        'price_note',
        'rank_score',
        'published_at',
        'service_categories',
        'tags',
        'logo_path',
        'ranking_logo_path',
        'area_regions',
    ];

    protected $casts = [
        'rope_support' => 'boolean',
        'gondola_supported' => 'boolean',
        'branco_supported' => 'boolean',
        'aerial_platform_supported' => 'boolean',
        'areas' => 'array',
        'safety_items' => 'array',
        'is_featured' => 'boolean',
        'security_points' => 'array',
        'strength_tags' => 'array',
        'emergency_supported' => 'boolean',
        'insurance' => 'boolean',
        'published_at' => 'datetime',
        'service_categories' => 'array',
        'tags' => 'array',
        'area_regions' => 'array',
        'sort_order' => 'integer',
        'recommend_score' => 'integer',
        'safety_score' => 'integer',
        'performance_score' => 'integer',
        'review_score' => 'decimal:2',
        'review_count' => 'integer',
        'max_floor' => 'integer',
    ];

    // Scope for published companies
    public function scopePublished(Builder $query)
    {
        return $query->whereNotNull('published_at');
    }

    // Scope for search filtering
    public function scopeForQuote(Builder $query, array $filters)
    {
        $query->published();

        if (isset($filters['prefecture_id'])) {
            $query->whereHas('prefectures', function ($q) use ($filters) {
                $q->where('prefecture_id', $filters['prefecture_id']);
            });
        }

        if (isset($filters['prefecture'])) {
            // First try to find prefecture by slug
            $prefecture = \App\Models\Prefecture::where('slug', $filters['prefecture'])->first();
            if ($prefecture) {
                $query->where(function ($q) use ($prefecture) {
                    // 都道府県での直接検索
                    $q->whereJsonContains('areas', $prefecture->name)
                      ->orWhereJsonContains('areas', '全国対応')
                      // 地域タグでの検索
                      ->orWhere(function ($subQ) use ($prefecture) {
                          $regionMapping = [
                              'hokkaido_tohoku' => ['北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県'],
                              'kanto' => ['茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県'],
                              'hokuriku_koshinetsu' => ['新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県'],
                              'tokai' => ['岐阜県', '静岡県', '愛知県', '三重県'],
                              'kansai' => ['大阪府', '兵庫県', '京都府', '滋賀県', '奈良県', '和歌山県'],
                              'chugoku' => ['鳥取県', '島根県', '岡山県', '広島県', '山口県'],
                              'shikoku' => ['徳島県', '香川県', '愛媛県', '高知県'],
                              'kyushu_okinawa' => ['福岡県', '佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県'],
                          ];
                          
                          // 全国設定の会社
                          $subQ->whereJsonContains('area_regions', 'nationwide');
                          
                          // 該当する地域に含まれている会社
                          foreach ($regionMapping as $region => $prefectures) {
                              if (in_array($prefecture->name, $prefectures)) {
                                  $subQ->orWhereJsonContains('area_regions', $region);
                              }
                          }
                      });
                });
            }
        }

        if (isset($filters['floors'])) {
            $query->where(function ($q) use ($filters) {
                $q->whereNull('max_floor')
                  ->orWhere('max_floor', '>=', $filters['floors']);
            });
        }

        if (isset($filters['building_type_id'])) {
            $query->whereHas('buildingTypes', function ($q) use ($filters) {
                $q->where('building_type_id', $filters['building_type_id']);
            });
        }

        if (isset($filters['service_category_id'])) {
            $query->whereHas('serviceCategories', function ($q) use ($filters) {
                $q->where('service_category_id', $filters['service_category_id']);
            });
        }

        if (isset($filters['preferred_service_method_id'])) {
            $query->whereHas('serviceMethods', function ($q) use ($filters) {
                $q->where('service_method_id', $filters['preferred_service_method_id']);
            });
        }

        if (isset($filters['emergency']) && $filters['emergency']) {
            $query->where('emergency_supported', true);
        }

        return $query;
    }

    // Relationships
    public function prefectures()
    {
        return $this->belongsToMany(Prefecture::class);
    }

    public function serviceMethods()
    {
        return $this->belongsToMany(ServiceMethod::class);
    }

    public function buildingTypes()
    {
        return $this->belongsToMany(BuildingType::class);
    }

    public function serviceCategories()
    {
        return $this->belongsToMany(ServiceCategory::class);
    }

    public function assets()
    {
        return $this->hasMany(CompanyAsset::class);
    }

    public function quoteRecipients()
    {
        return $this->hasMany(QuoteRecipient::class);
    }

    // Helper methods
    public function getLogoAsset()
    {
        return $this->assets()->where('kind', 'logo')->first();
    }

    public function getGalleryAssets()
    {
        return $this->assets()->where('kind', 'gallery')->orderBy('sort_order')->get();
    }

    // Review relationship
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    // Additional scopes for card UI
    public function scopeFeatured(Builder $query)
    {
        return $query->where('is_featured', true)->orderBy('sort_order');
    }

    public function scopeSearch(Builder $query, $searchTerm)
    {
        if ($searchTerm) {
            return $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }
        return $query;
    }

    public function scopeFilterAreas(Builder $query, array $areas)
    {
        if (!empty($areas)) {
            return $query->where(function ($q) use ($areas) {
                foreach ($areas as $area) {
                    $q->orWhereJsonContains('areas', $area);
                }
            });
        }
        return $query;
    }

    public function scopeFilterRope(Builder $query, bool $ropeSupport)
    {
        return $query->where('rope_support', $ropeSupport);
    }

    // Accessors for displaying array fields as comma-separated strings in forms
    public function getServiceCategoriesForFormAttribute()
    {
        return is_array($this->service_categories) ? implode(', ', $this->service_categories) : '';
    }
    
    public function getStrengthTagsForFormAttribute()
    {
        return is_array($this->strength_tags) ? implode(', ', $this->strength_tags) : '';
    }
    
    public function getSafetyItemsForFormAttribute()
    {
        return is_array($this->safety_items) ? implode(', ', $this->safety_items) : '';
    }

    // Mutators for handling string inputs from textarea components
    public function setAreasAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['areas'] = json_encode(array_filter(array_map('trim', explode(',', $value))));
        } elseif (is_array($value)) {
            $this->attributes['areas'] = json_encode($value);
        } else {
            $this->attributes['areas'] = $value;
        }
    }

    public function setServiceCategoriesAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['service_categories'] = json_encode(array_filter(array_map('trim', explode(',', $value))));
        } elseif (is_array($value)) {
            $this->attributes['service_categories'] = json_encode($value);
        } else {
            $this->attributes['service_categories'] = $value;
        }
    }
    
    public function setStrengthTagsAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['strength_tags'] = json_encode(array_filter(array_map('trim', explode(',', $value))));
        } elseif (is_array($value)) {
            $this->attributes['strength_tags'] = json_encode($value);
        } else {
            $this->attributes['strength_tags'] = $value;
        }
    }
    
    public function setSafetyItemsAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['safety_items'] = json_encode(array_filter(array_map('trim', explode(',', $value))));
        } elseif (is_array($value)) {
            $this->attributes['safety_items'] = json_encode($value);
        } else {
            $this->attributes['safety_items'] = $value;
        }
    }

    // Helper methods for card display
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->published()->avg('total_score') ?: 0;
    }

    public function getReviewsCountAttribute()
    {
        return $this->reviews()->published()->count();
    }

    public function getAreasDisplayAttribute()
    {
        $displayItems = [];
        
        // area_regionsが設定されている場合は地域名を追加
        if ($this->area_regions && is_array($this->area_regions) && !empty($this->area_regions)) {
            $regionLabels = [
                'nationwide' => '全国',
                'hokkaido_tohoku' => '北海道・東北',
                'hokuriku_koshinetsu' => '北陸・甲信越',
                'kanto' => '関東',
                'tokai' => '東海',
                'kansai' => '関西',
                'chugoku' => '中国',
                'shikoku' => '四国',
                'kyushu_okinawa' => '九州・沖縄',
            ];
            
            foreach ($this->area_regions as $region) {
                if (isset($regionLabels[$region])) {
                    $displayItems[] = $regionLabels[$region];
                }
            }
        }
        
        // 全国が含まれている場合は「全国」のみ表示
        if (in_array('全国', $displayItems)) {
            return '全国';
        }
        
        // areasフィールドの都道府県も追加（地域名と重複しないもの）
        if ($this->areas && is_array($this->areas)) {
            // 全47都道府県の場合は「全国」表示
            if (count($this->areas) >= 47) {
                return '全国';
            }
            
            // 地域に含まれない個別の都道府県を追加
            $regionPrefectures = [
                'hokkaido_tohoku' => ['北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県'],
                'kanto' => ['茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県'],
                'hokuriku_koshinetsu' => ['新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県'],
                'tokai' => ['岐阜県', '静岡県', '愛知県', '三重県'],
                'kansai' => ['大阪府', '兵庫県', '京都府', '滋賀県', '奈良県', '和歌山県'],
                'chugoku' => ['鳥取県', '島根県', '岡山県', '広島県', '山口県'],
                'shikoku' => ['徳島県', '香川県', '愛媛県', '高知県'],
                'kyushu_okinawa' => ['福岡県', '佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県'],
            ];
            
            $selectedRegionPrefectures = [];
            if ($this->area_regions && is_array($this->area_regions)) {
                foreach ($this->area_regions as $region) {
                    if (isset($regionPrefectures[$region])) {
                        $selectedRegionPrefectures = array_merge($selectedRegionPrefectures, $regionPrefectures[$region]);
                    }
                }
            }
            
            foreach ($this->areas as $area) {
                if (!in_array($area, $selectedRegionPrefectures)) {
                    $displayItems[] = $area;
                }
            }
        }
        
        if (empty($displayItems)) {
            return '全国対応';
        }
        
        return implode('・', $displayItems);
    }

    // ロゴ画像のURLを取得するアクセサー
    public function getLogoUrlAttribute()
    {
        return $this->logo_path ? asset('storage/' . $this->logo_path) : null;
    }

    // ランキング用ロゴ画像のURLを取得するアクセサー
    public function getRankingLogoUrlAttribute()
    {
        return $this->ranking_logo_path ? asset('storage/' . $this->ranking_logo_path) : null;
    }

    // サービスカテゴリの表示用テキスト
    public function getServiceCategoriesDisplayAttribute()
    {
        if (!$this->service_categories) return '';
        
        $labels = [
            'window' => '窓ガラス清掃',
            'exterior' => '外壁清掃',
            'inspection' => '外壁調査',
            'repair' => '外壁補修', 
            'painting' => '外壁塗装',
            'bird_control' => '鳥害対策',
            'sign' => '看板作業',
            'leak_inspection' => '雨漏り調査',
            'other' => 'その他'
        ];
        
        return collect($this->service_categories)
            ->map(fn($key) => $labels[$key] ?? $key)
            ->implode(' / ');
    }

    // タグが存在するかのヘルパーメソッド
    public function hasTag($tag)
    {
        return in_array($tag, $this->tags ?? []);
    }
}
