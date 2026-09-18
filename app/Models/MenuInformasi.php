<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class MenuInformasi extends Model
{
    protected $table = 'menu_informasi';
    protected $fillable = [
        'nama_menu',
        'slug',
        'tipe',
        'is_active',
        'parent_id',
        'urutan',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan'    => 'integer',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('urutan');
    }

    public function childrenRecursive(): HasMany
    {
        return $this->children()->with('childrenRecursive');
    }

    public function informasi(): HasMany
    {
        return $this->hasMany(PpidInformasi::class, 'menu_id')->orderBy('urutan');
    }

    public function halamanStatis(): HasOne
    {
        return $this->hasOne(HalamanStatis::class, 'menu_id');
    }

    public function getIsGroupAttribute(): bool
    {
        return $this->children()->exists();
    }
}
