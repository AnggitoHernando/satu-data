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
        'extension_file',
        'status_upload',
        'error_message_upload'
    ];
    public function seksi()
    {
        return $this->belongsTo(Seksi::class, 'seksi_id');
    }
    public function fields()
    {
        return $this->hasMany(JenisDataFields::class, 'jenis_data_id');
    }

    public function rows()
    {
        return $this->hasMany(JenisDataRecords::class, 'jenis_data_id');
    }
}
