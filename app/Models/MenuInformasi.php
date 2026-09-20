<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;
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
    protected $appends = ['breadcrumb', 'full_path']; //Untuk menambahkan otomatis fungsi ketika di get()

    protected $casts = [
        'is_active' => 'boolean',
        'urutan'    => 'integer',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function parentRecursive(): BelongsTo
    {
        return $this->parent()->with('parentRecursive');
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
        return $this->hasMany(PpidInformasi::class, 'menu_id')->orderBy('nama_informasi', 'asc');
    }

    public function halamanStatis(): HasOne
    {
        return $this->hasOne(HalamanStatis::class, 'menu_id');
    }

    public function getIsGroupAttribute(): bool
    {
        return $this->children()->exists();
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where(function ($queryUtama) use ($search) {
                    $queryUtama
                        ->whereAny(['judul'], 'like', "%{$search}%")
                        ->orWhereHas('menu', function ($subQuery) use ($search) {
                            $subQuery->where('nama_menu', 'like', "%{$search}%");
                        });
                });
            })

            ->when($filters['seksi_id'] ?? null, fn($q, $seksiId) => $q->where('seksi_id', $seksiId))

            ->when($filters['sortBy'] ?? null, function ($q, $sortBy) use ($filters) {
                $direction = ($filters['sortDir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
                if ($sortBy === 'seksi.nama_seksi') {
                    return $q->join('seksi', 'halaman_statis.seksi_id', '=', 'seksi.id')
                        ->orderBy('seksi.nama_seksi', $direction)
                        ->select('halaman_statis.*'); // Penting agar ID tidak tertukar
                }

                $allowedSorts = ['judul', 'gambar_utama', 'meta_deskripsi', 'id'];
                $sort = in_array($sortBy, $allowedSorts) ? $sortBy : 'id';

                $q->orderBy($sort, $direction);
            }, fn($q) => $q->latest());
    }

    public function getBreadcrumbAttribute(): array
    {
        $trail = [];
        $current = $this;

        while ($current) {
            array_unshift($trail, [
                'id'        => $current->id,
                'nama_menu' => $current->nama_menu,
                'slug'      => $current->slug,
            ]);
            $current = $current->parent;
        }

        return $trail;
    }

    public function getFullPathAttribute(): string
    {
        return collect($this->breadcrumb)->pluck('slug')->implode('/');
    }

    public function getJenisTampilanAttribute(): string
    {
        if ($this->is_group) {
            return 'grup';
        }

        if ($this->tipe === 'halaman_statis') {
            return $this->halamanStatis()->exists()
                ? 'halaman_statis'
                : 'halaman_statis_kosong';
        }

        return 'daftar_informasi';
    }
}
