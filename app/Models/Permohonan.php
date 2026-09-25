<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class Permohonan extends Model
{
    use HasFactory;

    protected $table = 'permohonan';

    protected $fillable = [
        'nomor_registrasi',
        'jenis',
        'permohonan_asal_id',
        'nama_lengkap',
        'email',
        'pekerjaan',
        'no_telepon',
        'alamat',
        'bukti_identitas',
        'rincian_informasi',
        'cara_mendapatkan',
        'tujuan_penggunaan',
        'alasan_keberatan',
        'status',
        'tanggapan',
        'tanggal_tanggapan',
    ];

    protected $casts = [
        'tanggal_tanggapan' => 'date',
    ];

    public const STATUS_LABELS = [
        'diajukan'     => 'Diajukan',
        'diverifikasi' => 'Diverifikasi',
        'diproses'     => 'Sedang Diproses',
        'ditanggapi'   => 'Sudah Ditanggapi',
        'selesai'      => 'Selesai',
        'ditolak'      => 'Ditolak',
    ];

    public const CARA_MENDAPATKAN_LABELS = [
        'email_download' => 'Email / Download',
        'ambil_langsung' => 'Ambil Langsung',
        'pos'             => 'Pos',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $permohonan) {
            if (blank($permohonan->nomor_registrasi)) {
                $permohonan->nomor_registrasi = self::generateNomorRegistrasi($permohonan->jenis);
            }
        });
    }

    public const PREFIX_JENIS = [
        'informasi' => 'INF',
        'keberatan' => 'KBR',
    ];


    public static function generateNomorRegistrasi(string $jenis): string
    {
        $tahun = now()->year;
        $bulan = now()->format('m');
        $prefix = self::PREFIX_JENIS[$jenis] ?? strtoupper(substr($jenis, 0, 3));
        $pola = "PPID{$prefix}{$tahun}{$bulan}%";

        return DB::transaction(function () use ($pola, $prefix, $tahun, $bulan) {
            $terakhir = self::where('nomor_registrasi', 'like', $pola)
                ->lockForUpdate()
                ->orderByDesc('id')
                ->value('nomor_registrasi');

            $urutan = $terakhir
                ? ((int) substr($terakhir, -5)) + 1
                : 1;

            return sprintf('PPID%s%d%s%05d', $prefix, $tahun, $bulan, $urutan);
        });
    }

    // ---------- Relasi ----------

    /**
     * Kalau jenis = 'keberatan', ini permohonan informasi asal yang
     * dikeberatankan (diisi dari field "Nomor Registrasi" di form keberatan).
     */
    public function permohonanAsal(): BelongsTo
    {
        return $this->belongsTo(self::class, 'permohonan_asal_id');
    }

    /**
     * Kebalikannya — daftar keberatan yang diajukan atas permohonan ini.
     */
    public function keberatan(): HasMany
    {
        return $this->hasMany(self::class, 'permohonan_asal_id');
    }

    // ---------- Accessor ----------

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function getCaraMendapatkanLabelAttribute(): ?string
    {
        return $this->cara_mendapatkan
            ? (self::CARA_MENDAPATKAN_LABELS[$this->cara_mendapatkan] ?? $this->cara_mendapatkan)
            : null;
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where(function ($queryUtama) use ($search) {
                    $queryUtama
                        ->whereAny(['nomor_registrasi'], 'like', "%{$search}%")
                        ->orWhereAny(['nama_lengkap'], 'like', "%{$search}%")
                        ->orWhereAny(['email'], 'like', "%{$search}%");
                });
            })
            ->when($filters['jenis'] ?? null, fn($q, $jenis) => $q->where('jenis', $jenis))
            ->when($filters['status'] ?? null, fn($q, $status) => $q->where('status', $status))
            ->when($filters['sortBy'] ?? null, function ($q, $sortBy) use ($filters) {
                $direction = ($filters['sortDir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
                $allowedSorts = ['nomor_registrasi', 'nama_lengkap', 'email', 'created_at', 'id'];
                $sort = in_array($sortBy, $allowedSorts) ? $sortBy : 'id';

                $q->orderBy($sort, $direction);
            }, fn($q) => $q->latest());
    }
}
