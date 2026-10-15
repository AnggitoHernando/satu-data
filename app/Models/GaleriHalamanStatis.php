<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GaleriHalamanStatis extends Model
{
    protected $table = 'galeri_halaman_statis';
    protected $fillable = [
        'halaman_statis_id',
        'path',
        'caption',
        'alt_text',
        'urutan',
    ];

    public function halamanStatis()
    {
        return $this->belongsTo(HalamanStatis::class, 'halaman_statis_id');
    }
}
