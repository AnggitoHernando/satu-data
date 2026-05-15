<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class KategoriData extends Model
{
    protected $fillable = [
        "seksi_id",
        "nama_kategori",
        "jenis_data_id"
    ];

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where(function ($queryUtama) use ($search) {
                    $queryUtama
                        ->whereAny(['nama_kategori'], 'like', "%{$search}%")

                        ->orWhereHas('seksi', function ($subQuery) use ($search) {
                            $subQuery->where('nama_seksi', 'like', "%{$search}%");
                        })
                        ->orWhereHas('jenisData', function ($subQuery) use ($search) {
                            $subQuery->where('judul_data', 'like', "%{$search}%");
                        });
                });
            })

            ->when($filters['seksi_id'] ?? null, fn($q, $id) => $q->where('seksi_id', $id))

            ->when($filters['sortBy'] ?? null, function ($q, $sortBy) use ($filters) {
                $direction = ($filters['sortDir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
                if ($sortBy === 'seksi.nama_seksi') {
                    return $q->join('seksi', 'kategori_data.seksi_id', '=', 'seksi.id')
                        ->orderBy('seksi.nama_seksi', $direction)
                        ->select('kategori_data.*'); // Penting agar ID tidak tertukar
                }

                $allowedSorts = ['nama_kategori', 'created_at', 'id'];
                $sort = in_array($sortBy, $allowedSorts) ? $sortBy : 'id';


                $q->orderBy($sort, $direction);
            }, fn($q) => $q->latest());
    }

    public function seksi()
    {
        return $this->belongsTo(Seksi::class, 'seksi_id');
    }

    public function isiStatistik()
    {
        return $this->hasMany(IsiStatistik::class, 'kategori_data_id');
    }

    public function jenisData()
    {
        return $this->belongsTo(JenisData::class, 'jenis_data_id');
    }
}
