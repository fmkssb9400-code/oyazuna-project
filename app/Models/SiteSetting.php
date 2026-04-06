<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'label',
        'description',
        'original_filename',
    ];

    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value, $type = 'text', $label = '', $description = '')
    {
        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'label' => $label,
                'description' => $description,
            ]
        );
    }

    public function getImageUrlAttribute()
    {
        if ($this->type === 'image' && $this->value) {
            return Storage::url($this->value);
        }
        return null;
    }

    public static function getSettings()
    {
        $settings = self::all();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting->key] = $setting->value;
        }
        
        return $result;
    }
}
