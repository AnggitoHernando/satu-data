<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class JenisData extends Model
{
    use HasFactory;

    protected $table = 'jenis_data';
    protected $fillable = [
        'judul_data',
        'jenis_data',
        'seksi_id',
        'slug',
        'deskripsi',
        'tahun',
        'sumber_data',
        'status_data',
        'file_path',
        'nama_original_file',
        'extension_file'
    ];
    public function seksi()
    {
        return $this->belongsTo(Seksi::class, 'seksi_id');
    }
}
