<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class IsiStatistik extends Model
{
    protected $fillable = [
        "kategori_data_id",
        "tahun",
        "value"
    ];

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where(function ($queryUtama) use ($search) {
                    $queryUtama
                        ->whereHas('kategoriData', function ($subQuery) use ($search) {
                            $subQuery->where('nama_kategori', 'like', "%{$search}%");
                        })

                        ->orWhereHas('kategoriData.seksi', function ($subQuery) use ($search) {
                            $subQuery->where('nama_seksi', 'like', "%{$search}%");
                        });
                });
            })

            ->when($filters['seksi_id'] ?? null, fn($q, $id) => $q->whereHas('kategoriData.seksi', function ($subQuery) use ($id) {
                $subQuery->where('id', $id);
            }))

            ->when($filters['sortBy'] ?? null, function ($q, $sortBy) use ($filters) {
                $direction = ($filters['sortDir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
                if ($sortBy === 'kategoriData.seksi.nama_seksi') {
                    return $q->join('seksi', 'kategori_data.seksi_id', '=', 'seksi.id')
                        ->orderBy('seksi.nama_seksi', $direction)
                        ->select('kategori_data.*'); // Penting agar ID tidak tertukar
                }
                $allowedSorts = ['tahun', 'value', 'created_at', 'id'];
                $sort = in_array($sortBy, $allowedSorts) ? $sortBy : 'id';


                $q->orderBy($sort, $direction);
            }, fn($q) => $q->latest());
    }

    public function kategoriData()
    {
        return $this->belongsTo(KategoriData::class, 'kategori_data_id');
    }
}
