<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PpidInformasi extends Model
{
    protected $table = 'ppid_informasi';
    protected $fillable = [
        'menu_id',
        'jenis_data_id',
        'seksi_id',
        'nama_informasi',
        'ringkasan',
        'pejabat_penguasa',
        'unit_kerja',
        'waktu_pembuatan',
        'waktu_penguasaan',
        'kategori',
        'bentuk_dokumen',
        'bahasa',
        'status',
        'keterangan',
        'tahun',
    ];

    protected $casts = [
        'waktu_pembuatan'  => 'date',
        'waktu_penguasaan' => 'date',
    ];

    public function jenisData()
    {
        return $this->belongsTo(JenisData::class, 'jenis_data_id');
    }

    public function seksi()
    {
        return $this->belongsTo(Seksi::class, 'seksi_id');
    }

    public function scopeKategori($query, string $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    public function scopeDapatDiakses($query)
    {
        return $query->where('status', 'dapat_diakses');
    }

    public function scopeDikecualikan($query)
    {
        return $query->where('status', 'dikecualikan');
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where(function ($queryUtama) use ($search) {
                    $queryUtama
                        ->whereAny(['nama_informasi'], 'like', "%{$search}%")
                        ->orWhereAny(['unit_kerja'], 'like', "%{$search}");
                    // ->orWhereHas('groupKategoriItem.groupKategori', function ($subQuery) use ($search) {
                    //     $subQuery->where('nama_group', 'like', "%{$search}%");
                    // })
                    // ->orWhereHas('groupKategoriItem.groupKategori.kategoriData', function ($subQuery) use ($search) {
                    //     $subQuery->where('nama_kategori', 'like', "%{$search}%");
                    // });
                });
            })

            ->when($filters['kategori'] ?? null, fn($q, $kategori) => $q->where('kategori', $kategori))
            ->when($filters['status'] ?? null, fn($q, $status) => $q->where('status', $status))
            // ->when($filters['seksi_id'] ?? null, fn($q, $seksiId) => $q->whereHas('groupKategoriItem.groupKategori.kategoriData', fn($q2) => $q2->where('seksi_id', $seksiId)))

            ->when($filters['sortBy'] ?? null, function ($q, $sortBy) use ($filters) {
                $direction = ($filters['sortDir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
                // if ($sortBy === 'groupKategoriItem.nama_item') {
                //     return $q->join('group_kategori_items', 'isi_statistiks.group_kategori_item_id', '=', 'group_kategori_items.id')
                //         ->orderBy('group_kategori_items.nama_item', $direction)
                //         ->select('isi_statistiks.*');
                // }

                $allowedSorts = ['nama_informasi', 'kategori', 'value', 'created_at', 'id'];
                $sort = in_array($sortBy, $allowedSorts) ? $sortBy : 'id';

                $q->orderBy($sort, $direction);
            }, fn($q) => $q->latest());
    }

    public function lampiran()
    {
        return $this->hasMany(PpidInformasiLampiran::class, 'ppid_informasi_id');
    }

    public function menu()
    {
        return $this->belongsTo(MenuInformasi::class, 'menu_id');
    }
}
