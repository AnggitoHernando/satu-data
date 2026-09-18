<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class HalamanStatis extends Model
{
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
}
