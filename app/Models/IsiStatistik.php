<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class IsiStatistik extends Model
{
    protected $fillable = [
        "group_kategori_item_id",
        "tahun",
        "value"
    ];
    protected $table = "isi_statistiks";

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where(function ($queryUtama) use ($search) {
                    $queryUtama
                        ->whereHas('groupKategoriItem', function ($subQuery) use ($search) {
                            $subQuery->where('nama_item', 'like', "%{$search}%");
                        })
                        ->orWhereHas('groupKategoriItem.groupKategori', function ($subQuery) use ($search) {
                            $subQuery->where('nama_group', 'like', "%{$search}%");
                        })
                        ->orWhereHas('groupKategoriItem.groupKategori.kategoriData', function ($subQuery) use ($search) {
                            $subQuery->where('nama_kategori', 'like', "%{$search}%");
                        });
                });
            })

            ->when($filters['tahun'] ?? null, fn($q, $tahun) => $q->where('tahun', $tahun))
            ->when($filters['seksi_id'] ?? null, fn($q, $seksiId) => $q->whereHas('groupKategoriItem.groupKategori.kategoriData', fn($q2) => $q2->where('seksi_id', $seksiId)))

            ->when($filters['sortBy'] ?? null, function ($q, $sortBy) use ($filters) {
                $direction = ($filters['sortDir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
                if ($sortBy === 'groupKategoriItem.nama_item') {
                    return $q->join('group_kategori_items', 'isi_statistiks.group_kategori_item_id', '=', 'group_kategori_items.id')
                        ->orderBy('group_kategori_items.nama_item', $direction)
                        ->select('isi_statistiks.*');
                }

                $allowedSorts = ['tahun', 'value', 'created_at', 'id'];
                $sort = in_array($sortBy, $allowedSorts) ? $sortBy : 'id';

                $q->orderBy($sort, $direction);
            }, fn($q) => $q->latest());
    }



    public function groupKategoriItem()
    {
        return $this->belongsTo(GroupKategoriItem::class, 'group_kategori_item_id');
    }
}
