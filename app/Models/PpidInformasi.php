<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpidInformasi extends Model
{
    protected $table = 'ppid_informasi';
    protected $fillable = [
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
    ];

    protected $casts = [
        'waktu_pembuatan'  => 'date',
        'waktu_penguasaan' => 'date',
    ];

    public function jenisData()
    {
        return $this->belongsTo(JenisData::class, 'jenis_data_id');
    }

    // ─── Relasi ke seksi ─────────────────────────────────────────
    public function seksi()
    {
        return $this->belongsTo(Seksi::class, 'seksi_id');
    }

    // ─── Scope filter ─────────────────────────────────────────────
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
}
