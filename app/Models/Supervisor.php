<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supervisor extends Model
{
    protected $fillable = [
        'name',
        'title',
        'description',
        'avatar',
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function getAvatarUrlAttribute(): ?string
    {
        if ($this->avatar) {
            return \Storage::disk('public')->url($this->avatar);
        }

        return null;
    }
}
