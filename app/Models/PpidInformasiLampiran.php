<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PpidInformasiLampiran extends Model
{
    use HasFactory;
    protected $table = 'ppid_informasi_lampiran';
    protected $fillable = [
        'ppid_informasi_id',
        'nama_file',
        'file_path',
        'tipe_file',
        'ukuran_file',
        'urutan',
    ];

    protected $casts = [
        'ukuran_file' => 'integer',
        'urutan'      => 'integer',
    ];

    public function ppidInformasi()
    {
        return $this->belongsTo(PpidInformasi::class, 'ppid_informasi_id');
    }
}
