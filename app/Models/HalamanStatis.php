<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\UploadsFile;

class HalamanStatis extends Model
{
    use UploadsFile;
    protected $fillable = [
        'menu_id',
        'judul',
        'isi_konten',
        'gambar_utama',
        'meta_deskripsi',
    ];

    public function menu()
    {
        return $this->belongsTo(MenuInformasi::class, 'menu_id');
    }

    public function galeri()
    {
        return $this->hasMany(GaleriHalamanStatis::class, 'halaman_statis_id');
    }

    /**
     * @param array $galeri ['baru' => UploadedFile[], 'hapus' => int[], 'alt' => [id => alt_text]]
     */
    public function simpanGaleri(array $galeri): void
    {
        $uploaded = []; // file baru: dibersihkan kalau gagal

        try {
            DB::transaction(function () use ($galeri, &$uploaded) {
                // 1. Hapus yang ditandai (dibatasi ke halaman ini lewat relasi)
                if ($ids = $galeri['hapus'] ?? []) {
                    $q     = $this->galeri()->whereIn('id', $ids);
                    $paths = $q->pluck('path')->all();
                    $q->delete();

                    // File lama baru dihapus setelah transaksi (terluar) benar-benar commit
                    DB::afterCommit(fn() => $this->hapusFile($paths));
                }

                // 2. Perbarui alt text gambar yang dipertahankan
                foreach ($galeri['alt'] ?? [] as $id => $alt) {
                    $this->galeri()->whereKey($id)->update(['alt_text' => $alt ?: null]);
                }

                // 3. Tambah gambar baru di urutan paling akhir
                $urutan = (int) $this->galeri()->max('urutan');
                foreach ($galeri['baru'] ?? [] as $file) {
                    $data = $this->uploadFile($file, 'uploads/HalamanStatis/Galeri');
                    $uploaded[] = $data['file_path'];

                    $this->galeri()->create(['path' => $data['file_path'], 'urutan' => ++$urutan]);
                }
            });
        } catch (\Throwable $e) {
            $this->hapusFile($uploaded);
            throw $e;
        }
    }

    protected static function booted(): void
    {
        // Hapus halaman: file galeri ikut terhapus lewat event model galeri.
        // Hapus baris ini jika memakai SoftDeletes.
        static::deleting(fn(self $h) => $h->galeri->each->delete());
    }
}
